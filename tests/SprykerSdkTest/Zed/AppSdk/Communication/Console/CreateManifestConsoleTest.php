<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\CreateManifestConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AppSdk
 * @group Communication
 * @group Console
 * @group CreateManifestConsoleTest
 */
class CreateManifestConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateManifestConsole(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(CreateManifestConsole::class);

        // Act
        $commandTester->execute(
            [
                CreateManifestConsole::MANIFEST_NAME => 'Manifest',
                CreateManifestConsole::MANIFEST_LOCALE => 'de_DE',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testCreateManifestConsoleWithFileExists(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(CreateManifestConsole::class);
        $this->tester->haveManifestFile(); // This creates a manifest named en_US.json

        // Act
        $commandTester->execute(
            [
                CreateManifestConsole::MANIFEST_NAME => 'Manifest',
                CreateManifestConsole::MANIFEST_LOCALE => 'en_US',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('File "vfs://root/config/app/manifest/en_US.json" already exists.', trim($commandTester->getDisplay()));
    }
}
