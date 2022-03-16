<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Manifest\Builder;

use Generated\Shared\Transfer\ManifestRequestTransfer;
use Generated\Shared\Transfer\ManifestResponseTransfer;

class ManifestBuilder implements ManifestBuilderInterface
{
    /**
     * @var array<string>
     */
    protected $transferToManifestTypeMap = [
        'int' => 'integer',
    ];

    /**
     * @param \Generated\Shared\Transfer\ManifestRequestTransfer $manifestRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ManifestResponseTransfer
     */
    public function addManifest(ManifestRequestTransfer $manifestRequestTransfer): ManifestResponseTransfer
    {
        $manifestResponseTransfer = new ManifestResponseTransfer();

        $targetFilePath = $manifestRequestTransfer->getManifestPathOrFail();
        $locale = $manifestRequestTransfer->getManifestOrFail()->getLocaleNameOrFail();

        $targetFile = $targetFilePath . 'en_' . strtoupper($locale) . '.json';

        $manifest = $this->addDefaults();

        $manifest['name'] = $manifestRequestTransfer->getManifestOrFail()->getNameOrFail();
        $manifest['provider'] = $manifestRequestTransfer->getManifestOrFail()->getNameOrFail();

        $this->writeToFile($targetFile, $manifest);

        return $manifestResponseTransfer;
    }

    /**
     * @return array
     */
    protected function addDefaults(): array
    {
        $manifest = [
            'name' => 'Manifest',
            'provider' => '',
            'description' => '',
            'descriptionShort' => '',
            'configureUrl' => '',
            'categories' => [],
            'assets' => [],
            'resources' => [],
            'pages' => [],
            'label' => [],
          ];

        return $manifest;
    }

    /**
     * @param string $targetFile
     * @param array $manifest
     *
     * @return void
     */
    protected function writeToFile(string $targetFile, array $manifest): void
    {
        $manifestSchemaJson = json_encode($manifest, JSON_PRETTY_PRINT);

        $dirname = dirname($targetFile);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0770, true);
        }

        file_put_contents($targetFile, $manifestSchemaJson);
    }
}
