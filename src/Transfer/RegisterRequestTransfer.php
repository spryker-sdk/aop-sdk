<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Transfer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class RegisterRequestTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const ENVIRONMENT = 'environment';

    /**
     * @var string
     */
    public const APPLICATION_IDENTIFIER = 'applicationIdentifier';

    /**
     * @var string
     */
    public const TENANT_IDENTIFIER = 'tenantIdentifier';

    /**
     * @var string
     */
    public const REPOSITORY_URL = 'repositoryUrl';

    /**
     * @var string
     */
    public const REPOSITORY_TOKEN = 'repositoryToken';

    /**
     * @var string|null
     */
    protected $environment;

    /**
     * @var string|null
     */
    protected $applicationIdentifier;

    /**
     * @var string|null
     */
    protected $tenantIdentifier;

    /**
     * @var string|null
     */
    protected $repositoryUrl;

    /**
     * @var string|null
     */
    protected $repositoryToken;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'environment' => 'environment',
        'Environment' => 'environment',
        'application_identifier' => 'applicationIdentifier',
        'applicationIdentifier' => 'applicationIdentifier',
        'ApplicationIdentifier' => 'applicationIdentifier',
        'tenant_identifier' => 'tenantIdentifier',
        'tenantIdentifier' => 'tenantIdentifier',
        'TenantIdentifier' => 'tenantIdentifier',
        'repository_url' => 'repositoryUrl',
        'repositoryUrl' => 'repositoryUrl',
        'RepositoryUrl' => 'repositoryUrl',
        'repository_token' => 'repositoryToken',
        'repositoryToken' => 'repositoryToken',
        'RepositoryToken' => 'repositoryToken',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::ENVIRONMENT => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'environment',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::APPLICATION_IDENTIFIER => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'application_identifier',
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
        self::REPOSITORY_URL => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'repository_url',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::REPOSITORY_TOKEN => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'repository_token',
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
     * @module VertexConfig
     *
     * @param string|null $environment
     *
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        $this->modifiedProperties[self::ENVIRONMENT] = true;

        return $this;
    }

    /**
     * @module VertexConfig
     *
     * @return string|null
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @module VertexConfig
     *
     * @param string|null $environment
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setEnvironmentOrFail($environment)
    {
        if ($environment === null) {
            $this->throwNullValueException(static::ENVIRONMENT);
        }

        return $this->setEnvironment($environment);
    }

    /**
     * @module VertexConfig
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getEnvironmentOrFail()
    {
        if ($this->environment === null) {
            $this->throwNullValueException(static::ENVIRONMENT);
        }

        return $this->environment;
    }

    /**
     * @module VertexConfig
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireEnvironment()
    {
        $this->assertPropertyIsSet(self::ENVIRONMENT);

        return $this;
    }

    /**
     * @module VertexConfig
     *
     * @param string|null $applicationIdentifier
     *
     * @return $this
     */
    public function setApplicationIdentifier($applicationIdentifier)
    {
        $this->applicationIdentifier = $applicationIdentifier;
        $this->modifiedProperties[self::APPLICATION_IDENTIFIER] = true;

        return $this;
    }

    /**
     * @module VertexConfig
     *
     * @return string|null
     */
    public function getApplicationIdentifier()
    {
        return $this->applicationIdentifier;
    }

    /**
     * @module VertexConfig
     *
     * @param string|null $applicationIdentifier
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setApplicationIdentifierOrFail($applicationIdentifier)
    {
        if ($applicationIdentifier === null) {
            $this->throwNullValueException(static::APPLICATION_IDENTIFIER);
        }

        return $this->setApplicationIdentifier($applicationIdentifier);
    }

    /**
     * @module VertexConfig
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getApplicationIdentifierOrFail()
    {
        if ($this->applicationIdentifier === null) {
            $this->throwNullValueException(static::APPLICATION_IDENTIFIER);
        }

        return $this->applicationIdentifier;
    }

    /**
     * @module VertexConfig
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireApplicationIdentifier()
    {
        $this->assertPropertyIsSet(self::APPLICATION_IDENTIFIER);

        return $this;
    }

    /**
     * @module VertexConfig
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
     * @module VertexConfig
     *
     * @return string|null
     */
    public function getTenantIdentifier()
    {
        return $this->tenantIdentifier;
    }

    /**
     * @module VertexConfig
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
     * @module VertexConfig
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
     * @module VertexConfig
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
     * @module VertexConfig
     *
     * @param string|null $repositoryUrl
     *
     * @return $this
     */
    public function setRepositoryUrl($repositoryUrl)
    {
        $this->repositoryUrl = $repositoryUrl;
        $this->modifiedProperties[self::REPOSITORY_URL] = true;

        return $this;
    }

    /**
     * @module VertexConfig
     *
     * @return string|null
     */
    public function getRepositoryUrl()
    {
        return $this->repositoryUrl;
    }

    /**
     * @module VertexConfig
     *
     * @param string|null $repositoryUrl
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setRepositoryUrlOrFail($repositoryUrl)
    {
        if ($repositoryUrl === null) {
            $this->throwNullValueException(static::REPOSITORY_URL);
        }

        return $this->setRepositoryUrl($repositoryUrl);
    }

    /**
     * @module VertexConfig
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getRepositoryUrlOrFail()
    {
        if ($this->repositoryUrl === null) {
            $this->throwNullValueException(static::REPOSITORY_URL);
        }

        return $this->repositoryUrl;
    }

    /**
     * @module VertexConfig
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireRepositoryUrl()
    {
        $this->assertPropertyIsSet(self::REPOSITORY_URL);

        return $this;
    }

    /**
     * @module VertexConfig
     *
     * @param string|null $repositoryToken
     *
     * @return $this
     */
    public function setRepositoryToken($repositoryToken)
    {
        $this->repositoryToken = $repositoryToken;
        $this->modifiedProperties[self::REPOSITORY_TOKEN] = true;

        return $this;
    }

    /**
     * @module VertexConfig
     *
     * @return string|null
     */
    public function getRepositoryToken()
    {
        return $this->repositoryToken;
    }

    /**
     * @module VertexConfig
     *
     * @param string|null $repositoryToken
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setRepositoryTokenOrFail($repositoryToken)
    {
        if ($repositoryToken === null) {
            $this->throwNullValueException(static::REPOSITORY_TOKEN);
        }

        return $this->setRepositoryToken($repositoryToken);
    }

    /**
     * @module VertexConfig
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getRepositoryTokenOrFail()
    {
        if ($this->repositoryToken === null) {
            $this->throwNullValueException(static::REPOSITORY_TOKEN);
        }

        return $this->repositoryToken;
    }

    /**
     * @module VertexConfig
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireRepositoryToken()
    {
        $this->assertPropertyIsSet(self::REPOSITORY_TOKEN);

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
                case 'environment':
                case 'applicationIdentifier':
                case 'tenantIdentifier':
                case 'repositoryUrl':
                case 'repositoryToken':
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
                case 'environment':
                case 'applicationIdentifier':
                case 'tenantIdentifier':
                case 'repositoryUrl':
                case 'repositoryToken':
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
                case 'environment':
                case 'applicationIdentifier':
                case 'tenantIdentifier':
                case 'repositoryUrl':
                case 'repositoryToken':
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
            'environment' => $this->environment,
            'applicationIdentifier' => $this->applicationIdentifier,
            'tenantIdentifier' => $this->tenantIdentifier,
            'repositoryUrl' => $this->repositoryUrl,
            'repositoryToken' => $this->repositoryToken,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'environment' => $this->environment,
            'application_identifier' => $this->applicationIdentifier,
            'tenant_identifier' => $this->tenantIdentifier,
            'repository_url' => $this->repositoryUrl,
            'repository_token' => $this->repositoryToken,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'environment' => $this->environment instanceof AbstractTransfer ? $this->environment->toArray(true, false) : $this->environment,
            'application_identifier' => $this->applicationIdentifier instanceof AbstractTransfer ? $this->applicationIdentifier->toArray(true, false) : $this->applicationIdentifier,
            'tenant_identifier' => $this->tenantIdentifier instanceof AbstractTransfer ? $this->tenantIdentifier->toArray(true, false) : $this->tenantIdentifier,
            'repository_url' => $this->repositoryUrl instanceof AbstractTransfer ? $this->repositoryUrl->toArray(true, false) : $this->repositoryUrl,
            'repository_token' => $this->repositoryToken instanceof AbstractTransfer ? $this->repositoryToken->toArray(true, false) : $this->repositoryToken,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'environment' => $this->environment instanceof AbstractTransfer ? $this->environment->toArray(true, true) : $this->environment,
            'applicationIdentifier' => $this->applicationIdentifier instanceof AbstractTransfer ? $this->applicationIdentifier->toArray(true, true) : $this->applicationIdentifier,
            'tenantIdentifier' => $this->tenantIdentifier instanceof AbstractTransfer ? $this->tenantIdentifier->toArray(true, true) : $this->tenantIdentifier,
            'repositoryUrl' => $this->repositoryUrl instanceof AbstractTransfer ? $this->repositoryUrl->toArray(true, true) : $this->repositoryUrl,
            'repositoryToken' => $this->repositoryToken instanceof AbstractTransfer ? $this->repositoryToken->toArray(true, true) : $this->repositoryToken,
        ];
    }
}
