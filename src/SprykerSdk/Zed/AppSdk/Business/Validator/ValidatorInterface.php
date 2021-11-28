<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator;

use SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;

interface ValidatorInterface
{
    /**
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     * @param \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface|null $validateResponse
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validate(ValidateRequestInterface $validateRequest, ?ValidateResponseInterface $validateResponse = null): ValidateResponseInterface;
}
