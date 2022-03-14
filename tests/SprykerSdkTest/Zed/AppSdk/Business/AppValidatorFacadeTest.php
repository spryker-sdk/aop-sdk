<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Business;

use Codeception\Test\Unit;

/**
 * @group AppSdk
 * @group AppSdkFacadeTest
 */
class AppValidatorFacadeTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\BusinesssTester
     */
    protected $tester;

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
