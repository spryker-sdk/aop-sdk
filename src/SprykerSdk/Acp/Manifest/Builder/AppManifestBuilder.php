<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Manifest\Builder;

use SprykerSdk\Acp\AcpConfig;
use Transfer\ManifestRequestTransfer;
use Transfer\ManifestResponseTransfer;
use Transfer\MessageTransfer;

class AppManifestBuilder implements AppManifestBuilderInterface
{
    /**
     * @var string
     */
    public const FALLBACK_LOCALE = 'en_US';

    /**
     * @var \SprykerSdk\Acp\AcpConfig
     */
    protected AcpConfig $config;

    /**
     * @param \SprykerSdk\Acp\AcpConfig $config
     */
    public function __construct(AcpConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Transfer\ManifestRequestTransfer $manifestRequestTransfer
     *
     * @return \Transfer\ManifestResponseTransfer
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

        $manifest = $this->getManifest($manifestRequestTransfer, $locale);

        $this->writeToFile($targetFile, $manifest);

        return $manifestResponseTransfer;
    }

    /**
     * @param \Transfer\ManifestRequestTransfer $manifestRequestTransfer
     *
     * @return array<string, mixed>
     */
    protected function getManifest(ManifestRequestTransfer $manifestRequestTransfer, string $locale): array
    {
        $manifestTransfer = $manifestRequestTransfer->getManifestOrFail();

        $manifestExampleData = $this->getManifestExampleData($locale);

        if (!$manifestExampleData) {
            $manifestExampleData = $this->getManifestExampleData(static::FALLBACK_LOCALE);
        }

        return [
            'name' => $manifestTransfer->getNameOrFail(),
            'provider' => $manifestTransfer->getNameOrFail(),
            'description' => $manifestExampleData['description'],
            'descriptionShort' => $manifestExampleData['descriptionShort'],
            'url' => $manifestExampleData['url'],
            'isAvailable' => $manifestExampleData['isAvailable'],
            'business_models' => $manifestExampleData['business_models'],
            'categories' => $manifestExampleData['categories'],
            'pages' => $manifestExampleData['pages'],
            'assets' => $manifestExampleData['assets'],
            'label' => $manifestExampleData['label'],
            'resources' => $manifestExampleData['resources'],
          ];
    }

    /**
     * @param string $locale
     *
     * @return array|null
     */
    protected function getManifestExampleData(string $locale): ?array
    {
        $manifestExample = $this->config->getDefaultManifestPath() . $locale . '.json';

        if (!file_exists($manifestExample)) {
            return null;
        }

        return json_decode(file_get_contents($manifestExample), true, 512, JSON_THROW_ON_ERROR);
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
