<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Aop\Console;

use Codeception\Test\Unit;
use SprykerSdk\Aop\Console\AbstractConsole;
use SprykerSdk\Aop\Console\CheckReadinessConsole;
use SprykerSdk\Aop\Exception\CheckerNotFoundException;
use SprykerSdk\Aop\Exception\RecipeNotFoundException;
use SprykerSdkTest\Aop\CommunicationTester;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AopSdk
 * @group Communication
 * @group Console
 * @group CheckReadinessConsoleTest
 */
class CheckReadinessConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Aop\CommunicationTester
     */
    protected CommunicationTester $tester;

    /**
     * @return void
     */
    public function testReturnsSuccessfulResponseWhenNoErrorWasFound(): void
    {
        // Arrange
        putenv('FOO_BAR=exist');
        include_once codecept_data_dir('Fixtures/BazBatDependencyProvider.php');

        $checkReadinessConsoleCommand = $this->tester->createCheckReadinessConsoleCommand();
        $commandTester = $this->tester->getConsoleTester($checkReadinessConsoleCommand);

        // Act
        $commandTester->execute(
            [
                CheckReadinessConsole::ARGUMENT_CHECK_RECIPE => [
                    'valid-composer',
                    'valid-env',
                    'valid-plugins',
                ],
                '--' . CheckReadinessConsole::OPTION_PROJECT_NAMESPACE => 'BazBat',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );
        $this->assertNotEmpty($commandTester->getDisplay(), $commandTester->getDisplay());
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());

        // Cleanup
        putenv('FOO_BAR');
    }

    /**
     * @return void
     */
    public function testReturnsThrowsExceptionWhenRecipeByNameWasNotFound(): void
    {
        // Arrange
        $checkReadinessConsoleCommand = $this->tester->createCheckReadinessConsoleCommand();
        $commandTester = $this->tester->getConsoleTester($checkReadinessConsoleCommand);

        // Expect
        $this->expectException(RecipeNotFoundException::class);

        // Act
        $commandTester->execute(
            [
                CheckReadinessConsole::ARGUMENT_CHECK_RECIPE => 'not-existent-recipe',
            ],
        );
    }

    /**
     * @return void
     */
    public function testReturnsThrowsExceptionWhenCheckerByNameWasNotFound(): void
    {
        // Arrange
        $checkReadinessConsoleCommand = $this->tester->createCheckReadinessConsoleCommand();
        $commandTester = $this->tester->getConsoleTester($checkReadinessConsoleCommand);

        // Expect
        $this->expectException(CheckerNotFoundException::class);

        // Act
        $commandTester->execute(
            [
                CheckReadinessConsole::ARGUMENT_CHECK_RECIPE => 'undefined-checker',
            ],
        );
    }

    /**
     * @return void
     */
    public function testReturnsRecipeWithErrorsWhenComposerPackageIsNotInstalled(): void
    {
        // Arrange
        $checkReadinessConsoleCommand = $this->tester->createCheckReadinessConsoleCommand();
        $commandTester = $this->tester->getConsoleTester($checkReadinessConsoleCommand);

        // Act
        $commandTester->execute(
            [
                CheckReadinessConsole::ARGUMENT_CHECK_RECIPE => 'missing-composer-package',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('Required package "missing/requirement" was not found. Please install it with "composer install missing/requirement".', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testReturnsRecipeWithErrorsWhenComposerPackageDoesNotSatisfyExpectedVersion(): void
    {
        // Arrange
        $checkReadinessConsoleCommand = $this->tester->createCheckReadinessConsoleCommand();
        $commandTester = $this->tester->getConsoleTester($checkReadinessConsoleCommand);

        // Act
        $commandTester->execute(
            [
                CheckReadinessConsole::ARGUMENT_CHECK_RECIPE => 'composer-package-does-not-satisfy-expected-version',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('Required package "symfony/console" does not satisfy the expected version "^100.0.0". Please update your composer dependencies.', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testReturnsRecipeWithErrorsWhenExpectedDependencyProviderDoesNotExists(): void
    {
        // Arrange
        $checkReadinessConsoleCommand = $this->tester->createCheckReadinessConsoleCommand();
        $commandTester = $this->tester->getConsoleTester($checkReadinessConsoleCommand);

        // Act
        $commandTester->execute(
            [
                CheckReadinessConsole::ARGUMENT_CHECK_RECIPE => 'missing-plugins',
                '--' . CheckReadinessConsole::OPTION_PROJECT_NAMESPACE => 'FooBar',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('The class "\FooBar\Zed\FooBar\FooBarDependencyProvider" was not found please create one with a method "getFooBarPlugins()".', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testReturnsRecipeWithErrorsWhenExpectedPluginMethodDoesNotExistsInTheDependencyProvider(): void
    {
        // Arrange
        $checkReadinessConsoleCommand = $this->tester->createCheckReadinessConsoleCommand();
        $commandTester = $this->tester->getConsoleTester($checkReadinessConsoleCommand);

        include_once codecept_data_dir('Fixtures/FooBarDependencyProvider.php');

        // Act
        $commandTester->execute(
            [
                CheckReadinessConsole::ARGUMENT_CHECK_RECIPE => 'missing-dependency-provider-method',
                '--' . CheckReadinessConsole::OPTION_PROJECT_NAMESPACE => 'FooBar',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('The method "\FooBar\Zed\FooBar\FooBarDependencyProvider::getCatFacePlugins()" was not found please add it.', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testReturnsRecipeWithErrorsWhenExpectedPluginsAreNotAddedToTheDependencyProvider(): void
    {
        // Arrange
        $checkReadinessConsoleCommand = $this->tester->createCheckReadinessConsoleCommand();
        $commandTester = $this->tester->getConsoleTester($checkReadinessConsoleCommand);

        include_once codecept_data_dir('Fixtures/FooBarDependencyProvider.php');

        // Act
        $commandTester->execute(
            [
                CheckReadinessConsole::ARGUMENT_CHECK_RECIPE => 'missing-plugins',
                '--' . CheckReadinessConsole::OPTION_PROJECT_NAMESPACE => 'FooBar',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('The plugin "\Spryker\Zed\FooBar\Communication\Plugins\BazBat\FooBarPlugin" does not exists in "\FooBar\Zed\FooBar\FooBarDependencyProvider::getFooBarPlugins()".', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testReturnsRecipeWithErrorsWhenEnvVarIsNotSet(): void
    {
        // Arrange
        $checkReadinessConsoleCommand = $this->tester->createCheckReadinessConsoleCommand();
        $commandTester = $this->tester->getConsoleTester($checkReadinessConsoleCommand);

        // Act
        $commandTester->execute(
            [
                CheckReadinessConsole::ARGUMENT_CHECK_RECIPE => 'missing-env',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('The env variable "FOO_BAR" does not exists, please add it.', $commandTester->getDisplay());
    }
}
