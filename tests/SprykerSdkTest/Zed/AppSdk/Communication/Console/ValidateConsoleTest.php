<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\ValidateConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group Command
 * @group ValidateCommandTest
 */
class ValidateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateReturnsSuccessCodeWhenAllValidationsAreSuccessful(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithManifestAndConfiguration();

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
