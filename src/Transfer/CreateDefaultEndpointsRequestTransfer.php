<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Transfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class CreateDefaultEndpointsRequestTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const SCHEMA_FILE = 'schemaFile';

    /**
     * @var string
     */
    public const ADD_LOCAL = 'addLocal';

    /**
     * @var string
     */
    public const REMOTE_URL = 'remoteUrl';

    /**
     * @var string|null
     */
    protected $schemaFile;

    /**
     * @var bool|null
     */
    protected $addLocal;

    /**
     * @var string|null
     */
    protected $remoteUrl;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'schema_file' => 'schemaFile',
        'schemaFile' => 'schemaFile',
        'SchemaFile' => 'schemaFile',
        'add_local' => 'addLocal',
        'addLocal' => 'addLocal',
        'AddLocal' => 'addLocal',
        'remote_url' => 'remoteUrl',
        'remoteUrl' => 'remoteUrl',
        'RemoteUrl' => 'remoteUrl',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::SCHEMA_FILE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'schema_file',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::ADD_LOCAL => [
            'type' => 'bool',
            'type_shim' => null,
            'name_underscore' => 'add_local',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::REMOTE_URL => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'remote_url',
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
     * @module Algolia
     *
     * @param string|null $schemaFile
     *
     * @return $this
     */
    public function setSchemaFile($schemaFile)
    {
        $this->schemaFile = $schemaFile;
        $this->modifiedProperties[self::SCHEMA_FILE] = true;

        return $this;
    }

    /**
     * @module Algolia
     *
     * @return string|null
     */
    public function getSchemaFile()
    {
        return $this->schemaFile;
    }

    /**
     * @module Algolia
     *
     * @param string|null $schemaFile
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setSchemaFileOrFail($schemaFile)
    {
        if ($schemaFile === null) {
            $this->throwNullValueException(static::SCHEMA_FILE);
        }

        return $this->setSchemaFile($schemaFile);
    }

    /**
     * @module Algolia
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getSchemaFileOrFail()
    {
        if ($this->schemaFile === null) {
            $this->throwNullValueException(static::SCHEMA_FILE);
        }

        return $this->schemaFile;
    }

    /**
     * @module Algolia
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireSchemaFile()
    {
        $this->assertPropertyIsSet(self::SCHEMA_FILE);

        return $this;
    }

    /**
     * @module Algolia
     *
     * @param bool|null $addLocal
     *
     * @return $this
     */
    public function setAddLocal($addLocal)
    {
        $this->addLocal = $addLocal;
        $this->modifiedProperties[self::ADD_LOCAL] = true;

        return $this;
    }

    /**
     * @module Algolia
     *
     * @return bool|null
     */
    public function getAddLocal()
    {
        return $this->addLocal;
    }

    /**
     * @module Algolia
     *
     * @param bool|null $addLocal
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setAddLocalOrFail($addLocal)
    {
        if ($addLocal === null) {
            $this->throwNullValueException(static::ADD_LOCAL);
        }

        return $this->setAddLocal($addLocal);
    }

    /**
     * @module Algolia
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return bool
     */
    public function getAddLocalOrFail()
    {
        if ($this->addLocal === null) {
            $this->throwNullValueException(static::ADD_LOCAL);
        }

        return $this->addLocal;
    }

    /**
     * @module Algolia
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireAddLocal()
    {
        $this->assertPropertyIsSet(self::ADD_LOCAL);

        return $this;
    }

    /**
     * @module Algolia
     *
     * @param string|null $remoteUrl
     *
     * @return $this
     */
    public function setRemoteUrl($remoteUrl)
    {
        $this->remoteUrl = $remoteUrl;
        $this->modifiedProperties[self::REMOTE_URL] = true;

        return $this;
    }

    /**
     * @module Algolia
     *
     * @return string|null
     */
    public function getRemoteUrl()
    {
        return $this->remoteUrl;
    }

    /**
     * @module Algolia
     *
     * @param string|null $remoteUrl
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setRemoteUrlOrFail($remoteUrl)
    {
        if ($remoteUrl === null) {
            $this->throwNullValueException(static::REMOTE_URL);
        }

        return $this->setRemoteUrl($remoteUrl);
    }

    /**
     * @module Algolia
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getRemoteUrlOrFail()
    {
        if ($this->remoteUrl === null) {
            $this->throwNullValueException(static::REMOTE_URL);
        }

        return $this->remoteUrl;
    }

    /**
     * @module Algolia
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireRemoteUrl()
    {
        $this->assertPropertyIsSet(self::REMOTE_URL);

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
                case 'schemaFile':
                case 'addLocal':
                case 'remoteUrl':
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
                case 'schemaFile':
                case 'addLocal':
                case 'remoteUrl':
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
                case 'schemaFile':
                case 'addLocal':
                case 'remoteUrl':
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
            'schemaFile' => $this->schemaFile,
            'addLocal' => $this->addLocal,
            'remoteUrl' => $this->remoteUrl,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'schema_file' => $this->schemaFile,
            'add_local' => $this->addLocal,
            'remote_url' => $this->remoteUrl,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'schema_file' => $this->schemaFile instanceof AbstractTransfer ? $this->schemaFile->toArray(true, false) : $this->schemaFile,
            'add_local' => $this->addLocal instanceof AbstractTransfer ? $this->addLocal->toArray(true, false) : $this->addLocal,
            'remote_url' => $this->remoteUrl instanceof AbstractTransfer ? $this->remoteUrl->toArray(true, false) : $this->remoteUrl,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'schemaFile' => $this->schemaFile instanceof AbstractTransfer ? $this->schemaFile->toArray(true, true) : $this->schemaFile,
            'addLocal' => $this->addLocal instanceof AbstractTransfer ? $this->addLocal->toArray(true, true) : $this->addLocal,
            'remoteUrl' => $this->remoteUrl instanceof AbstractTransfer ? $this->remoteUrl->toArray(true, true) : $this->remoteUrl,
        ];
    }
}
