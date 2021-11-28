<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business;

use SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;

interface AppSdkFacadeInterface
{
    /**
     * Specification:
     * - Validates an App.
     *
     * @api
     *
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validate(ValidateRequestInterface $validateRequest): ValidateResponseInterface;

    /**
     * Specification:
     * - Validates an App manifest files.
     *
     * @api
     *
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validateManifest(ValidateRequestInterface $validateRequest): ValidateResponseInterface;

    /**
     * Specification:
     * - Validates an App configuration file.
     *
     * @api
     *
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validateConfiguration(ValidateRequestInterface $validateRequest): ValidateResponseInterface;

    /**
     * Specification:
     * - Validates an App translation file.
     *
     * @api
     *
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validateTranslation(ValidateRequestInterface $validateRequest): ValidateResponseInterface;
}
