<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Aop\Console;

use Codeception\Test\Unit;
use SprykerSdk\Aop\Console\AbstractConsole;
use SprykerSdk\Aop\Console\AppConfigurationValidateConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AopSdk
 * @group Communication
 * @group Console
 * @group AppConfigurationValidateConsoleTest
 */
class AppConfigurationValidateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Aop\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateConfigurationReturnsSuccessCodeWhenValidationIsSuccessful(): void
    {
        // Arrange
        $this->tester->haveValidConfiguration();

        $commandTester = $this->tester->getConsoleTester(AppConfigurationValidateConsole::class);

        // Act
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testValidateConfigurationReturnsErrorCodeAndPrintsErrorMessagesWhenValidationFailed(): void
    {
        // Arrange
        $this->tester->haveInvalidConfigurationFile();

        $commandTester = $this->tester->getConsoleTester(AppConfigurationValidateConsole::class);

        // Act
        $commandTester->execute([], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertNotEmpty($commandTester->getDisplay());
    }
}
