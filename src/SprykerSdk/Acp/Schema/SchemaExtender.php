<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Schema;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;
use Symfony\Component\Process\Process;
use Transfer\CreateDefaultEndpointsRequestTransfer;
use Transfer\CreateDefaultEndpointsResponseTransfer;
use Transfer\MessageTransfer;

class SchemaExtender implements SchemaExtenderInterface
{
    /**
     * @var \SprykerSdk\Acp\Schema\SchemaConverterInterface
     */
    protected $schemaConverter;

    /**
     * @var \SprykerSdk\Acp\Schema\SchemaWriterInterface
     */
    protected $schemaWriter;

    /**
     * @param \SprykerSdk\Acp\Schema\SchemaConverterInterface $schemaConverter
     * @param \SprykerSdk\Acp\Schema\SchemaWriterInterface $schemaWriter
     */
    public function __construct(SchemaConverterInterface $schemaConverter, SchemaWriterInterface $schemaWriter)
    {
        $this->schemaConverter = $schemaConverter;
        $this->schemaWriter = $schemaWriter;
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

        $schema = $this->getDefaultEndpointsSchema($createDefaultEndpointsRequestTransfer);

        $process = new Process([
            'vendor/bin/syncapi',
            'schema:openapi:update',
            '-f',
            $createDefaultEndpointsRequestTransfer->getSchemaFile(),
            $this->schemaWriter->writeSchemaToJsonString($schema),
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
     * @return \cebe\openapi\spec\OpenApi
     */
    protected function getDefaultEndpointsSchema(CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer): OpenApi
    {
        $filePath = getcwd() . '/config/app/api/openapi/registry_reference_remote.yml';

        if ($createDefaultEndpointsRequestTransfer->getAddLocal()) {
            $filePath = getcwd() . '/config/app/api/openapi/registry_reference_local.yml';
        }

        return Reader::readFromYamlFile($filePath, OpenApi::class, false);
    }

    /**
     * @param \Transfer\CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
     *
     * @return bool
     */
    protected function copyRegistryLocalFile(CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer): bool
    {
        $schemaFileParts = explode('/', $createDefaultEndpointsRequestTransfer->getSchemaFile());
        array_pop($schemaFileParts);
        $registryFilePath = implode('/', [...$schemaFileParts, 'registry.yml']);

        return copy(getcwd(). '/config/app/api/openapi/registry.yml', $registryFilePath);
    }

    /**
     * @param \Transfer\CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
     *
     * @return \Transfer\MessageTransfer|null
     */
    protected function convertConfigurationToSchema(CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer): ?MessageTransfer
    {
        $schema = $this->schemaConverter->convertConfigurationToSchemaJson($createDefaultEndpointsRequestTransfer->getConfigurationFile());

        $process = new Process(['vendor/bin/syncapi', 'schema:openapi:update', '-f', $createDefaultEndpointsRequestTransfer->getSchemaFile(), $schema]);
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
