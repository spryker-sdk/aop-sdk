<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Manifest\Builder;

use Generated\Shared\Transfer\ManifestRequestTransfer;
use Generated\Shared\Transfer\ManifestResponseTransfer;
use Generated\Shared\Transfer\ManifestTransfer;

class ManifestBuilder implements ManifestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ManifestRequestTransfer $manifestRequestTransfer
     * @param \Generated\Shared\Transfer\ManifestTransfer $manifestTransfer
     *
     * @return \Generated\Shared\Transfer\ManifestResponseTransfer
     */
    public function createManifest(ManifestRequestTransfer $manifestRequestTransfer, ManifestTransfer $manifestTransfer): ManifestResponseTransfer
    {
        $manifestResponseTransfer = new ManifestResponseTransfer();

        $manifestTransfer = new ManifestTransfer();

        $targetFilePath = $manifestRequestTransfer->getManifestPathOrFail();
        $locale = $manifestRequestTransfer->getManifestOrFail()->getLocaleNameOrFail();

        $targetFile = $targetFilePath . $locale . '.json';

        $manifest = $this->addDefaults();

        $manifest['name'] = $manifestTransfer->getName();
        $manifest['provider'] = $manifestRequestTransfer->getManifestOrFail()->getNameOrFail();

        $this->writeToFile($targetFile, $manifest);

        return $manifestResponseTransfer;
    }

    /**
     * @return array<string, mixed>
     */
    protected function addDefaults(): array
    {
        $manifest = [
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
