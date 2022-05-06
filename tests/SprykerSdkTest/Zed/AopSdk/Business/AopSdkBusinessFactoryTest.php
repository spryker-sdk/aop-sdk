<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AopSdk\Business;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AopSdk\Business\AopSdkBusinessFactory;
use SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder\OpenApiCodeBuilderInterface;
use SprykerSdkTest\Zed\AopSdk\BusinesssTester;

/**
 * @group AopSdk
 * @group AopSdkBusinessFactoryTest
 */
class AopSdkBusinessFactoryTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AopSdk\BusinesssTester
     */
    protected BusinesssTester $tester;

    /**
     * @return void
     */
    public function testCreateOpenApiCodeBuilder(): void
    {
        $factory = new AopSdkBusinessFactory();
        $this->assertInstanceOf(OpenApiCodeBuilderInterface::class, $factory->createOpenApiCodeBuilder());
    }
}
