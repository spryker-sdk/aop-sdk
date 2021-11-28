<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Business;

use Codeception\Test\Unit;

/**
 * @group AppSdk
 * @group AppManifestValidatorFacadeTest
 */
class AppManifestValidatorFacadeTest extends Unit
{
    /**
     * @var \AppSdkTest\AppSdkTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateManifestReturnsSuccessfulResponseWhenFilesExistsAndContainValidData(): void
    {
        // Arrange
        $this->tester->haveValidManifestFile();

        // Act
        $validatorResult = $this->tester->getFacade()->validateManifest(
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
    public function testValidateManifestReturnsFailedResponseWhenFilesExistsButARequiredFieldIsMissing(): void
    {
        // Arrange
        $this->tester->haveManifestFileWithMissingRequiredFields();

        // Act
        $validatorResult = $this->tester->getFacade()->validateManifest(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('Field "provider" must be present in the manifest file "en_US.json" but was not found.', $expectedErrorMessage);
    }

    /**
     * @return void
     */
    public function testValidateManifestReturnsFailedResponseWhenRequiredFieldIsMissingInPageBlock(): void
    {
        // Arrange
        $this->tester->haveManifestFileWithMissingRequiredFieldsInPageBlock();

        // Act
        $validatorResult = $this->tester->getFacade()->validateManifest(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('Page block field "type" in page "Overview" must be present in the manifest file "en_US.json" but was not found.', $expectedErrorMessage);
    }

    /**
     * @return void
     */
    public function testValidateManifestReturnsFailedResponseWhenPageBlockTypeFieldHasInvalidType(): void
    {
        // Arrange
        $this->tester->haveManifestFileWithInvalidPageBlockType();

        // Act
        $validatorResult = $this->tester->getFacade()->validateManifest(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('Page block type "invalid" not allowed in page "Overview" in the manifest file "en_US.json".', $expectedErrorMessage);
    }

    /**
     * @return void
     */
    public function testValidateManifestReturnsFailedResponseWhenFilesNotFound(): void
    {
        // Act
        $validatorResult = $this->tester->getFacade()->validateManifest(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validatorResult->getErrors());

        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('No manifest files found.', $expectedErrorMessage);
    }

    /**
     * @return void
     */
    public function testValidateManifestReturnsFailedResponseWhenJSONIsInvalid(): void
    {
        // Arrange
        $this->tester->haveInvalidManifestFile();

        // Act
        $validatorResult = $this->tester->getFacade()->validateManifest(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('Manifest file "vfs://root/config/app/manifest/en_US.json" contains invalid JSON. Error: "Syntax error".', $expectedErrorMessage);
    }
}
