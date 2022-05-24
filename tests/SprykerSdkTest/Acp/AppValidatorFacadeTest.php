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
 * @group AcpFacadeTest
 */
class AppValidatorFacadeTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Acp\Tester
     */
    protected Tester $tester;

    /**
     * @return void
     */
    public function testValidateReturnsSuccessfulResponseWhenValidationIsSuccessful(): void
    {
        // Arrange
        $this->tester->haveValidConfigurations();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validate(
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
    public function testValidateReturnsFailedResponseWhenValidationFails(): void
    {
        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validate(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertTrue($validateResponseTransfer->getErrors()->count() > 0);
    }
}
