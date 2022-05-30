<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Acp\Reader;

use Codeception\Test\Unit;
use SprykerSdk\Acp\AcpConfig;
use SprykerSdk\Acp\Manifest\Reader\AppManifestReader;
use SprykerSdk\Acp\Validator\Finder\Finder;
use SprykerSdkTest\Acp\Tester;
use Transfer\ManifestCriteriaTransfer;

/**
 * @group SprykerSdk
 * @group Acp
 * @group Reader
 * @group AppManifestReaderTest
 */
class AppManifestReaderTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Acp\Tester
     */
    protected Tester $tester;

    /**
     * @return void
     */
    public function testGetManifestCollectionUsingDefaultConfig(): void
    {
        // Arrange
        $this->tester->haveValidTranslationWithManifestAndConfiguration();

        $config = $this->createMock(AcpConfig::class);
        $config->expects($this->once())
            ->method('getDefaultManifestFolder')
            ->willReturn($this->tester->getRootPath() . '/config/app/manifest');

        $config->expects($this->once())
            ->method('getDefaultConfigurationFile')
            ->willReturn($this->tester->getRootPath() . '/config/app/configuration.json');

        $config->expects($this->once())
            ->method('getDefaultTranslationFile')
            ->willReturn($this->tester->getRootPath() . '/config/app/translation.json');

        $appManifestReader = new AppManifestReader($config, new Finder());

        $manifestCriteriaTransfer = new ManifestCriteriaTransfer();

        // Act
        $collection = $appManifestReader->getManifestCollection($manifestCriteriaTransfer);

        // Assert
        $this->assertNotEmpty($collection->getTranslation()->getTranslations());
        $this->assertNotEmpty($collection->getManifests());
        $this->assertNotEmpty($collection->getConfiguration());
    }
}
