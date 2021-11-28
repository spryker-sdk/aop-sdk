<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator;

use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;

interface FileValidatorInterface
{
    /**
     * @param array $data
     * @param string $fileName
     * @param \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface $validateResponse
     * @param array|null $context
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validate(array $data, string $fileName, ValidateResponseInterface $validateResponse, ?array $context = null): ValidateResponseInterface;
}
