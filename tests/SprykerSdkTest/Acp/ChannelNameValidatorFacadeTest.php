<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Acp;

use Codeception\Test\Unit;

/**
 * @group SprykerSdk
 * @group Acp
 * @group ChannelNameValidatorFacadeTest
 */
class ChannelNameValidatorFacadeTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Acp\Tester
     */
    protected Tester $tester;

    /**
     * @return void
     */
    public function testValidateChannelNamesReturnsSuccessfulResponseWhenConfigurationIsValid(): void
    {
        // Arrange
        $this->tester->haveValidProjectConfiguration();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateChannelNames($this->tester->haveChannelsValidateRequest());

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
    public function testValidateChannelNamesReturnsFailedResponseWhenConfigurationIsInvalid(): void
    {
        // Arrange
        $this->tester->haveInvalidProjectConfiguration();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateChannelNames($this->tester->haveChannelsValidateRequest());

        // Assert
        $this->assertCount(3, $validateResponseTransfer->getErrors(), 'Expected that validation failed.');
    }

    /**
     * @return void
     */
    public function testValidateChannelNamesReturnsFailedResponseWhenConfigurationIsEmpty(): void
    {
        // Arrange
        $this->tester->haveEmptyProjectConfiguration();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateChannelNames($this->tester->haveChannelsValidateRequest());

        // Assert
        $this->assertCount(2, $validateResponseTransfer->getErrors(), 'Expected that validation failed.');
    }

    /**
     * @return void
     */
    public function testValidateChannelNamesReturnsFailedResponseWhenConfigurationHasMissingChannel(): void
    {
        // Arrange
        $this->tester->haveMissingChannelProjectConfiguration();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateChannelNames($this->tester->haveChannelsValidateRequest());

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors(), 'Expected that validation failed.');
    }

    /**
     * @return void
     */
    public function testValidateChannelNamesReturnsFailedResponseWhenConfigurationIsNotFound(): void
    {
        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateChannelNames($this->tester->haveChannelsValidateRequest());

        // Assert
        $this->assertCount(1, $validateResponseTransfer->getErrors(), 'Expected that validation failed.');
        $expectedErrorMessage = $this->tester->getMessagesFromValidateResponseTransfer($validateResponseTransfer)[0];
        $this->assertSame('No "config_default.php" file found.', $expectedErrorMessage);
    }
}
