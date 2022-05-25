<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Acp;

use Codeception\Test\Unit;
use Transfer\ManifestCollectionTransfer;
use Transfer\ManifestConditionsTransfer;
use Transfer\ManifestCriteriaTransfer;

/**
 * @group SprykerSdk
 * @group Acp
 * @group AppManifestFacadeTest
 */
class AppManifestFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const INVALID_LOCALE = 'en_U';

    /**
     * @var \SprykerSdkTest\Acp\Tester
     */
    protected Tester $tester;

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
            $this->tester->getMessagesFromManifestResponseTransfer($manifestResponseTransfer),
            sprintf(
                'Expected to get exactly "1" error, got "%s". Either there is no error or you have more than expected',
                $manifestResponseTransfer->getErrors()->count(),
            ),
        );
    }

    /**
     * @return void
     */
    public function testGetManifestCollectionShouldReturnCollection(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithManifestAndConfiguration();

        $manifestCriteriaTransfer = new ManifestCriteriaTransfer();
        $manifestConditionsTransfer = new ManifestConditionsTransfer();

        $manifestCriteriaTransfer->setManifestConditions($manifestConditionsTransfer);
        $manifestConditionsTransfer->setConfigurationFilePath(
            $this->tester->getRootPath() . '/config/app/configuration.json',
        );
        $manifestConditionsTransfer->setManifestFolder(
            $this->tester->getRootPath() . '/config/app/manifest',
        );
        $manifestConditionsTransfer->setTranslationFilePath(
            $this->tester->getRootPath() . '/config/app/translation.json',
        );

        // Act
        $collection = $this->tester->getFacade()->getManifestCollection($manifestCriteriaTransfer);

        // Assert
        $this->assertNotEmpty($collection->getTranslation()->getTranslations());
        $this->assertNotEmpty($collection->getManifests());
        $this->assertNotEmpty($collection->getConfiguration());
    }

    /**
     * @return void
     */
    public function testGetManifestCollectionWithInvalidJsonFilesShouldReturnEmptyCollection(): void
    {
        // Arrange
        $this->tester->haveInvalidJsonStructure();

        $manifestCriteriaTransfer = new ManifestCriteriaTransfer();
        $manifestConditionsTransfer = new ManifestConditionsTransfer();

        $manifestCriteriaTransfer->setManifestConditions($manifestConditionsTransfer);
        $manifestConditionsTransfer->setTranslationFilePath(
            $this->tester->getRootPath() . '/config/app/translation.json',
        );
        $manifestCriteriaTransfer->setManifestConditions($manifestConditionsTransfer);
        $manifestConditionsTransfer->setConfigurationFilePath(
            $this->tester->getRootPath() . '/config/app/configuration.json',
        );
        $manifestConditionsTransfer->setManifestFolder(
            $this->tester->getRootPath() . '/config/app/manifest',
        );

        // Act
        $collection = $this->tester->getFacade()->getManifestCollection($manifestCriteriaTransfer);

        // Assert
        $this->assertEmpty($collection->getTranslation());
        $this->assertEmpty($collection->getConfiguration());
    }

    /**
     * @return void
     */
    public function testGetExistingKeysToTranslateWithManifestCollectionShouldReturnTranslationKeys(): void
    {
        // Arrange
        $manifestCollection = $this->tester->haveManifestCollection();

        // Act
        $keysToTranslate = $this->tester->getFacade()->getExistingKeysToTranslate($manifestCollection);

        // Assert
        $this->assertCount(5, $keysToTranslate);
    }

    /**
     * @return void
     */
    public function testGetExistingKeysToTranslateWithEmptyManifestCollection(): void
    {
        // Arrange
        $manifestCollection = new ManifestCollectionTransfer();

        // Act
        $keysToTranslate = $this->tester->getFacade()->getExistingKeysToTranslate($manifestCollection);

        // Assert
        $this->assertEmpty($keysToTranslate);
    }
}
