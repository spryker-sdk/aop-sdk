<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business;

use Generated\Shared\Transfer\AppConfigurationRequestTransfer;
use Generated\Shared\Transfer\AppConfigurationResponseTransfer;
use Generated\Shared\Transfer\AppTranslationRequestTransfer;
use Generated\Shared\Transfer\AppTranslationResponseTransfer;
use Generated\Shared\Transfer\AsyncApiRequestTransfer;
use Generated\Shared\Transfer\AsyncApiResponseTransfer;
use Generated\Shared\Transfer\CheckReadinessResponseTransfer;
use Generated\Shared\Transfer\CheckReadinessTransfer;
use Generated\Shared\Transfer\ManifestRequestTransfer;
use Generated\Shared\Transfer\ManifestResponseTransfer;
use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiResponseTransfer;
use Generated\Shared\Transfer\ValidateRequestTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerSdk\Zed\AppSdk\Business\AppSdkBusinessFactory getFactory()
 */
class AppSdkFacade extends AbstractFacade implements AppSdkFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
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
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
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
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
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
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
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
     * @param \Generated\Shared\Transfer\ManifestRequestTransfer $manifestRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ManifestResponseTransfer
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
     * @param \Generated\Shared\Transfer\AppTranslationRequestTransfer $appTranslationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AppTranslationResponseTransfer
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
     * @param \Generated\Shared\Transfer\AppConfigurationRequestTransfer $appConfigurationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigurationResponseTransfer
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
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function addAsyncApi(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer
    {
        return $this->getFactory()->createAsyncApiBuilder()->addAsyncApi($asyncApiRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function addAsyncApiMessage(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer
    {
        return $this->getFactory()->createAsyncApiBuilder()->addAsyncApiMessage($asyncApiRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return \Generated\Shared\Transfer\CheckReadinessResponseTransfer
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
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function buildFromAsyncApi(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer
    {
        return $this->getFactory()->createAsyncApiCodeBuilder()->build($asyncApiRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validateAsyncApi(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer
    {
        return $this->getFactory()->createAsyncApiValidator()->validate($validateRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validateOpenApi(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer
    {
        return $this->getFactory()->createOpenApiValidator()->validate($validateRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OpenApiRequestTransfer $openApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    public function createOpenApi(OpenApiRequestTransfer $openApiRequestTransfer): OpenApiResponseTransfer
    {
        return $this->getFactory()->createOpenApiBuilder()->createOpenApi($openApiRequestTransfer);
    }
}
