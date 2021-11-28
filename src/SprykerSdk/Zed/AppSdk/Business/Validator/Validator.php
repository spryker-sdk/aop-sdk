<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator;

use SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponse;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;

class Validator implements ValidatorInterface
{
    /**
     * @var array<\SprykerSdk\Zed\AppSdk\Business\Validator\ValidatorInterface>
     */
    protected $validators;

    /**
     * @param array<\SprykerSdk\Zed\AppSdk\Business\Validator\ValidatorInterface> $validators
     */
    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     * @param \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface|null $validateResponse
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validate(ValidateRequestInterface $validateRequest, ?ValidateResponseInterface $validateResponse = null): ValidateResponseInterface
    {
        $validateResponse ??= new ValidateResponse();

        foreach ($this->validators as $validator) {
            $validateResponse = $validator->validate($validateRequest, $validateResponse);
        }

        return $validateResponse;
    }
}
