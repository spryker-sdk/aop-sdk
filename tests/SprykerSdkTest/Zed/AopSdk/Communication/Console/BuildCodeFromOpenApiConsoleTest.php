<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AopSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AopSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\BuildCodeFromOpenApiConsole;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AopSdk
 * @group Communication
 * @group Console
 * @group BuildCodeFromOpenApiConsoleTest
 */
class BuildCodeFromOpenApiConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AopSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildCodeFromOpenApiConsoleReturnsSuccessCodeWhenProcessIsDone(): void
    {
        $commandTester = $this->tester->getConsoleTester(new BuildCodeFromOpenApiConsole());

        // Act
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testBuildCodeFromOpenApiConsoleReturnsErrorCodeWhenAnErrorOccurred(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(new BuildCodeFromOpenApiConsole());

        // Act
        $commandTester->execute([
            '--' . BuildCodeFromOpenApiConsole::OPTION_OPEN_API_FILE => codecept_data_dir('api/openapi/invalid/invalid_openapi.yml'),
            '--' . BuildCodeFromOpenApiConsole::APPLICATION_TYPE => 'backend',
            '--' . BuildCodeFromOpenApiConsole::OPTION_ORGANIZATION => 'Spryker',
        ]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
    }
}
