<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Business;

use Codeception\Test\Unit;

/**
 * @group AppSdk
 * @group AppTranslationValidatorFacadeTest
 */
class AppTranslationValidatorFacadeTest extends Unit
{
    /**
     * @var \AppSdkTest\AppSdkTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateTranslationReturnsSuccessfulResponseWhenFileExists(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithManifestAndConfiguration();

        // Act
        $validatorResult = $this->tester->getFacade()->validateTranslation(
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
    public function testValidateTranslationReturnsFailedResponseWhenNoManifestFileExist(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithoutManifest();

        // Act
        $validatorResult = $this->tester->getFacade()->validateTranslation(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validatorResult->getErrors());

        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('Can not validate the Translation file "vfs://root/config/app/translation/translation.json" without existing manifest file(s).', $expectedErrorMessage);
    }

    /**
     * @return void
     */
    public function testValidateTranslationReturnsFailedResponseWhenNoConfigurationFileExist(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithManifestAndWithoutConfiguration();

        // Act
        $validatorResult = $this->tester->getFacade()->validateTranslation(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validatorResult->getErrors());

        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('Can not validate the Translation file "vfs://root/config/app/translation/translation.json" without existing "configuration.json" file.', $expectedErrorMessage);
    }

    /**
     * @return void
     */
    public function testValidateTranslationReturnsFailedResponseWhenTranslationValueForLocaleIsMissing(): void
    {
        // Arrange
        $this->tester->haveMissingTranslationValueTranslationFile();

        // Act
        $validatorResult = $this->tester->getFacade()->validateTranslation(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validatorResult->getErrors());

        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('Missing translation for key "foo.bar" and locale "en_US".', $expectedErrorMessage);
    }

    /**
     * @return void
     */
    public function testValidateTranslationReturnsFailedResponseWhenTranslationFileNotFound(): void
    {
        // Act
        $validatorResult = $this->tester->getFacade()->validateTranslation(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validatorResult->getErrors());

        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('No "translation.json" file found.', $expectedErrorMessage);
    }

    /**
     * @return void
     */
    public function testValidateTranslationReturnsFailedResponseWhenJSONIsInvalid(): void
    {
        // Arrange
        $this->tester->haveInvalidTranslationFile();

        // Act
        $validatorResult = $this->tester->getFacade()->validateTranslation(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validatorResult->getErrors());

        $expectedErrorMessage = current($validatorResult->getErrors());
        $this->assertSame('Translation file "vfs://root/config/app/translation/translation.json" contains invalid JSON. Error: "Syntax error".', $expectedErrorMessage);
    }
}
