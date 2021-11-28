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
     * @var \AppSdkTest\AppSdkTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateReturnsSuccessfulResponseWhenValidationIsSuccessful(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithManifestAndConfiguration();

        // Act
        $validatorResult = $this->tester->getFacade()->validate(
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
    public function testValidateReturnsFailedResponseWhenValidationFailes(): void
    {
        // Act
        $validatorResult = $this->tester->getFacade()->validate(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $this->assertFalse($validatorResult->isValid());
    }
}
