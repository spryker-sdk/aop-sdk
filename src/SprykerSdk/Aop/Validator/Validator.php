<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Aop\Validator;

use Generated\Shared\Transfer\ValidateRequestTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;

class Validator implements ValidatorInterface
{
    /**
     * @var array<\SprykerSdk\Aop\Validator\ValidatorInterface>
     */
    protected $validators;

    /**
     * @param array<\SprykerSdk\Aop\Validator\ValidatorInterface> $validators
     */
    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer|null $validateResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validate(
        ValidateRequestTransfer $validateRequestTransfer,
        ?ValidateResponseTransfer $validateResponseTransfer = null
    ): ValidateResponseTransfer {
        $validateResponseTransfer ??= new ValidateResponseTransfer();

        foreach ($this->validators as $validator) {
            $validateResponseTransfer = $validator->validate($validateRequestTransfer, $validateResponseTransfer);
        }

        return $validateResponseTransfer;
    }
}
