<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp;

use Transfer\AppConfigurationRequestTransfer;
use Transfer\AppConfigurationResponseTransfer;
use Transfer\AppTranslationRequestTransfer;
use Transfer\AppTranslationResponseTransfer;
use Transfer\CheckReadinessResponseTransfer;
use Transfer\CheckReadinessTransfer;
use Transfer\CreateDefaultEndpointsRequestTransfer;
use Transfer\CreateDefaultEndpointsResponseTransfer;
use Transfer\ManifestCollectionTransfer;
use Transfer\ManifestCriteriaTransfer;
use Transfer\ManifestRequestTransfer;
use Transfer\ManifestResponseTransfer;
use Transfer\ValidateRequestTransfer;
use Transfer\ValidateResponseTransfer;

class AcpFacade implements AcpFacadeInterface
{
    /**
     * @var \SprykerSdk\Acp\AcpFactory|null
     */
    protected ?AcpFactory $factory = null;

    /**
     * @param \SprykerSdk\Acp\AcpFactory $factory
     *
     * @return void
     */
    public function setFactory(AcpFactory $factory): void
    {
        $this->factory = $factory;
    }

    /**
     * @return \SprykerSdk\Acp\AcpFactory
     */
    protected function getFactory(): AcpFactory
    {
        if (!$this->factory) {
            $this->factory = new AcpFactory();
        }

        return $this->factory;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validate(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer
    {
        return $this->getFactory()->createValidator()->validate($validateRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validateAppManifest(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer
    {
        return $this->getFactory()->createAppManifestValidator()->validate($validateRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validateAppTranslation(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer
    {
        return $this->getFactory()->createAppTranslationValidator()->validate($validateRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validateAppConfiguration(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer
    {
        return $this->getFactory()->createAppConfigurationValidator()->validate($validateRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Transfer\ManifestRequestTransfer $manifestRequestTransfer
     *
     * @return \Transfer\ManifestResponseTransfer
     */
    public function createAppManifest(ManifestRequestTransfer $manifestRequestTransfer): ManifestResponseTransfer
    {
        return $this->getFactory()->createAppManifestBuilder()->createManifest($manifestRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Transfer\AppTranslationRequestTransfer $appTranslationRequestTransfer
     *
     * @return \Transfer\AppTranslationResponseTransfer
     */
    public function createAppTranslation(AppTranslationRequestTransfer $appTranslationRequestTransfer): AppTranslationResponseTransfer
    {
        return $this->getFactory()->createAppTranslationBuilder()->createTranslation($appTranslationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Transfer\AppConfigurationRequestTransfer $appConfigurationRequestTransfer
     *
     * @return \Transfer\AppConfigurationResponseTransfer
     */
    public function createAppConfiguration(AppConfigurationRequestTransfer $appConfigurationRequestTransfer): AppConfigurationResponseTransfer
    {
        return $this->getFactory()->createAppConfigurationBuilder()->createConfiguration($appConfigurationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return \Transfer\CheckReadinessResponseTransfer
     */
    public function checkReadiness(CheckReadinessTransfer $checkReadinessTransfer): CheckReadinessResponseTransfer
    {
        return $this->getFactory()->createReadinessChecker()->checkReadiness($checkReadinessTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return \Transfer\ManifestCollectionTransfer
     */
    public function getManifestCollection(ManifestCriteriaTransfer $manifestCriteriaTransfer): ManifestCollectionTransfer
    {
        return $this->getFactory()->createAppManifestReader()->getManifestCollection($manifestCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     *
     * @return array
     */
    public function getExistingKeysToTranslate(ManifestCollectionTransfer $manifestCollectionTransfer): array
    {
        return $this->getFactory()->createTranslateKeyMapper()->mapManifestCollectionToTranslateKeys($manifestCollectionTransfer);
    }

    /**
     * @param \Transfer\CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
     *
     * @return \Transfer\CreateDefaultEndpointsResponseTransfer
     */
    public function createDefaultEndpoints(
        CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
    ): CreateDefaultEndpointsResponseTransfer {
        return new CreateDefaultEndpointsResponseTransfer();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $configurationFilePath
     * @param string $openapiFilePath
     *
     * @return bool
     */
    public function convertConfigurationToSchema(string $configurationFilePath, string $openapiFilePath): bool
    {
        return $this->getFactory()->createSchemaGenerator()->convertConfigurationToSchema($configurationFilePath, $openapiFilePath);
    }
}
