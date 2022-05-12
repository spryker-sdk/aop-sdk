<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Acp\Console;

use Codeception\Test\Unit;
use SprykerSdk\Acp\Console\AbstractConsole;
use SprykerSdk\Acp\Console\AppManifestCreateConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group Acp
 * @group Communication
 * @group Console
 * @group CreateManifestConsoleTest
 */
class AppManifestCreateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Acp\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateManifestConsole(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(AppManifestCreateConsole::class);

        // Act
        $commandTester->execute(
            [
                AppManifestCreateConsole::MANIFEST_NAME => 'Manifest',
                AppManifestCreateConsole::MANIFEST_LOCALE => 'de_DE',
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
        $commandTester = $this->tester->getConsoleTester(AppManifestCreateConsole::class);
        $this->tester->haveManifestFile(); // This creates a manifest named en_US.json

        // Act
        $commandTester->execute(
            [
                AppManifestCreateConsole::MANIFEST_NAME => 'Manifest',
                AppManifestCreateConsole::MANIFEST_LOCALE => 'en_US',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('File "vfs://root/config/app/manifest/en_US.json" already exists.', trim($commandTester->getDisplay()));
    }
}
