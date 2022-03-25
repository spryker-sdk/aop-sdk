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
        $commandTester = $this->tester->getConsoleTester(new CreateManifestConsole());

        // Act
        $commandTester->execute([CreateManifestConsole::MANIFEST_NAME => 'Manifest', '--' . CreateManifestConsole::OPTION_MANIFEST_PATH => 'config/example/en_US.json'], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
