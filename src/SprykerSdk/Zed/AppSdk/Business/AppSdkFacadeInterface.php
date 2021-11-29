<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business;

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
}
