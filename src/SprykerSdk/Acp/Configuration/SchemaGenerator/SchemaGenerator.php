<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Configuration\SchemaGenerator;

use cebe\openapi\spec\OpenApi;
use SprykerSdk\Acp\Configuration\Reader\AppConfigurationReaderInterface;

class SchemaGenerator implements SchemaGeneratorInterface
{
    /**
     * @var \SprykerSdk\Acp\Configuration\Reader\AppConfigurationReaderInterface
     */
    protected $appConfigurationReader;

    /**
     * @var \SprykerSdk\Acp\Configuration\SchemaGenerator\SchemaWriterInterface
     */
    protected $schemaWriter;

    /**
     * @param \SprykerSdk\Acp\Configuration\Reader\AppConfigurationReaderInterface $appConfigurationReader
     * @param \SprykerSdk\Acp\Configuration\SchemaGenerator\SchemaWriterInterface $schemaWriter
     */
    public function __construct(AppConfigurationReaderInterface $appConfigurationReader, SchemaWriterInterface $schemaWriter)
    {
        $this->appConfigurationReader = $appConfigurationReader;
        $this->schemaWriter = $schemaWriter;
    }

    /**
     * @param string $configurationFilePath
     * @param string $openapiFilePath
     *
     * @return bool
     */
    public function convertConfigurationToSchema(string $configurationFilePath, string $openapiFilePath): bool
    {
        $appConfigurationData = $this->appConfigurationReader->readConfigurationFile($configurationFilePath);

        $generatedSchema = $this->generateSchemaFromConfiguration($appConfigurationData);

        return $this->schemaWriter->writeSchemaToFile($generatedSchema, $openapiFilePath);
    }

    /**
     * @param array $appConfigurationData
     *
     * @return \cebe\openapi\spec\OpenApi
     */
    protected function generateSchemaFromConfiguration(array $appConfigurationData): OpenApi
    {
        $appConfigurationSchema = $this->parseAppConfigurationData($appConfigurationData);

        return new OpenApi([
            'components' => [
                'schemas' => [
                    'AppConfiguration' => $appConfigurationSchema,
                ],
            ],
        ]);
    }

    /**
     * @param array $appConfigurationData
     *
     * @return array
     */
    protected function parseAppConfigurationData(array $appConfigurationData): array
    {
        $propertiesArray = [];

        foreach ($appConfigurationData['properties'] as $propertyKey => $propertyConfiguration) {
            $type = $propertyConfiguration['type'];
            $parsedPropertyConfiguration = [];
            $parsedPropertyConfiguration['type'] = $type;
            if ($type === 'string' && isset($propertyConfiguration['oneOf'])) {
                $enum = [];
                foreach ($propertyConfiguration['oneOf'] as $oneOffField) {
                    $enum = array_merge($enum, $oneOffField['enum']);
                }
                $parsedPropertyConfiguration['enum'] = $enum;
            }

            if ($type === 'array' && isset($propertyConfiguration['items'])) {
                $itemsType = $propertyConfiguration['items']['type'];
                $parsedPropertyConfiguration['items'] = [];
                $parsedPropertyConfiguration['items']['type'] = $itemsType;
                if ($itemsType === 'object') {
                    $parsedPropertyConfiguration['items']['additionalProperties'] = true;
                }

                if (isset($propertyConfiguration['items']['oneOf'])) {
                    $enum = [];
                    foreach ($propertyConfiguration['items']['oneOf'] as $oneOffField) {
                        $enum = array_merge($enum, $oneOffField['enum']);
                    }
                    $parsedPropertyConfiguration['items']['enum'] = $enum;
                }
            }

            $propertiesArray[$propertyKey] = $parsedPropertyConfiguration;
        }

        return [
            'type' => 'object',
            'properties' => $propertiesArray,
            'required' => $appConfigurationData['required'],
        ];
    }
}
