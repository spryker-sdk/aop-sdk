<?php

use Spryker\Shared\Kernel\KernelConstants;

// ############################################################################
// ############################## PRODUCTION CONFIGURATION ####################
// ############################################################################

$config[KernelConstants::SPRYKER_ROOT] = APPLICATION_ROOT_DIR . '/vendor/spryker';

$config[KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED] = true;
$config[KernelConstants::RESOLVED_INSTANCE_CACHE_ENABLED] = true;

$config[KernelConstants::PROJECT_NAMESPACE] = 'SprykerSdk';
$config[KernelConstants::PROJECT_NAMESPACES] = [
    'SprykerSdk',
];
$config[KernelConstants::CORE_NAMESPACES] = [
    'Spryker',
];
