<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\OpenApiCreateConsole;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AppSdk
 * @group Communication
 * @group Console
 * @group OpenApiCreateConsoleTest
 */
class OpenApiCreateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testOpenApiCreateConsole(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(OpenApiCreateConsole::class);

        // Act
        $commandTester->execute(
            [
                OpenApiCreateConsole::ARGUMENT_TITLE => 'Test File',
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testCreateOpenApiConsoleWithFileExists(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(OpenApiCreateConsole::class);
        $this->tester->haveOpenApiFile();

        // Act
        $commandTester->execute(
            [
                OpenApiCreateConsole::ARGUMENT_TITLE => 'Test File',
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('File "vfs://root/config/api/openapi/openapi.yml" already exists.', $commandTester->getDisplay());
    }
}
