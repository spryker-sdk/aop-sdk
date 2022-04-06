<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AopSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AopSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\AsyncApiCreateConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AopSdk
 * @group Communication
 * @group Console
 * @group AsyncApiCreateConsoleTest
 */
class AsyncApiCreateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AopSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAsyncnApiCreateConsole(): void
    {
        $commandTester = $this->tester->getConsoleTester(new AsyncApiCreateConsole());

        // Act
        $commandTester->execute([AsyncApiCreateConsole::ARGUMENT_TITLE => 'Test File', '--' . AsyncApiCreateConsole::OPTION_ASYNC_API_FILE => 'config/api/asyncapi.yml'], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
