<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\ValidateTranslationConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group Command
 * @group ValidateTranslationCommandTest
 */
class ValidateTranslationConsoleTest extends Unit
{
    /**
     * @var \AppSdkTest\CommandTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateTranslationReturnsSuccessCodeWhenValidationIsSuccessful(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithManifestAndConfiguration();

        $command = new ValidateTranslationConsole();
        $commandTester = $this->tester->getConsoleTester(ValidateTranslationConsole::class);

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
        $commandTester = $this->tester->getConsoleTester(new ValidateTranslationConsole());

        // Act
        $commandTester->execute([], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertNotEmpty($commandTester->getDisplay());
    }
}
