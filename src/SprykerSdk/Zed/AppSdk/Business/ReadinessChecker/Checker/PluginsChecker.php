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

        foreach ($checkerConfiguration as $dependencyProviderConfiguration) {
            $recipeTransfer = $this->checkDependencyProviderConfiguration($recipeTransfer, $dependencyProviderConfiguration, $checkConfigurationTransfer->getProjectNamespaceOrFail());
        }

        return $recipeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RecipeTransfer $recipeTransfer
     * @param array $dependencyProviderConfiguration
     * @param string $projectNamespace
     *
     * @return \Generated\Shared\Transfer\RecipeTransfer
     */
    protected function checkDependencyProviderConfiguration(
        RecipeTransfer $recipeTransfer,
        array $dependencyProviderConfiguration,
        string $projectNamespace
    ): RecipeTransfer {
        foreach ($dependencyProviderConfiguration as $dependencyProviderClassName => $configuration) {
            $dependencyProviderClassName = str_replace('{projectNamespace}', $projectNamespace, $dependencyProviderClassName);

            if (!class_exists($dependencyProviderClassName)) {
                return $this->addClassDoesNotExistsCheckMessage($recipeTransfer, $dependencyProviderClassName, $configuration['method']);
            }

            $dependencyProvider = new $dependencyProviderClassName();

            if (!method_exists($dependencyProvider, $configuration['method'])) {
                return $this->addClassMethodDoesNotExistsCheckMessage($recipeTransfer, $dependencyProviderClassName, $configuration['method']);
            }

            $recipeTransfer = $this->checkExpectedPluginsExists($recipeTransfer, $dependencyProviderClassName, $configuration);
        }

        return $recipeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RecipeTransfer $recipeTransfer
     * @param string $className
     * @param string $methodName
     *
     * @return \Generated\Shared\Transfer\RecipeTransfer
     */
    protected function addClassDoesNotExistsCheckMessage(RecipeTransfer $recipeTransfer, string $className, string $methodName): RecipeTransfer
    {
        $checkerMessageTransfer = new CheckerMessageTransfer();
        $checkerMessageTransfer->setType('error')
            ->setMessage(sprintf('The class "%s" was not found please create one with a method "%s()".', $className, $methodName));

        $recipeTransfer->addCheckerMessage($checkerMessageTransfer);

        return $recipeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RecipeTransfer $recipeTransfer
     * @param string $className
     * @param string $methodName
     *
     * @return \Generated\Shared\Transfer\RecipeTransfer
     */
    protected function addClassMethodDoesNotExistsCheckMessage(RecipeTransfer $recipeTransfer, string $className, string $methodName): RecipeTransfer
    {
        $checkerMessageTransfer = new CheckerMessageTransfer();
        $checkerMessageTransfer->setType('error')
            ->setMessage(sprintf('The method "%s::%s()" was not found please add it.', $className, $methodName));

        $recipeTransfer->addCheckerMessage($checkerMessageTransfer);

        return $recipeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RecipeTransfer $recipeTransfer
     * @param string $className
     * @param array $configuration
     *
     * @return \Generated\Shared\Transfer\RecipeTransfer
     */
    protected function checkExpectedPluginsExists(RecipeTransfer $recipeTransfer, string $className, array $configuration): RecipeTransfer
    {
        $dependencyProvider = new $className();
        $reflectionMethod = new ReflectionMethod($className, $configuration['method']);
        $reflectionMethod->setAccessible(true);

        $plugins = $reflectionMethod->invoke($dependencyProvider);

        foreach ($configuration['plugins'] as $expectedPluginClassName) {
            if (!$this->checkIfPluginExistsInPluginStack($plugins, $expectedPluginClassName)) {
                $checkerMessageTransfer = new CheckerMessageTransfer();
                $checkerMessageTransfer->setType('error')
                    ->setMessage(sprintf('The plugin "%s" does not exists in "%s::%s()".', $expectedPluginClassName, $className, $configuration['method']));

                $recipeTransfer->addCheckerMessage($checkerMessageTransfer);
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
