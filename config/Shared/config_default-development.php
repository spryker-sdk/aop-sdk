<?php

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\Kernel\KernelConstants;

// ----------------------------------------------------------------------------
// ------------------------------ CODEBASE ------------------------------------
// ----------------------------------------------------------------------------

$config[KernelConstants::STORE_PREFIX] = 'DEV';

// >>> Debug
$config[ApplicationConstants::ENABLE_APPLICATION_DEBUG]= true;

// >>> ErrorHandler
$config[ErrorHandlerConstants::DISPLAY_ERRORS] = true;

