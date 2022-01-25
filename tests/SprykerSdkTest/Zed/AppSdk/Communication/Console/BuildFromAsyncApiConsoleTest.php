<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\BuildFromAsyncApiConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AppSdk
 * @group Communication
 * @group Console
 * @group BuildFromAsyncApiConsoleTest
 */
class BuildFromAsyncApiConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildFromAsyncApiReturnsSuccessCodeWhenProcessIsDone(): void
    {
        // Arrange
        $buildFromAsyncApiConsoleMock = $this->tester->getAsyncApiBuilderConsoleMock();

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsoleMock);

        // Act
        $commandTester->execute([
            '--' . BuildFromAsyncApiConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/asyncapi/builder/asyncapi.yml'),
        ]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testBuildFromAsyncApiPrintsResultToConsoleInVerboseMode(): void
    {
        // Arrange
        $buildFromAsyncApiConsoleMock = $this->tester->getAsyncApiBuilderConsoleMock();

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsoleMock);

        // Act
        $commandTester->execute([
            '--' . BuildFromAsyncApiConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/asyncapi/builder/asyncapi.yml'),
        ], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('Added property "incomingSourceStatus" with type "string" to the "IncomingMessageTransfer" transfer object of the module "Module".', $commandTester->getDisplay());
        $this->assertStringContainsString('Added MessageHandlerPlugin for the message "IncomingMessage" to the module "Module".', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testBuildFromAsyncApiReturnsErrorCodeWhenAnErrorOccurred(): void
    {
        // Arrange
        $buildFromAsyncApiConsoleMock = $this->tester->getAsyncApiBuilderConsoleMock();

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsoleMock);

        // Act
        $commandTester->execute([
            '--' . BuildFromAsyncApiConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/asyncapi/builder/asyncapi-empty.yml'),
        ]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testBuildFromAsyncApiReturnsErrorCodeWhenAnErrorOccurredAndPrintsResultToConsoleInVerboseMode(): void
    {
        // Arrange
        $buildFromAsyncApiConsoleMock = $this->tester->getAsyncApiBuilderConsoleMock();

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsoleMock);

        // Act
        $commandTester->execute([
            '--' . BuildFromAsyncApiConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/asyncapi/builder/asyncapi-empty.yml'),
        ], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('Something went wrong. Either not channels have been found or the channels do not have messages defined.', $commandTester->getDisplay());
    }
}
