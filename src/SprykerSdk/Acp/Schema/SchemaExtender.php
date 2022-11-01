<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Schema;

use Symfony\Component\Process\Process;
use Transfer\CreateDefaultEndpointsRequestTransfer;
use Transfer\CreateDefaultEndpointsResponseTransfer;
use Transfer\MessageTransfer;

class SchemaExtender implements SchemaExtenderInterface
{
    /**
     * @var \SprykerSdk\Acp\Schema\SchemaConverterInterface
     */
    protected SchemaConverterInterface $schemaConverter;

    /**
     * @var \SprykerSdk\Acp\Schema\SchemaWriterInterface
     */
    protected SchemaWriterInterface $schemaWriter;

    /**
     * @var string
     */
    protected string $acpRootPath;

    /**
     * @param \SprykerSdk\Acp\Schema\SchemaConverterInterface $schemaConverter
     * @param \SprykerSdk\Acp\Schema\SchemaWriterInterface $schemaWriter
     * @param string $acpRootPath
     */
    public function __construct(
        SchemaConverterInterface $schemaConverter,
        SchemaWriterInterface $schemaWriter,
        string $acpRootPath
    ) {
        $this->schemaConverter = $schemaConverter;
        $this->schemaWriter = $schemaWriter;
        $this->acpRootPath = $acpRootPath;
    }

    /**
     * @param \Transfer\CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
     *
     * @return \Transfer\CreateDefaultEndpointsResponseTransfer
     */
    public function extendProjectSchema(CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer): CreateDefaultEndpointsResponseTransfer
    {
        $createDefaultEndpointsResponseTransfer = $this->addDefaultEndpointsToSchema($createDefaultEndpointsRequestTransfer);

        $configurationSchemaGeneratedMessage = $this->convertConfigurationToSchema($createDefaultEndpointsRequestTransfer);

        if ($configurationSchemaGeneratedMessage) {
            $createDefaultEndpointsResponseTransfer->addError($configurationSchemaGeneratedMessage);
        }

        return $createDefaultEndpointsResponseTransfer;
    }

    /**
     * @param \Transfer\CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
     *
     * @return \Transfer\CreateDefaultEndpointsResponseTransfer
     */
    protected function addDefaultEndpointsToSchema(
        CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
    ): CreateDefaultEndpointsResponseTransfer {
        $result = new CreateDefaultEndpointsResponseTransfer();

        $process = new Process([
            $this->acpRootPath . '/vendor/bin/syncapi',
            'schema:openapi:update',
            '--openapi-file',
            $createDefaultEndpointsRequestTransfer->getSchemaFile(),
            '--openapi-doc-file',
            $this->getDefaultEndpointsSchemaPath($createDefaultEndpointsRequestTransfer),
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            $result->addError($this->createMessage(sprintf('Updating openapi schema failed, error output: %s', $process->getErrorOutput())));

            return $result;
        }

        if ($createDefaultEndpointsRequestTransfer->getAddLocal()) {
            $registryFileCopied = $this->copyRegistryLocalFile($createDefaultEndpointsRequestTransfer);
            if (!$registryFileCopied) {
                $result->addError($this->createMessage('Copying local registry file failed'));

                return $result;
            }
        }

        return $result;
    }

    /**
     * @param \Transfer\CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
     *
     * @return string
     */
    protected function getDefaultEndpointsSchemaPath(CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer): string
    {
        if ($createDefaultEndpointsRequestTransfer->getAddLocal()) {
            return $this->acpRootPath . '/config/app/api/openapi/registry_reference_local.yml';
        }

        return $this->acpRootPath . '/config/app/api/openapi/registry_reference_remote.yml';
    }

    /**
     * @param \Transfer\CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
     *
     * @return bool
     */
    protected function copyRegistryLocalFile(CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer): bool
    {
        $registryFilePath = dirname($createDefaultEndpointsRequestTransfer->getSchemaFileOrFail()) . DIRECTORY_SEPARATOR . 'registry.yml';

        return copy($this->acpRootPath . '/config/app/api/openapi/registry.yml', $registryFilePath);
    }

    /**
     * @param \Transfer\CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
     *
     * @return \Transfer\MessageTransfer|null
     */
    protected function convertConfigurationToSchema(CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer): ?MessageTransfer
    {
        $schema = $this->schemaConverter->convertConfigurationToSchemaJson($createDefaultEndpointsRequestTransfer->getConfigurationFileOrFail());

        $process = new Process([
            $this->acpRootPath . '/vendor/bin/syncapi',
            'schema:openapi:update',
            '--openapi-file',
            $createDefaultEndpointsRequestTransfer->getSchemaFile(),
            $schema,
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            return $this->createMessage(sprintf('Updating openapi schema with configuration schema failed, error output: %s', $process->getErrorOutput()));
        }

        return null;
    }

    /**
     * @param string $errorMessage
     *
     * @return \Transfer\MessageTransfer
     */
    protected function createMessage(string $errorMessage): MessageTransfer
    {
        return (new MessageTransfer())
            ->setMessage($errorMessage);
    }
}
