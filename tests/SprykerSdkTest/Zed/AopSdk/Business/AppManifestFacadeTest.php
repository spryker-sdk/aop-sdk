<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AopSdk\Business;

use Codeception\Test\Unit;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AopSdk
 * @group Business
 * @group AppManifestFacadeTest
 */
class AppManifestFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const INVALID_LOCALE = 'en_U';

    /**
     * @var \SprykerSdkTest\Zed\AopSdk\BusinesssTester
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
        $manifestResponseTransfer = $this->tester->getFacade()->createAppManifest(
            $manifestRequestTransfer,
        );

        // Assert
        $this->tester->assertManifestResponseHasNoErrors($manifestResponseTransfer);
        $this->assertFileExists($manifestRequestTransfer->getManifestPath());
    }

    /**
     * @return void
     */
    public function testAddManifestWithInvalidLocaleReturnsErrorResponse(): void
    {
        // Arrange
        $manifestRequestTransfer = $this->tester->haveManifestCreateRequest();
        $manifestRequestTransfer->getManifestOrFail()->setLocaleName(static::INVALID_LOCALE);

        // Act
        $manifestResponseTransfer = $this->tester->getFacade()->createAppManifest(
            $manifestRequestTransfer,
        );

        // Assert
        $this->assertCount(
            1,
            $manifestResponseTransfer->getErrors(),
            sprintf(
                'Expected to get exactly "1" error, got "%s". Either there is no error or you have more than expected',
                $manifestResponseTransfer->getErrors()->count(),
            ),
        );
    }
}
