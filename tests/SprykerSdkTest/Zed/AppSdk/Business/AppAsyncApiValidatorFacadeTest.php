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
        $this->assertSame('Field "operationId" must be present in the asyncapi file "asyncapi.yml" but was not found.', $expectedErrorMessage->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenFilesExistsButHaveDuplicateMessage(): void
    {
        // Arrange
        $this->tester->haveAsyncApiFileHaveDuplicateMessage();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('Async API file contains duplicate messages.', $expectedErrorMessage->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenFilesNotFound(): void
    {
        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors());

        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('No asyncapi files found.', $expectedErrorMessage->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenSchemaIsInvalid(): void
    {
        // Arrange
        $this->tester->haveInvalidAsyncApiFile();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('AsyncApi file "vfs://root/config/api/asyncapi/builder/asyncapi-empty.yml" contains invalid schema. Error: "Syntax error".', $expectedErrorMessage->getMessage());
    }
}
