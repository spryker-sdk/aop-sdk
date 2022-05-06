<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AopSdk\Business;

use Generated\Shared\Transfer\AppConfigurationRequestTransfer;
use Generated\Shared\Transfer\AppConfigurationResponseTransfer;
use Generated\Shared\Transfer\AppTranslationRequestTransfer;
use Generated\Shared\Transfer\AppTranslationResponseTransfer;
use Generated\Shared\Transfer\CheckReadinessResponseTransfer;
use Generated\Shared\Transfer\CheckReadinessTransfer;
use Generated\Shared\Transfer\ManifestRequestTransfer;
use Generated\Shared\Transfer\ManifestResponseTransfer;
use Generated\Shared\Transfer\ValidateRequestTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;

interface AopSdkFacadeInterface
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
    public function validateAppManifest(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

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
    public function validateAppConfiguration(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

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
    public function validateAppTranslation(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

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
    public function createAppManifest(ManifestRequestTransfer $manifestRequestTransfer): ManifestResponseTransfer;

    /**
     * Specification:
     * - Adds a Configuration file.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigurationRequestTransfer $appConfigurationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigurationResponseTransfer
     */
    public function createAppConfiguration(AppConfigurationRequestTransfer $appConfigurationRequestTransfer): AppConfigurationResponseTransfer;

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
    public function createAppTranslation(AppTranslationRequestTransfer $appTranslationRequestTransfer): AppTranslationResponseTransfer;

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
}
