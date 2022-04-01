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
     * @var \SprykerSdkTest\Zed\AppSdk\BusinesssTester
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
        $validateResponseTransfer = $this->tester->getFacade()->validateAppTranslation(
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
    public function testValidateTranslationReturnsFailedResponseWhenNoManifestFileExist(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithoutManifest();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAppTranslation(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors());

        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('Can not validate the Translation file "vfs://root/config/app/translation/translation.json" without existing manifest file(s).', $expectedErrorMessage->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateTranslationReturnsFailedResponseWhenNoConfigurationFileExist(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithManifestAndWithoutConfiguration();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAppTranslation(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors());

        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('Can not validate the Translation file "vfs://root/config/app/translation/translation.json" without existing "configuration.json" file.', $expectedErrorMessage->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateTranslationReturnsFailedResponseWhenTranslationValueForLocaleIsMissing(): void
    {
        // Arrange
        $this->tester->haveMissingTranslationValueTranslationFile();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAppTranslation(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors());

        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('Missing translation for key "foo.bar" and locale "en_US".', $expectedErrorMessage->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateTranslationReturnsFailedResponseWhenTranslationFileNotFound(): void
    {
        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAppTranslation(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors());

        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('No "translation.json" file found.', $expectedErrorMessage->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateTranslationReturnsFailedResponseWhenJSONIsInvalid(): void
    {
        // Arrange
        $this->tester->haveInvalidTranslationFile();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAppTranslation(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors());

        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertSame('Translation file "vfs://root/config/app/translation/translation.json" contains invalid JSON. Error: "Syntax error".', $expectedErrorMessage->getMessage());
    }
}
