<?php

namespace SprykerSdk\Acp\Registrator;

use Transfer\RegisterRequestTransfer;
use Transfer\RegisterResponseTransfer;

interface RegistratorInterface
{
    /**
     * @param  RegisterRequestTransfer $registerRequestTransfer
     * @return RegisterResponseTransfer
     */
    public function register(RegisterRequestTransfer $registerRequestTransfer): RegisterResponseTransfer;
}
