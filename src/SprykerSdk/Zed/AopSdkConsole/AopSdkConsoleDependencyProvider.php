<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AopSdkConsole;

use Spryker\Zed\Console\ConsoleDependencyProvider as SprykerConsoleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerSdk\Zed\AopSdk\Communication\Console\AppConfigurationCreateConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\AppConfigurationValidateConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\AppManifestCreateConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\AppManifestValidateConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\AppTranslationCreateConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\AppTranslationValidateConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\AsyncApiCreateConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\AsyncApiMessageAddConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\AsyncApiValidateConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\BuildCodeFromAsyncApiConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\CheckReadinessConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\OpenApiCreateConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\OpenApiValidateConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\ValidateConsole;

/**
 * @method \SprykerSdk\Zed\AopSdkConsole\AopSdkConsoleConfig getConfig()
 */
class AopSdkConsoleDependencyProvider extends SprykerConsoleDependencyProvider
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
            new AppConfigurationCreateConsole(),
            new AppConfigurationValidateConsole(),
            new AppTranslationValidateConsole(),
            new CheckReadinessConsole(),
            new BuildCodeFromAsyncApiConsole(),
            new AppManifestCreateConsole(),
            new OpenApiCreateConsole(),
            new AppTranslationCreateConsole(),
            new OpenApiValidateConsole(),
        ];
    }
}
