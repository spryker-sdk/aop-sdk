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
use Transfer\ManifestCollectionTransfer;
use Transfer\ManifestCriteriaTransfer;
use Transfer\ManifestRequestTransfer;
use Transfer\ManifestResponseTransfer;
use Transfer\RegisterRequestTransfer;
use Transfer\RegisterResponseTransfer;
use Transfer\ValidateRequestTransfer;
use Transfer\ValidateResponseTransfer;

interface AcpFacadeInterface
{
    /**
     * Specification:
     * - Registers an App in ACP.
     *
     * @api
     *
     * @param \Transfer\RegisterRequestTransfer $registerRequestTransfer
     *
     * @return \Transfer\RegisterResponseTransfer
     */
    public function registerApp(RegisterRequestTransfer $registerRequestTransfer): RegisterResponseTransfer;

    /**
     * Specification:
     * - Validates an App.
     *
     * @api
     *
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validate(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

    /**
     * Specification:
     * - Validates an App manifest files.
     *
     * @api
     *
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validateAppManifest(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

    /**
     * Specification:
     * - Validates an App configuration file.
     *
     * @api
     *
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validateAppConfiguration(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

    /**
     * Specification:
     * - Validates an App translation file.
     *
     * @api
     *
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validateAppTranslation(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;

    /**
     * Specification:
     * - Adds a new Manifest file.
     * - Validates the mandatory locale.
     *
     * @api
     *
     * @param \Transfer\ManifestRequestTransfer $manifestRequestTransfer
     *
     * @return \Transfer\ManifestResponseTransfer
     */
    public function createAppManifest(ManifestRequestTransfer $manifestRequestTransfer): ManifestResponseTransfer;

    /**
     * Specification:
     * - Adds a Configuration file.
     *
     * @api
     *
     * @param \Transfer\AppConfigurationRequestTransfer $appConfigurationRequestTransfer
     *
     * @return \Transfer\AppConfigurationResponseTransfer
     */
    public function createAppConfiguration(AppConfigurationRequestTransfer $appConfigurationRequestTransfer): AppConfigurationResponseTransfer;

    /**
     * Specification:
     * - Creates a translation file.
     *
     * @api
     *
     * @param \Transfer\AppTranslationRequestTransfer $appTranslationRequestTransfer
     *
     * @return \Transfer\AppTranslationResponseTransfer
     */
    public function createAppTranslation(AppTranslationRequestTransfer $appTranslationRequestTransfer): AppTranslationResponseTransfer;

    /**
     * Specification:
     * - Checks the readiness of a project against recipes.
     * - Loads recipe(s) by recipe name(s) from another repository.
     * - Runs all defined checks.
     * - Returns a CheckReadinessResponseTransfer that contains all needed information.
     *
     * @api
     *
     * @param \Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return \Transfer\CheckReadinessResponseTransfer
     */
    public function checkReadiness(CheckReadinessTransfer $checkReadinessTransfer): CheckReadinessResponseTransfer;

    /**
     * Specification:
     * - Finds existing manifests according to the criteria.
     * - Extends the collection by a manifest configuration.
     * - Extends the collection by a manifest translation.
     *
     * @api
     *
     * @param \Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return \Transfer\ManifestCollectionTransfer
     */
    public function getManifestCollection(ManifestCriteriaTransfer $manifestCriteriaTransfer): ManifestCollectionTransfer;

    /**
     * Specification:
     * - Finds object keys from configuration to translate.
     *
     * @api
     *
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     *
     * @return array
     */
    public function getExistingKeysToTranslate(ManifestCollectionTransfer $manifestCollectionTransfer): array;

    /**
     * Specification:
     * - Validates channel names.
     *
     * @api
     *
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validateChannelNames(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;
}
