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

interface AppSdkFacadeInterface
{
    /**
     * Specification:
     * - Validates an App.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validate(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

    /**
     * Specification:
     * - Validates an App manifest files.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validateManifest(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

    /**
     * Specification:
     * - Validates an App configuration file.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validateConfiguration(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

    /**
     * Specification:
     * - Validates an App translation file.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validateTranslation(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

    /**
     * Specification:
     * - Adds an AsyncAPI file.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function addAsyncApi(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer;

    /**
     * Specification:
     * - Adds an AsyncAPI message to a given file.
     * - When the file does not exist, it will raise an error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function addAsyncApiMessage(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer;

    /**
     * Specification:
     * - Checks the readines of a project against recipes.
     * - Loads recipe(s) by recipe name(s) from another repository.
     * - Runs all defined checks.
     * - Returns a CheckReadinessResponseTransfer that contains all needed information.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return \Generated\Shared\Transfer\CheckReadinessResponseTransfer
     */
    public function checkReadiness(CheckReadinessTransfer $checkReadinessTransfer): CheckReadinessResponseTransfer;

    /**
     * Specification:
     * - Reads an AsyncAPI file and builds code that is required.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function buildFromAsyncApi(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer;

    /**
     * Specification:
     * - Reads an AsyncAPI file and validates it.
     * - Validate schema of AsyncAPI file.
     * - Check if AsyncAPI file contains message
     * - Check if AsyncAPI contains duplicate message
     * - Check if AsyncAPI contains message with missing operationId
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validateAsyncApi(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

    /**
     * Specification:
     * - Reads an OpenAPI file and validates it.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $openApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validateOpenApi(ValidateRequestTransfer $openApiRequestTransfer): ValidateResponseTransfer;

    /**
     * Specification:
     * - Adds a new Manifest file.
     * - Validates the mandatory locale.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ManifestRequestTransfer $manifestRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ManifestResponseTransfer
     */
    public function createManifest(ManifestRequestTransfer $manifestRequestTransfer): ManifestResponseTransfer;

    /**
     * Specification:
     * - Adds an OpenAPI file.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OpenApiRequestTransfer $openApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    public function createOpenApi(OpenApiRequestTransfer $openApiRequestTransfer): OpenApiResponseTransfer;

    /**
     * Specification:
     * - Adds Configuration file.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigurationRequestTransfer $appConfigurationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigurationResponseTransfer
     */
    public function appConfigurationCreate(AppConfigurationRequestTransfer $appConfigurationRequestTransfer): AppConfigurationResponseTransfer;

    /**
     * Specification:
     * - Creates a translation file.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppTranslationRequestTransfer $appTranslationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AppTranslationResponseTransfer
     */
    public function appTranslationCreate(AppTranslationRequestTransfer $appTranslationRequestTransfer): AppTranslationResponseTransfer;
}
