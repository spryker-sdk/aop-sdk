<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Aop\Manifest\Builder;

use Generated\Shared\Transfer\ManifestRequestTransfer;
use Generated\Shared\Transfer\ManifestResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class AppManifestBuilder implements AppManifestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ManifestRequestTransfer $manifestRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ManifestResponseTransfer
     */
    public function createManifest(ManifestRequestTransfer $manifestRequestTransfer): ManifestResponseTransfer
    {
        $manifestResponseTransfer = new ManifestResponseTransfer();

        $targetFilePath = $manifestRequestTransfer->getManifestPathOrFail();
        $locale = $manifestRequestTransfer->getManifestOrFail()->getLocaleNameOrFail();

        $targetFile = $targetFilePath . $locale . '.json';

        if (file_exists($targetFile)) {
            $manifestResponseTransfer->addError((new MessageTransfer())->setMessage(sprintf('File "%s" already exists.', $targetFile)));

            return $manifestResponseTransfer;
        }

        if ($this->isLocaleIsValid($locale) === false) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage('You have to enter a valid locale, example: en_US');

            $manifestResponseTransfer->addError($messageTransfer);

            return $manifestResponseTransfer;
        }

        $manifest = $this->getManifest($manifestRequestTransfer);

        $this->writeToFile($targetFile, $manifest);

        return $manifestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ManifestRequestTransfer $manifestRequestTransfer
     *
     * @return array<string, mixed>
     */
    protected function getManifest(ManifestRequestTransfer $manifestRequestTransfer): array
    {
        $manifestTransfer = $manifestRequestTransfer->getManifestOrFail();

        return [
            'name' => $manifestTransfer->getNameOrFail(),
            'provider' => $manifestTransfer->getNameOrFail(),
            'description' => '',
            'descriptionShort' => '',
            'configureUrl' => '',
            'categories' => [],
            'assets' => [],
            'resources' => [],
            'pages' => [],
            'label' => [],
          ];
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

    /**
     * @param string $locale
     *
     * @return bool
     */
    protected function isLocaleIsValid(string $locale): bool
    {
        $pattern = '/^[a-z]{2}(?:_[A-Z]{2})?$/';

        return (bool)preg_match($pattern, $locale);
    }
}
