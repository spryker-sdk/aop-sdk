<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Aop\Console;

use Codeception\Test\Unit;
use SprykerSdk\Aop\Console\AbstractConsole;
use SprykerSdk\Aop\Console\ValidateConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AopSdk
 * @group Communication
 * @group Console
 * @group ValidateConsoleTest
 */
class ValidateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Aop\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateReturnsSuccessCodeWhenAllValidationsAreSuccessful(): void
    {
        // Arrange
        $this->tester->haveValidConfigurations();

        $commandTester = $this->tester->getConsoleTester(ValidateConsole::class);

        // Act
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testValidateTranslationReturnsErrorCodeAndPrintsErrorMessagesWhenValidationFailed(): void
    {
        $commandTester = $this->tester->getConsoleTester(ValidateConsole::class, false);

        // Act
        $commandTester->execute([], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertNotEmpty($commandTester->getDisplay());
    }
}
