<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Transfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class RegisterRequestTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const PRIVATE = 'private';

    /**
     * @var string
     */
    public const APP_IDENTIFIER = 'appIdentifier';

    /**
     * @var string
     */
    public const TENANT_IDENTIFIER = 'tenantIdentifier';

    /**
     * @var string
     */
    public const REGISTRY_URL = 'registryUrl';

    /**
     * @var string
     */
    public const AUTHORIZATION_TOKEN = 'authorizationToken';

    /**
     * @var string
     */
    public const BASE_URL = 'baseUrl';

    /**
     * @var string
     */
    public const MANIFEST_PATH = 'manifestPath';

    /**
     * @var string
     */
    public const CONFIGURATION_FILE = 'configurationFile';

    /**
     * @var string
     */
    public const TRANSLATION_FILE = 'translationFile';

    /**
     * @var string
     */
    public const ACP_API_FILE = 'acpApiFile';

    /**
     * @var bool|null
     */
    protected $private;

    /**
     * @var string|null
     */
    protected $appIdentifier;

    /**
     * @var string|null
     */
    protected $tenantIdentifier;

    /**
     * @var string|null
     */
    protected $registryUrl;

    /**
     * @var string|null
     */
    protected $authorizationToken;

    /**
     * @var string|null
     */
    protected $baseUrl;

    /**
     * @var string|null
     */
    protected $manifestPath;

    /**
     * @var string|null
     */
    protected $configurationFile;

    /**
     * @var string|null
     */
    protected $translationFile;

    /**
     * @var string|null
     */
    protected $acpApiFile;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'private' => 'private',
        'Private' => 'private',
        'app_identifier' => 'appIdentifier',
        'appIdentifier' => 'appIdentifier',
        'AppIdentifier' => 'appIdentifier',
        'tenant_identifier' => 'tenantIdentifier',
        'tenantIdentifier' => 'tenantIdentifier',
        'TenantIdentifier' => 'tenantIdentifier',
        'registry_url' => 'registryUrl',
        'registryUrl' => 'registryUrl',
        'RegistryUrl' => 'registryUrl',
        'authorization_token' => 'authorizationToken',
        'authorizationToken' => 'authorizationToken',
        'AuthorizationToken' => 'authorizationToken',
        'base_url' => 'baseUrl',
        'baseUrl' => 'baseUrl',
        'BaseUrl' => 'baseUrl',
        'manifest_path' => 'manifestPath',
        'manifestPath' => 'manifestPath',
        'ManifestPath' => 'manifestPath',
        'configuration_file' => 'configurationFile',
        'configurationFile' => 'configurationFile',
        'ConfigurationFile' => 'configurationFile',
        'translation_file' => 'translationFile',
        'translationFile' => 'translationFile',
        'TranslationFile' => 'translationFile',
        'acp_api_file' => 'acpApiFile',
        'acpApiFile' => 'acpApiFile',
        'AcpApiFile' => 'acpApiFile',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::PRIVATE => [
            'type' => 'bool',
            'type_shim' => null,
            'name_underscore' => 'private',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::APP_IDENTIFIER => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'app_identifier',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::TENANT_IDENTIFIER => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'tenant_identifier',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::REGISTRY_URL => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'registry_url',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::AUTHORIZATION_TOKEN => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'authorization_token',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::BASE_URL => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'base_url',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::MANIFEST_PATH => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'manifest_path',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::CONFIGURATION_FILE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'configuration_file',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::TRANSLATION_FILE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'translation_file',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::ACP_API_FILE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'acp_api_file',
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
     * @module Test
     *
     * @param bool|null $private
     *
     * @return $this
     */
    public function setPrivate($private)
    {
        $this->private = $private;
        $this->modifiedProperties[self::PRIVATE] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return bool|null
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @module Test
     *
     * @param bool|null $private
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setPrivateOrFail($private)
    {
        if ($private === null) {
            $this->throwNullValueException(static::PRIVATE);
        }

        return $this->setPrivate($private);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return bool
     */
    public function getPrivateOrFail()
    {
        if ($this->private === null) {
            $this->throwNullValueException(static::PRIVATE);
        }

        return $this->private;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePrivate()
    {
        $this->assertPropertyIsSet(self::PRIVATE);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|null $appIdentifier
     *
     * @return $this
     */
    public function setAppIdentifier($appIdentifier)
    {
        $this->appIdentifier = $appIdentifier;
        $this->modifiedProperties[self::APP_IDENTIFIER] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getAppIdentifier()
    {
        return $this->appIdentifier;
    }

    /**
     * @module Test
     *
     * @param string|null $appIdentifier
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setAppIdentifierOrFail($appIdentifier)
    {
        if ($appIdentifier === null) {
            $this->throwNullValueException(static::APP_IDENTIFIER);
        }

        return $this->setAppIdentifier($appIdentifier);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getAppIdentifierOrFail()
    {
        if ($this->appIdentifier === null) {
            $this->throwNullValueException(static::APP_IDENTIFIER);
        }

        return $this->appIdentifier;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireAppIdentifier()
    {
        $this->assertPropertyIsSet(self::APP_IDENTIFIER);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|null $tenantIdentifier
     *
     * @return $this
     */
    public function setTenantIdentifier($tenantIdentifier)
    {
        $this->tenantIdentifier = $tenantIdentifier;
        $this->modifiedProperties[self::TENANT_IDENTIFIER] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getTenantIdentifier()
    {
        return $this->tenantIdentifier;
    }

    /**
     * @module Test
     *
     * @param string|null $tenantIdentifier
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setTenantIdentifierOrFail($tenantIdentifier)
    {
        if ($tenantIdentifier === null) {
            $this->throwNullValueException(static::TENANT_IDENTIFIER);
        }

        return $this->setTenantIdentifier($tenantIdentifier);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getTenantIdentifierOrFail()
    {
        if ($this->tenantIdentifier === null) {
            $this->throwNullValueException(static::TENANT_IDENTIFIER);
        }

        return $this->tenantIdentifier;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTenantIdentifier()
    {
        $this->assertPropertyIsSet(self::TENANT_IDENTIFIER);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|null $registryUrl
     *
     * @return $this
     */
    public function setRegistryUrl($registryUrl)
    {
        $this->registryUrl = $registryUrl;
        $this->modifiedProperties[self::REGISTRY_URL] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getRegistryUrl()
    {
        return $this->registryUrl;
    }

    /**
     * @module Test
     *
     * @param string|null $registryUrl
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setRegistryUrlOrFail($registryUrl)
    {
        if ($registryUrl === null) {
            $this->throwNullValueException(static::REGISTRY_URL);
        }

        return $this->setRegistryUrl($registryUrl);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getRegistryUrlOrFail()
    {
        if ($this->registryUrl === null) {
            $this->throwNullValueException(static::REGISTRY_URL);
        }

        return $this->registryUrl;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireRegistryUrl()
    {
        $this->assertPropertyIsSet(self::REGISTRY_URL);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|null $authorizationToken
     *
     * @return $this
     */
    public function setAuthorizationToken($authorizationToken)
    {
        $this->authorizationToken = $authorizationToken;
        $this->modifiedProperties[self::AUTHORIZATION_TOKEN] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getAuthorizationToken()
    {
        return $this->authorizationToken;
    }

    /**
     * @module Test
     *
     * @param string|null $authorizationToken
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setAuthorizationTokenOrFail($authorizationToken)
    {
        if ($authorizationToken === null) {
            $this->throwNullValueException(static::AUTHORIZATION_TOKEN);
        }

        return $this->setAuthorizationToken($authorizationToken);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getAuthorizationTokenOrFail()
    {
        if ($this->authorizationToken === null) {
            $this->throwNullValueException(static::AUTHORIZATION_TOKEN);
        }

        return $this->authorizationToken;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireAuthorizationToken()
    {
        $this->assertPropertyIsSet(self::AUTHORIZATION_TOKEN);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|null $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->modifiedProperties[self::BASE_URL] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @module Test
     *
     * @param string|null $baseUrl
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setBaseUrlOrFail($baseUrl)
    {
        if ($baseUrl === null) {
            $this->throwNullValueException(static::BASE_URL);
        }

        return $this->setBaseUrl($baseUrl);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getBaseUrlOrFail()
    {
        if ($this->baseUrl === null) {
            $this->throwNullValueException(static::BASE_URL);
        }

        return $this->baseUrl;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireBaseUrl()
    {
        $this->assertPropertyIsSet(self::BASE_URL);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|null $manifestPath
     *
     * @return $this
     */
    public function setManifestPath($manifestPath)
    {
        $this->manifestPath = $manifestPath;
        $this->modifiedProperties[self::MANIFEST_PATH] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getManifestPath()
    {
        return $this->manifestPath;
    }

    /**
     * @module Test
     *
     * @param string|null $manifestPath
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setManifestPathOrFail($manifestPath)
    {
        if ($manifestPath === null) {
            $this->throwNullValueException(static::MANIFEST_PATH);
        }

        return $this->setManifestPath($manifestPath);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getManifestPathOrFail()
    {
        if ($this->manifestPath === null) {
            $this->throwNullValueException(static::MANIFEST_PATH);
        }

        return $this->manifestPath;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireManifestPath()
    {
        $this->assertPropertyIsSet(self::MANIFEST_PATH);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|null $configurationFile
     *
     * @return $this
     */
    public function setConfigurationFile($configurationFile)
    {
        $this->configurationFile = $configurationFile;
        $this->modifiedProperties[self::CONFIGURATION_FILE] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getConfigurationFile()
    {
        return $this->configurationFile;
    }

    /**
     * @module Test
     *
     * @param string|null $configurationFile
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setConfigurationFileOrFail($configurationFile)
    {
        if ($configurationFile === null) {
            $this->throwNullValueException(static::CONFIGURATION_FILE);
        }

        return $this->setConfigurationFile($configurationFile);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getConfigurationFileOrFail()
    {
        if ($this->configurationFile === null) {
            $this->throwNullValueException(static::CONFIGURATION_FILE);
        }

        return $this->configurationFile;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireConfigurationFile()
    {
        $this->assertPropertyIsSet(self::CONFIGURATION_FILE);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|null $translationFile
     *
     * @return $this
     */
    public function setTranslationFile($translationFile)
    {
        $this->translationFile = $translationFile;
        $this->modifiedProperties[self::TRANSLATION_FILE] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getTranslationFile()
    {
        return $this->translationFile;
    }

    /**
     * @module Test
     *
     * @param string|null $translationFile
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setTranslationFileOrFail($translationFile)
    {
        if ($translationFile === null) {
            $this->throwNullValueException(static::TRANSLATION_FILE);
        }

        return $this->setTranslationFile($translationFile);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getTranslationFileOrFail()
    {
        if ($this->translationFile === null) {
            $this->throwNullValueException(static::TRANSLATION_FILE);
        }

        return $this->translationFile;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTranslationFile()
    {
        $this->assertPropertyIsSet(self::TRANSLATION_FILE);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|null $acpApiFile
     *
     * @return $this
     */
    public function setAcpApiFile($acpApiFile)
    {
        $this->acpApiFile = $acpApiFile;
        $this->modifiedProperties[self::ACP_API_FILE] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getAcpApiFile()
    {
        return $this->acpApiFile;
    }

    /**
     * @module Test
     *
     * @param string|null $acpApiFile
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setAcpApiFileOrFail($acpApiFile)
    {
        if ($acpApiFile === null) {
            $this->throwNullValueException(static::ACP_API_FILE);
        }

        return $this->setAcpApiFile($acpApiFile);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getAcpApiFileOrFail()
    {
        if ($this->acpApiFile === null) {
            $this->throwNullValueException(static::ACP_API_FILE);
        }

        return $this->acpApiFile;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireAcpApiFile()
    {
        $this->assertPropertyIsSet(self::ACP_API_FILE);

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
                case 'private':
                case 'appIdentifier':
                case 'tenantIdentifier':
                case 'registryUrl':
                case 'authorizationToken':
                case 'baseUrl':
                case 'manifestPath':
                case 'configurationFile':
                case 'translationFile':
                case 'acpApiFile':
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
                case 'private':
                case 'appIdentifier':
                case 'tenantIdentifier':
                case 'registryUrl':
                case 'authorizationToken':
                case 'baseUrl':
                case 'manifestPath':
                case 'configurationFile':
                case 'translationFile':
                case 'acpApiFile':
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
                case 'private':
                case 'appIdentifier':
                case 'tenantIdentifier':
                case 'registryUrl':
                case 'authorizationToken':
                case 'baseUrl':
                case 'manifestPath':
                case 'configurationFile':
                case 'translationFile':
                case 'acpApiFile':
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
            'private' => $this->private,
            'appIdentifier' => $this->appIdentifier,
            'tenantIdentifier' => $this->tenantIdentifier,
            'registryUrl' => $this->registryUrl,
            'authorizationToken' => $this->authorizationToken,
            'baseUrl' => $this->baseUrl,
            'manifestPath' => $this->manifestPath,
            'configurationFile' => $this->configurationFile,
            'translationFile' => $this->translationFile,
            'acpApiFile' => $this->acpApiFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'private' => $this->private,
            'app_identifier' => $this->appIdentifier,
            'tenant_identifier' => $this->tenantIdentifier,
            'registry_url' => $this->registryUrl,
            'authorization_token' => $this->authorizationToken,
            'base_url' => $this->baseUrl,
            'manifest_path' => $this->manifestPath,
            'configuration_file' => $this->configurationFile,
            'translation_file' => $this->translationFile,
            'acp_api_file' => $this->acpApiFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'private' => $this->private instanceof AbstractTransfer ? $this->private->toArray(true, false) : $this->private,
            'app_identifier' => $this->appIdentifier instanceof AbstractTransfer ? $this->appIdentifier->toArray(true, false) : $this->appIdentifier,
            'tenant_identifier' => $this->tenantIdentifier instanceof AbstractTransfer ? $this->tenantIdentifier->toArray(true, false) : $this->tenantIdentifier,
            'registry_url' => $this->registryUrl instanceof AbstractTransfer ? $this->registryUrl->toArray(true, false) : $this->registryUrl,
            'authorization_token' => $this->authorizationToken instanceof AbstractTransfer ? $this->authorizationToken->toArray(true, false) : $this->authorizationToken,
            'base_url' => $this->baseUrl instanceof AbstractTransfer ? $this->baseUrl->toArray(true, false) : $this->baseUrl,
            'manifest_path' => $this->manifestPath instanceof AbstractTransfer ? $this->manifestPath->toArray(true, false) : $this->manifestPath,
            'configuration_file' => $this->configurationFile instanceof AbstractTransfer ? $this->configurationFile->toArray(true, false) : $this->configurationFile,
            'translation_file' => $this->translationFile instanceof AbstractTransfer ? $this->translationFile->toArray(true, false) : $this->translationFile,
            'acp_api_file' => $this->acpApiFile instanceof AbstractTransfer ? $this->acpApiFile->toArray(true, false) : $this->acpApiFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'private' => $this->private instanceof AbstractTransfer ? $this->private->toArray(true, true) : $this->private,
            'appIdentifier' => $this->appIdentifier instanceof AbstractTransfer ? $this->appIdentifier->toArray(true, true) : $this->appIdentifier,
            'tenantIdentifier' => $this->tenantIdentifier instanceof AbstractTransfer ? $this->tenantIdentifier->toArray(true, true) : $this->tenantIdentifier,
            'registryUrl' => $this->registryUrl instanceof AbstractTransfer ? $this->registryUrl->toArray(true, true) : $this->registryUrl,
            'authorizationToken' => $this->authorizationToken instanceof AbstractTransfer ? $this->authorizationToken->toArray(true, true) : $this->authorizationToken,
            'baseUrl' => $this->baseUrl instanceof AbstractTransfer ? $this->baseUrl->toArray(true, true) : $this->baseUrl,
            'manifestPath' => $this->manifestPath instanceof AbstractTransfer ? $this->manifestPath->toArray(true, true) : $this->manifestPath,
            'configurationFile' => $this->configurationFile instanceof AbstractTransfer ? $this->configurationFile->toArray(true, true) : $this->configurationFile,
            'translationFile' => $this->translationFile instanceof AbstractTransfer ? $this->translationFile->toArray(true, true) : $this->translationFile,
            'acpApiFile' => $this->acpApiFile instanceof AbstractTransfer ? $this->acpApiFile->toArray(true, true) : $this->acpApiFile,
        ];
    }
}
