<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\ManifestValidateConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AppSdk
 * @group Communication
 * @group Console
 * @group ValidateManifestConsoleTest
 */
class ValidateManifestConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateManifestReturnsSuccessCodeWhenValidationIsSuccessful(): void
    {
        // Arrange
        $this->tester->haveValidManifestFile();

        $commandTester = $this->tester->getConsoleTester(ManifestValidateConsole::class);

        // Act
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testValidateManifestReturnsErrorCodeAndPrintsErrorMessagesWhenValidationFailed(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(new ManifestValidateConsole());

        // Act
        $commandTester->execute([], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertNotEmpty($commandTester->getDisplay());
    }
}
