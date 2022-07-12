<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Transfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class ManifestConditionsTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const MANIFEST_FOLDER = 'manifestFolder';

    /**
     * @var string
     */
    public const TRANSLATION_FILE_PATH = 'translationFilePath';

    /**
     * @var string
     */
    public const CONFIGURATION_FILE_PATH = 'configurationFilePath';

    /**
     * @var string|null
     */
    protected $manifestFolder;

    /**
     * @var string|null
     */
    protected $translationFilePath;

    /**
     * @var string|null
     */
    protected $configurationFilePath;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'manifest_folder' => 'manifestFolder',
        'manifestFolder' => 'manifestFolder',
        'ManifestFolder' => 'manifestFolder',
        'translation_file_path' => 'translationFilePath',
        'translationFilePath' => 'translationFilePath',
        'TranslationFilePath' => 'translationFilePath',
        'configuration_file_path' => 'configurationFilePath',
        'configurationFilePath' => 'configurationFilePath',
        'ConfigurationFilePath' => 'configurationFilePath',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::MANIFEST_FOLDER => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'manifest_folder',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::TRANSLATION_FILE_PATH => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'translation_file_path',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::CONFIGURATION_FILE_PATH => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'configuration_file_path',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
    ];

    /**
     * @module AcpSdk
     *
     * @param string|null $manifestFolder
     *
     * @return $this
     */
    public function setManifestFolder($manifestFolder)
    {
        $this->manifestFolder = $manifestFolder;
        $this->modifiedProperties[self::MANIFEST_FOLDER] = true;

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @return string|null
     */
    public function getManifestFolder()
    {
        return $this->manifestFolder;
    }

    /**
     * @module AcpSdk
     *
     * @param string|null $manifestFolder
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setManifestFolderOrFail($manifestFolder)
    {
        if ($manifestFolder === null) {
            $this->throwNullValueException(static::MANIFEST_FOLDER);
        }

        return $this->setManifestFolder($manifestFolder);
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getManifestFolderOrFail()
    {
        if ($this->manifestFolder === null) {
            $this->throwNullValueException(static::MANIFEST_FOLDER);
        }

        return $this->manifestFolder;
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireManifestFolder()
    {
        $this->assertPropertyIsSet(self::MANIFEST_FOLDER);

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @param string|null $translationFilePath
     *
     * @return $this
     */
    public function setTranslationFilePath($translationFilePath)
    {
        $this->translationFilePath = $translationFilePath;
        $this->modifiedProperties[self::TRANSLATION_FILE_PATH] = true;

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @return string|null
     */
    public function getTranslationFilePath()
    {
        return $this->translationFilePath;
    }

    /**
     * @module AcpSdk
     *
     * @param string|null $translationFilePath
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setTranslationFilePathOrFail($translationFilePath)
    {
        if ($translationFilePath === null) {
            $this->throwNullValueException(static::TRANSLATION_FILE_PATH);
        }

        return $this->setTranslationFilePath($translationFilePath);
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getTranslationFilePathOrFail()
    {
        if ($this->translationFilePath === null) {
            $this->throwNullValueException(static::TRANSLATION_FILE_PATH);
        }

        return $this->translationFilePath;
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTranslationFilePath()
    {
        $this->assertPropertyIsSet(self::TRANSLATION_FILE_PATH);

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @param string|null $configurationFilePath
     *
     * @return $this
     */
    public function setConfigurationFilePath($configurationFilePath)
    {
        $this->configurationFilePath = $configurationFilePath;
        $this->modifiedProperties[self::CONFIGURATION_FILE_PATH] = true;

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @return string|null
     */
    public function getConfigurationFilePath()
    {
        return $this->configurationFilePath;
    }

    /**
     * @module AcpSdk
     *
     * @param string|null $configurationFilePath
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setConfigurationFilePathOrFail($configurationFilePath)
    {
        if ($configurationFilePath === null) {
            $this->throwNullValueException(static::CONFIGURATION_FILE_PATH);
        }

        return $this->setConfigurationFilePath($configurationFilePath);
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getConfigurationFilePathOrFail()
    {
        if ($this->configurationFilePath === null) {
            $this->throwNullValueException(static::CONFIGURATION_FILE_PATH);
        }

        return $this->configurationFilePath;
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireConfigurationFilePath()
    {
        $this->assertPropertyIsSet(self::CONFIGURATION_FILE_PATH);

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     * @param bool $ignoreMissingProperty
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $normalizedPropertyName = $this->transferPropertyNameMap[$property] ?? null;

            switch ($normalizedPropertyName) {
                case 'manifestFolder':
                case 'translationFilePath':
                case 'configurationFilePath':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                default:
                    if (!$ignoreMissingProperty) {
                        throw new \InvalidArgumentException(sprintf('Missing property `%s` in `%s`', $property, static::class));
                    }
            }
        }

        return $this;
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function modifiedToArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayRecursiveCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveNotCamelCased();
        }
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function toArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->toArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->toArrayRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->toArrayNotRecursiveNotCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->toArrayNotRecursiveCamelCased();
        }
    }

    /**
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollectionModified($value, $isRecursive, $camelCasedKeys): array
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->modifiedToArray($isRecursive, $camelCasedKeys);

                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollection($value, $isRecursive, $camelCasedKeys): array
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->toArray($isRecursive, $camelCasedKeys);

                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, true);

                continue;
            }
            switch ($property) {
                case 'manifestFolder':
                case 'translationFilePath':
                case 'configurationFilePath':
                    $values[$arrayKey] = $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveNotCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, false);

                continue;
            }
            switch ($property) {
                case 'manifestFolder':
                case 'translationFilePath':
                case 'configurationFilePath':
                    $values[$arrayKey] = $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveNotCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
     * @return void
     */
    protected function initCollectionProperties(): void
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveCamelCased(): array
    {
        return [
            'manifestFolder' => $this->manifestFolder,
            'translationFilePath' => $this->translationFilePath,
            'configurationFilePath' => $this->configurationFilePath,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'manifest_folder' => $this->manifestFolder,
            'translation_file_path' => $this->translationFilePath,
            'configuration_file_path' => $this->configurationFilePath,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'manifest_folder' => $this->manifestFolder instanceof AbstractTransfer ? $this->manifestFolder->toArray(true, false) : $this->manifestFolder,
            'translation_file_path' => $this->translationFilePath instanceof AbstractTransfer ? $this->translationFilePath->toArray(true, false) : $this->translationFilePath,
            'configuration_file_path' => $this->configurationFilePath instanceof AbstractTransfer ? $this->configurationFilePath->toArray(true, false) : $this->configurationFilePath,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'manifestFolder' => $this->manifestFolder instanceof AbstractTransfer ? $this->manifestFolder->toArray(true, true) : $this->manifestFolder,
            'translationFilePath' => $this->translationFilePath instanceof AbstractTransfer ? $this->translationFilePath->toArray(true, true) : $this->translationFilePath,
            'configurationFilePath' => $this->configurationFilePath instanceof AbstractTransfer ? $this->configurationFilePath->toArray(true, true) : $this->configurationFilePath,
        ];
    }
}
