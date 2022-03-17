<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdkConsole;

use Spryker\Zed\Console\ConsoleDependencyProvider as SprykerConsoleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerSdk\Zed\AppSdk\Communication\Console\AppConfigurationValidateConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\AppManifestValidateConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\AppTranslationValidateConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\AsyncApiCreateConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\AsyncApiMessageAddConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\AsyncApiValidateConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\BuildCodeFromAsyncApiConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\CheckReadinessConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\CreateManifestConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\ValidateConsole;

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
            new AsyncApiCreateConsole(),
            new AsyncApiMessageAddConsole(),
            new AsyncApiValidateConsole(),
            new ValidateConsole(),
            new AppManifestValidateConsole(),
            new AppConfigurationValidateConsole(),
            new AppTranslationValidateConsole(),
            new CheckReadinessConsole(),
            new BuildCodeFromAsyncApiConsole(),
            new CreateManifestConsole(),
        ];
    }
}
