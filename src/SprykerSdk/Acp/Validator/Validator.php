<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Validator;

use Transfer\ValidateRequestTransfer;
use Transfer\ValidateResponseTransfer;

class Validator implements ValidatorInterface
{
    /**
     * @var array<\SprykerSdk\Acp\Validator\ValidatorInterface>
     */
    protected array $validators;

    /**
     * @param array<\SprykerSdk\Acp\Validator\ValidatorInterface> $validators
     */
    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     * @param \Transfer\ValidateResponseTransfer|null $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
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
