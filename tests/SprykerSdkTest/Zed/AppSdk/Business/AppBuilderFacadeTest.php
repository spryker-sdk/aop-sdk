<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AsyncApiRequestTransfer;
use Generated\Shared\Transfer\AsyncApiResponseTransfer;

/**
 * @group AppSdk
 * @group AppSdkFacadeTest
 * @group AppBuilderFacadeTest
 */
class AppBuilderFacadeTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\BusinesssTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildFromAsyncApiReturnsAsyncApiResponse(): void
    {
        // Arrange
        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setProjectNamespace('FooBar')
            ->setTargetFile(codecept_data_dir('api/asyncapi/builder/asyncapi-empty.yml'));

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->buildFromAsyncApi(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->assertInstanceOf(AsyncApiResponseTransfer::class, $asyncApiResponseTransfer);
    }
}
