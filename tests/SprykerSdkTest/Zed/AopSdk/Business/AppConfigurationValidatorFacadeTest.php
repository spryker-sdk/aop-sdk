<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AopSdk\Business;

use Codeception\Test\Unit;

/**
 * @group AopSdk
 * @group AppConfigurationValidatorFacadeTest
 */
class AppConfigurationValidatorFacadeTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AopSdk\BusinesssTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateConfigurationReturnsSuccessfulResponseWhenFileExists(): void
    {
        // Arrange
        $this->tester->haveValidConfiguration();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAppConfiguration(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(0, $validateResponseTransfer->getErrors(), sprintf(
            'Expected that no validation errors given but there are errors. Errors: "%s"',
            implode(', ', $this->tester->getMessagesFromValidateResponseTransfer($validateResponseTransfer)),
        ));
        $this->assertCount(0, $validateResponseTransfer->getErrors(), 'Expected that validation was successful but was not.');
    }

    /**
     * @return void
     */
    public function testValidateConfigurationReturnsFailedResponseWhenConfigurationFileNotFound(): void
    {
        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAppConfiguration(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors());

        $expectedErrorMessage = $this->tester->getMessagesFromValidateResponseTransfer($validateResponseTransfer)[0];
        $this->assertSame('No "configuration.json" file found.', $expectedErrorMessage);
    }

    /**
     * @return void
     */
    public function testValidateConfigurationReturnsFailedResponseWhenJSONIsInvalid(): void
    {
        // Arrange
        $this->tester->haveInvalidConfigurationFile();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAppConfiguration(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors());

        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('Configuration file "vfs://root/config/app/configuration/configuration.json" contains invalid JSON. Error: "Syntax error".', $expectedErrorMessage->getMessage());
    }
}
