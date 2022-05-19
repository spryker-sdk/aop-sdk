<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class ManifestCollectionTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const MANIFESTS = 'manifests';

    /**
     * @var string
     */
    public const CONFIGURATION = 'configuration';

    /**
     * @var string
     */
    public const TRANSLATION = 'translation';

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\ManifestTransfer[]
     */
    protected $manifests;

    /**
     * @var \Generated\Shared\Transfer\ManifestConfigurationTransfer|null
     */
    protected $configuration;

    /**
     * @var \Generated\Shared\Transfer\ManifestTranslationTransfer|null
     */
    protected $translation;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'manifests' => 'manifests',
        'Manifests' => 'manifests',
        'configuration' => 'configuration',
        'Configuration' => 'configuration',
        'translation' => 'translation',
        'Translation' => 'translation',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::MANIFESTS => [
            'type' => 'Generated\Shared\Transfer\ManifestTransfer',
            'type_shim' => null,
            'name_underscore' => 'manifests',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::CONFIGURATION => [
            'type' => 'Generated\Shared\Transfer\ManifestConfigurationTransfer',
            'type_shim' => null,
            'name_underscore' => 'configuration',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::TRANSLATION => [
            'type' => 'Generated\Shared\Transfer\ManifestTranslationTransfer',
            'type_shim' => null,
            'name_underscore' => 'translation',
            'is_collection' => false,
            'is_transfer' => true,
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
     * @param \ArrayObject|\Generated\Shared\Transfer\ManifestTransfer[] $manifests
     *
     * @return $this
     */
    public function setManifests(ArrayObject $manifests)
    {
        $this->manifests = $manifests;
        $this->modifiedProperties[self::MANIFESTS] = true;

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ManifestTransfer[]
     */
    public function getManifests()
    {
        return $this->manifests;
    }

    /**
     * @module AcpSdk
     *
     * @param \Generated\Shared\Transfer\ManifestTransfer $manifest
     *
     * @return $this
     */
    public function addManifest(ManifestTransfer $manifest)
    {
        $this->manifests[] = $manifest;
        $this->modifiedProperties[self::MANIFESTS] = true;

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireManifests()
    {
        $this->assertCollectionPropertyIsSet(self::MANIFESTS);

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @param \Generated\Shared\Transfer\ManifestConfigurationTransfer|null $configuration
     *
     * @return $this
     */
    public function setConfiguration(ManifestConfigurationTransfer $configuration = null)
    {
        $this->configuration = $configuration;
        $this->modifiedProperties[self::CONFIGURATION] = true;

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @return \Generated\Shared\Transfer\ManifestConfigurationTransfer|null
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @module AcpSdk
     *
     * @param \Generated\Shared\Transfer\ManifestConfigurationTransfer $configuration
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setConfigurationOrFail(ManifestConfigurationTransfer $configuration)
    {
        return $this->setConfiguration($configuration);
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return \Generated\Shared\Transfer\ManifestConfigurationTransfer
     */
    public function getConfigurationOrFail()
    {
        if ($this->configuration === null) {
            $this->throwNullValueException(static::CONFIGURATION);
        }

        return $this->configuration;
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireConfiguration()
    {
        $this->assertPropertyIsSet(self::CONFIGURATION);

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @param \Generated\Shared\Transfer\ManifestTranslationTransfer|null $translation
     *
     * @return $this
     */
    public function setTranslation(ManifestTranslationTransfer $translation = null)
    {
        $this->translation = $translation;
        $this->modifiedProperties[self::TRANSLATION] = true;

        return $this;
    }

    /**
     * @module AcpSdk
     *
     * @return \Generated\Shared\Transfer\ManifestTranslationTransfer|null
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @module AcpSdk
     *
     * @param \Generated\Shared\Transfer\ManifestTranslationTransfer $translation
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setTranslationOrFail(ManifestTranslationTransfer $translation)
    {
        return $this->setTranslation($translation);
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return \Generated\Shared\Transfer\ManifestTranslationTransfer
     */
    public function getTranslationOrFail()
    {
        if ($this->translation === null) {
            $this->throwNullValueException(static::TRANSLATION);
        }

        return $this->translation;
    }

    /**
     * @module AcpSdk
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTranslation()
    {
        $this->assertPropertyIsSet(self::TRANSLATION);

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
                case 'configuration':
                case 'translation':
                    if (is_array($value)) {
                        $type = $this->transferMetadata[$normalizedPropertyName]['type'];
                        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $value */
                        $value = (new $type())->fromArray($value, $ignoreMissingProperty);
                    }

                    if ($value !== null && $this->isPropertyStrict($normalizedPropertyName)) {
                        $this->assertInstanceOfTransfer($normalizedPropertyName, $value);
                    }
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'manifests':
                    $elementType = $this->transferMetadata[$normalizedPropertyName]['type'];
                    $this->$normalizedPropertyName = $this->processArrayObject($elementType, $value, $ignoreMissingProperty);
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
                case 'configuration':
                case 'translation':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, true) : $value;

                    break;
                case 'manifests':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, true) : $value;

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
                case 'configuration':
                case 'translation':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, false) : $value;

                    break;
                case 'manifests':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, false) : $value;

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
        $this->manifests = $this->manifests ?: new ArrayObject();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveCamelCased(): array
    {
        return [
            'configuration' => $this->configuration,
            'translation' => $this->translation,
            'manifests' => $this->manifests,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'configuration' => $this->configuration,
            'translation' => $this->translation,
            'manifests' => $this->manifests,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'configuration' => $this->configuration instanceof AbstractTransfer ? $this->configuration->toArray(true, false) : $this->configuration,
            'translation' => $this->translation instanceof AbstractTransfer ? $this->translation->toArray(true, false) : $this->translation,
            'manifests' => $this->manifests instanceof AbstractTransfer ? $this->manifests->toArray(true, false) : $this->addValuesToCollection($this->manifests, true, false),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'configuration' => $this->configuration instanceof AbstractTransfer ? $this->configuration->toArray(true, true) : $this->configuration,
            'translation' => $this->translation instanceof AbstractTransfer ? $this->translation->toArray(true, true) : $this->translation,
            'manifests' => $this->manifests instanceof AbstractTransfer ? $this->manifests->toArray(true, true) : $this->addValuesToCollection($this->manifests, true, true),
        ];
    }
}
