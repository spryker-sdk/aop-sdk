<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker;

use Generated\Shared\Transfer\CheckConfigurationTransfer;
use Generated\Shared\Transfer\CheckerMessageTransfer;
use Generated\Shared\Transfer\RecipeTransfer;
use ReflectionMethod;

class PluginsChecker implements CheckerInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'plugins';
    }

    /**
     * @param \Generated\Shared\Transfer\RecipeTransfer $recipeTransfer
     * @param \Generated\Shared\Transfer\CheckConfigurationTransfer $checkConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\RecipeTransfer
     */
    public function check(RecipeTransfer $recipeTransfer, CheckConfigurationTransfer $checkConfigurationTransfer): RecipeTransfer
    {
        $checkerConfiguration = $checkConfigurationTransfer->getCheckConfiguration();

        foreach ($checkerConfiguration as $dependencyProviderClassName => $configuration) {
            $dependencyProviderClassName = str_replace('{projectNamespace}', $checkConfigurationTransfer->getProjectNamespaceOrFail(), $dependencyProviderClassName);

            if (!class_exists($dependencyProviderClassName)) {
                $checkerMessageTransfer = new CheckerMessageTransfer();
                $checkerMessageTransfer->setType('error')
                    ->setMessage(sprintf('The class "%s" was not found please create one with a method "%s()".', $dependencyProviderClassName, $configuration['method']));

                $recipeTransfer->addCheckerMessage($checkerMessageTransfer);

                return $recipeTransfer;
            }

            $dependencyProvider = new $dependencyProviderClassName();

            if (!method_exists($dependencyProvider, $configuration['method'])) {
                $checkerMessageTransfer = new CheckerMessageTransfer();
                $checkerMessageTransfer->setType('error')
                    ->setMessage(sprintf('The method "%s::%s()" was not found please add it.', $dependencyProviderClassName, $configuration['method']));

                $recipeTransfer->addCheckerMessage($checkerMessageTransfer);

                return $recipeTransfer;
            }

            $reflectionMethod = new ReflectionMethod($dependencyProvider, $configuration['method']);
            $reflectionMethod->setAccessible(true);

            $plugins = $reflectionMethod->invoke($dependencyProvider);

            foreach ($configuration['plugins'] as $expectedPluginClassName) {
                if (!$this->checkIfPluginExistsInPluginStack($plugins, $expectedPluginClassName)) {
                    $checkerMessageTransfer = new CheckerMessageTransfer();
                    $checkerMessageTransfer->setType('error')
                        ->setMessage(sprintf('The plugin "%s" does not exists in "%s::%s()".', $expectedPluginClassName, $dependencyProviderClassName, $configuration['method']));

                    $recipeTransfer->addCheckerMessage($checkerMessageTransfer);
                }
            }
        }

        return $recipeTransfer;
    }

    /**
     * @param array $pluginStack
     * @param string $expectedPluginClassName
     *
     * @return bool
     */
    protected function checkIfPluginExistsInPluginStack(array $pluginStack, string $expectedPluginClassName): bool
    {
        foreach ($pluginStack as $plugin) {
            if ($plugin instanceof $expectedPluginClassName) {
                return true;
            }
        }

        return false;
    }
}
