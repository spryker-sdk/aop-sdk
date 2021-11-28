<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Business;

use Codeception\Test\Unit;

/**
 * @group AppSdk
 * @group AppConfigurationValidatorFacadeTest
 */
class AppConfigurationValidatorFacadeTest extends Unit
{
    /**
     * @var \AppSdkTest\AppSdkTester
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
        $validatorResult = $this->tester->getFacade()->validateConfiguration(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(0, $validatorResult->getErrors(), sprintf(
            'Expected that no validation errors given but there are errors. Errors: "%s"',
            implode(', ', $validatorResult->getErrors()),
        ));
        $this->assertTrue($validatorResult->isValid(), 'Expected that validation was successful but was not.');
    }

    /**
     * @return void
     */
    public function testValidateConfigurationReturnsFailedResponseWhenConfigurationFileNotFound(): void
    {
        // Act
        $validatorResult = $this->tester->getFacade()->validateConfiguration(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validatorResult->getErrors());

        $expectedErrorMessage = current($validatorResult->getErrors());
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
        $validatorResult = $this->tester->getFacade()->validateConfiguration(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validatorResult->getErrors());

        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('Configuration file "vfs://root/config/app/configuration/configuration.json" contains invalid JSON. Error: "Syntax error".', $expectedErrorMessage);
    }
}
