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
 * @group AppManifestFacadeTest
 */
class AppManifestFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const LOCALE_NAME = 'en_U1';

    /**
     * @var \SprykerSdkTest\Zed\AppSdk\BusinesssTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddManifestAddsANewManifestFile(): void
    {
        // Arrange
        $manifestRequestTransfer = $this->tester->haveManifestCreateRequest();

        // Act
        $manifestResponseTransfer = $this->tester->getFacade()->createManifest(
            $manifestRequestTransfer,
        );

        // Assert
        $this->tester->assertManifestResponseHasNoErrors($manifestResponseTransfer);
        $this->assertFileExists($manifestRequestTransfer->getManifestPath());
    }

    /**
     * @return void
     */
    public function testWhenInvalidLocaleNameInManifestRequest(): void
    {
        // Arrange
        $manifestRequestTransfer = $this->tester->haveManifestCreateRequest();
        $manifestRequestTransfer->getManifestOrFail()->setLocaleName(static::LOCALE_NAME);

        // Act
        $manifestResponseTransfer = $this->tester->getFacade()->createManifest(
            $manifestRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $manifestResponseTransfer->getErrors(), 'error.');
    }
}
