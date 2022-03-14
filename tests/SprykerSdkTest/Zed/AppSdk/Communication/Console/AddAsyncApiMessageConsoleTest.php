<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Communication\Console;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AsyncApiBuilderTestTransfer;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\AddAsyncApiMessageConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AppSdk
 * @group Communication
 * @group Console
 * @group AddAsyncApiMessageConsoleTest
 */
class AddAsyncApiMessageConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddMessageReturnsSuccessCodeWhenMessageWasAdded(): void
    {
        // Arrange
        $this->tester->haveAsyncApiFile();
        $commandTester = $this->tester->getConsoleTester(AddAsyncApiMessageConsole::class);

        // Act
        $commandTester->execute(
            [
                AddAsyncApiMessageConsole::ARGUMENT_CHANNEL_NAME => 'test/channel',
                '--' . AddAsyncApiMessageConsole::OPTION_FROM_TRANSFER_CLASS => AsyncApiBuilderTestTransfer::class,
                '--' . AddAsyncApiMessageConsole::OPTION_OPERATION_ID => 'operationId',
            ],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testAddMessageReturnsErrorCodeAndPrintsErrorMessagesWhenMessageCouldNotBeAddedWhenAsyncApiDoesNotExists(): void
    {
        $commandTester = $this->tester->getConsoleTester(AddAsyncApiMessageConsole::class, false);

        // Act
        $commandTester->execute(
            [
                AddAsyncApiMessageConsole::ARGUMENT_CHANNEL_NAME => 'test/channel',
                '--' . AddAsyncApiMessageConsole::OPTION_FROM_TRANSFER_CLASS => AsyncApiBuilderTestTransfer::class,
                '--' . AddAsyncApiMessageConsole::OPTION_OPERATION_ID => 'operationId',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertNotEmpty($commandTester->getDisplay());
    }
}
