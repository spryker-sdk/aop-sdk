<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Registrator;

use Transfer\RegisterRequestTransfer;
use Transfer\RegisterResponseTransfer;

interface RegistratorInterface
{
    /**
     * @param \Transfer\RegisterRequestTransfer $registerRequestTransfer
     *
     * @return \Transfer\RegisterResponseTransfer
     */
    public function register(RegisterRequestTransfer $registerRequestTransfer): RegisterResponseTransfer;
}
