<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;

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
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validate(ValidateRequestInterface $validateRequest): ValidateResponseInterface
    {
        return $this->getFactory()->createValidator()->validate($validateRequest);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validateManifest(ValidateRequestInterface $validateRequest): ValidateResponseInterface
    {
        return $this->getFactory()->createManifestValidator()->validate($validateRequest);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validateConfiguration(ValidateRequestInterface $validateRequest): ValidateResponseInterface
    {
        return $this->getFactory()->createConfigurationValidator()->validate($validateRequest);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validateTranslation(ValidateRequestInterface $validateRequest): ValidateResponseInterface
    {
        return $this->getFactory()->createTranslationValidator()->validate($validateRequest);
    }
}
