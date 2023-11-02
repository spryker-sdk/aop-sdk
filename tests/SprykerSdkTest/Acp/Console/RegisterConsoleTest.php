<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Acp\Console;

use Codeception\Test\Unit;
use SprykerSdk\Acp\Console\RegisterConsole;
use SprykerSdkTest\Acp\Tester;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group Acp
 * @group Console
 * @group RegisterConsoleTest
 */
class RegisterConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Acp\Tester
     */
    protected Tester $tester;

    /**
     * @return void
     */
    public function testRegisterAppReturnsSuccessfulResponseWhenAppWasRegisteredInAcp(): void
    {
        // Arrange
        $this->tester->haveValidConfigurations();
        $registerConsole = $this->tester->getRegisterConsoleWithAtrsSuccessResponse();

        // Act
        $commandTester = $this->tester->getConsoleTester($registerConsole);
        $commandTester->execute([
            '--appIdentifier' => '1234-5678-9012-3456',
            '--baseUrl' => 'http://www.example.com/',
            '--authorizationToken' => '1234-5678-9012-3456',
        ], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(RegisterConsole::CODE_SUCCESS, $commandTester->getStatusCode(), $commandTester->getDisplay());
        $this->assertSame("App successfully registered or updated in ACP.\n", $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testRegisterAppPrintsErrorWhenAppIdentifierIsNotPassed(): void
    {
        // Arrange
        $this->tester->haveValidConfigurations();
        $registerConsole = $this->tester->getRegisterConsoleWithAtrsSuccessResponse();

        // Act
        $commandTester = $this->tester->getConsoleTester($registerConsole);
        $commandTester->execute([
            '--baseUrl' => 'http://www.example.com/',
            '--authorizationToken' => '1234-5678-9012-3456',
        ]);

        // Assert
        $this->assertSame(RegisterConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('You need to pass an AppIdentifier with the option `--appIdentifier`.', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testRegisterAppPrintsErrorWhenBaseUrlIsNotPassed(): void
    {
        // Arrange
        $this->tester->haveValidConfigurations();
        $registerConsole = $this->tester->getRegisterConsoleWithAtrsSuccessResponse();

        // Act
        $commandTester = $this->tester->getConsoleTester($registerConsole);
        $commandTester->execute([
            '--appIdentifier' => '1234-5678-9012-3456',
            '--authorizationToken' => '1234-5678-9012-3456',
        ]);

        // Assert
        $this->assertSame(RegisterConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('You need to pass a base URL to your App with the option `--baseUrl`.', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testRegisterAppPrintsErrorWhenAuthorizationTokenIsNotPassed(): void
    {
        // Arrange
        $this->tester->haveValidConfigurations();
        $registerConsole = $this->tester->getRegisterConsoleWithAtrsSuccessResponse();

        // Act
        $commandTester = $this->tester->getConsoleTester($registerConsole);
        $commandTester->execute([
            '--appIdentifier' => '1234-5678-9012-3456',
            '--baseUrl' => 'http://www.example.com/',
        ]);

        // Assert
        $this->assertSame(RegisterConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('You need to pass an authorization token with the option `--authorizationToken`.', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testRegisterAppPrintsErrorWhenAppShouldBeMadePrivateButTenantIdentifierIsNotPassed(): void
    {
        // Arrange
        $this->tester->haveValidConfigurations();
        $registerConsole = $this->tester->getRegisterConsoleWithAtrsSuccessResponse();

        // Act
        $commandTester = $this->tester->getConsoleTester($registerConsole);
        $commandTester->execute([
            '--private' => true,
            '--appIdentifier' => '1234-5678-9012-3456',
            '--baseUrl' => 'http://www.example.com/',
            '--authorizationToken' => '1234-5678-9012-3456',
        ]);

        // Assert
        $this->assertSame(RegisterConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('You need to pass a Tenant Identifier with the option `--tenantIdentifier` when you want this App to be only visible to you.', $commandTester->getDisplay());
    }
}
