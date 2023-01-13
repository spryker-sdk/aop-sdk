<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Acp\Manifest\Builder;

use Codeception\Test\Unit;
use SprykerSdk\Acp\Manifest\Builder\AppManifestBuilder;
use SprykerSdkTest\Acp\Tester;

/**
 * @group SprykerSdk
 * @group Acp
 * @group Reader
 * @group AppManifestReaderTest
 */
class AppManifestBuilderTest extends Unit
{
 /**
  * @var \SprykerSdkTest\Acp\Tester
  */
    protected Tester $tester;

    /**
     * @return void
     */
    public function testShouldFallbackToEnglishIfLocaleDoesntExist()
    {
        // Arrange
        $this->tester->haveRealManifestExampleFile('en_US');

        $manifestRequestTransfer = $this->tester->haveManifestCreateRequest('fr_FR');
        $manifestRealExampleContent = $this->tester->haveRealManifestExampleData('en_US');

        // Act
        $appManifestBuilder = new AppManifestBuilder(
            $this->tester->getConfig(),
        );

        $appManifestBuilder->createManifest($manifestRequestTransfer);

        $responseContent = json_decode(
            file_get_contents(
                $manifestRequestTransfer->getManifestPath() .
                $manifestRequestTransfer->getManifest()->getLocaleName() . '.json',
            ),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        // Assert
        $this->assertEquals(
            $manifestRealExampleContent,
            $responseContent,
        );
    }

    /**
     * @dataProvider localeProvider
     *
     * @return void
     */
    public function testShouldUseTheRightExampleFileToMakeTheManifest($locale)
    {
        // Arrange
        $this->tester->haveRealManifestExampleFile($locale);

        $manifestRequestTransfer = $this->tester->haveManifestCreateRequest($locale);
        $manifestLocale = $manifestRequestTransfer->getManifest()->getLocaleName();

        $manifestRealExampleContent = $this->tester->haveRealManifestExampleData($manifestLocale);

        // Act
        $appManifestBuilder = new AppManifestBuilder(
            $this->tester->getConfig(),
        );

        $appManifestBuilder->createManifest($manifestRequestTransfer);

        $responseContent = json_decode(
            file_get_contents(
                $manifestRequestTransfer->getManifestPath() .
                $manifestRequestTransfer->getManifest()->getLocaleName() . '.json',
            ),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        // Assert
        $this->assertEquals(
            $manifestRealExampleContent,
            $responseContent,
        );
    }

    /**
     * @return array[<string>, <string>]
     */
    public function localeProvider()
    {
        return [
            'with `en_US` locale' => ['en_US'],
            'with `de_DE` locale' => ['de_DE'],
        ];
    }
}
