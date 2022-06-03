<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Acp\Console;

use Codeception\Test\Unit;
use SprykerSdk\Acp\Console\AbstractConsole;
use SprykerSdk\Acp\Console\AppTranslationValidateConsole;
use SprykerSdkTest\Acp\Tester;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Acp
 * @group Console
 * @group AppTranslationValidateConsoleTest
 */
class AppTranslationValidateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Acp\Tester
     */
    protected Tester $tester;

    /**
     * @return void
     */
    public function testValidateTranslationReturnsSuccessCodeWhenValidationIsSuccessful(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithManifestAndConfiguration();

        $commandTester = $this->tester->getConsoleTester(AppTranslationValidateConsole::class);

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
        $commandTester = $this->tester->getConsoleTester(new AppTranslationValidateConsole());

        // Act
        $commandTester->execute([], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertNotEmpty($commandTester->getDisplay());
    }
}
