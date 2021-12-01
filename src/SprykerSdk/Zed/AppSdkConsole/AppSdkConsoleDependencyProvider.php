<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdkConsole;

use Spryker\Zed\Console\ConsoleDependencyProvider as SprykerConsoleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerSdk\Zed\AppSdk\Communication\Console\AddAsyncApiConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\AddAsyncApiMessageConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\ValidateConfigurationConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\ValidateConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\ValidateManifestConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\ValidateTranslationConsole;

/**
 * @method \SprykerSdk\Zed\AppSdkConsole\AppSdkConsoleConfig getConfig()
 */
class AppSdkConsoleDependencyProvider extends SprykerConsoleDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Symfony\Component\Console\Command\Command>
     */
    protected function getConsoleCommands(Container $container): array
    {
        return [
            new AddAsyncApiConsole(),
            new AddAsyncApiMessageConsole(),
            new ValidateConsole(),
            new ValidateManifestConsole(),
            new ValidateConfigurationConsole(),
            new ValidateTranslationConsole(),
            new AddAsyncApiMessageConsole(),
        ];
    }
}
