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
 * @group Manifest
 * @group Builder
 * @group AppManifestBuilderTest
 */
class AppManifestBuilderTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Acp\Tester
     */
    protected Tester $tester;

    /**
     * @dataProvider localeProvider
     *
     * @param string $locale
     *
     * @return void
     */
    public function testShouldUseTheRightExampleFileToBuildTheManifest($locale): void
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
     * @return void
     */
    public function testShouldFallbackToEnglishIfLocaleDoesntExist(): void
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
     * @return void
     */
    public function testShouldEmptyDataIfFallbackDoesntExistExist(): void
    {
        // Arrange
        $this->tester->haveEmptyManifestExampleFile('en_US');

        $manifestRequestTransfer = $this->tester->haveManifestCreateRequest();

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
            [
                'name' => $manifestRequestTransfer->getManifest()->getName(),
                'provider' => $manifestRequestTransfer->getManifest()->getName(),
                'description' => [],
                'descriptionShort' => [],
                'url' => [],
                'isAvailable' => [],
                'developedBy' => '',
                'business_models' => [],
                'categories' => [],
                'pages' => [],
                'assets' => [],
                'label' => [],
                'resources' => [],
            ],
            $responseContent,
        );
    }

    /**
     * @return array[<string>, <string>]
     */
    public function localeProvider(): array
    {
        return [
            'with `en_US` locale' => ['en_US'],
            'with `de_DE` locale' => ['de_DE'],
        ];
    }
}
