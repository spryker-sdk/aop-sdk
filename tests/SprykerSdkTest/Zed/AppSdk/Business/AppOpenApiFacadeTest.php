<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Business;

use Codeception\Test\Unit;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AppSdk
 * @group Business
 * @group AppOpenApiFacadeTest
 */
class AppOpenApiFacadeTest extends Unit
{
 /**
  * @var \SprykerSdkTest\Zed\AppSdk\BusinesssTester
  */
    protected $tester;

    /**
     * @return void
     */
    public function testAddOpenApiAddsANewOpenApiFile(): void
    {
        // Arrange
        $openApiRequestTransfer = $this->tester->haveOpenApiAddRequest();

        // Act
        $openApiResponseTransfer = $this->tester->getFacade()->addOpenApi(
            $openApiRequestTransfer,
        );

        // Assert
        $this->assertFileExists($openApiRequestTransfer->getTargetFile());
    }
}
