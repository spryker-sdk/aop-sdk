<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\AppConfigurationCreateConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AppSdk
 * @group Communication
 * @group Console
 * @group AppConfigurationCreateConsoleTest
 */
class AppConfigurationCreateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateConfigurationReturnsSuccessCodeWhenValidationIsSuccessful(): void
    {
        // Arrange
        // $this->tester->haveValidConfiguration();

        $commandTester = $this->tester->getConsoleTester(AppConfigurationCreateConsole::class);

        //Property Name
        $commandTester->setInputs(['Test']);

        //IsRequired
        // $commandTester->setInputs(['Yes']);

        //setWidget
        $commandTester->setInputs([2]);

        // Act
        $commandTester->execute([]);

        dd($commandTester);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testValidateConfigurationReturnsErrorCodeAndPrintsErrorMessagesWhenValidationFailed(): void
    {
        $commandTester = $this->tester->getConsoleTester(new AppConfigurationCreateConsole());

        // Act
        $commandTester->execute([], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertNotEmpty($commandTester->getDisplay());
    }
}
