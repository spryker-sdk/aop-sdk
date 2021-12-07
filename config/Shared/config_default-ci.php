<?php

use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\Kernel\KernelConstants;

// ############################################################################
// ############################## TESTING IN CI ###############################
// ############################################################################

// ----------------------------------------------------------------------------
// ------------------------------ CODEBASE ------------------------------------
// ----------------------------------------------------------------------------

// >>> Dev tools
$config[KernelConstants::ENABLE_CONTAINER_OVERRIDING] = true;

// >>> ErrorHandler
$config[ErrorHandlerConstants::DISPLAY_ERRORS] = true;
