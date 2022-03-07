<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Business;

use Codeception\Test\Unit;

/**
 * @group AppSdk
 * @group AppAsyncApiValidatorFacadeTest
 */
class AppAsyncApiValidatorFacadeTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\BusinesssTester
     */
    protected $tester;


    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenFilesNotFound(): void
    {
        dd("AppAsyncApiValidatorFacadeTest");
        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors());

        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('No manifest files found.', $expectedErrorMessage->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenYAMLIsInvalid(): void
    {
        // Arrange
        $this->tester->haveInvalidAsyncApiFile();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('AsyncApi file "vfs://root/config/app/manifest/en_US.json" contains invalid YAML. Error: "Syntax error".', $expectedErrorMessage->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsSuccessfulResponseWhenFilesExistsAndContainValidData(): void
    {
        // Arrange
        $this->tester->haveValidAsyncApiFile();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
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
    public function testValidateAsyncApiReturnsFailedResponseWhenFilesExistsButARequiredFieldIsMissing(): void
    {
        // Arrange
        $this->tester->haveAsyncApiFileWithMissingRequiredFields();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('Field "provider" must be present in the manifest file "en_US.json" but was not found.', $expectedErrorMessage->getMessage());
    }
}
