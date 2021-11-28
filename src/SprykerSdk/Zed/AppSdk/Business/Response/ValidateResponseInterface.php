<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Response;

interface ValidateResponseInterface
{
    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @param string $error
     *
     * @return void
     */
    public function addError(string $error): void;

    /**
     * @return array
     */
    public function getErrors(): array;
}
