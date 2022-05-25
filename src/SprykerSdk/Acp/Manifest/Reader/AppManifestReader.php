<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Manifest\Reader;

use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerSdk\Acp\AcpConfig;
use SprykerSdk\Acp\Validator\Finder\FinderInterface;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Transfer\ManifestCollectionTransfer;
use Transfer\ManifestConfigurationTransfer;
use Transfer\ManifestCriteriaTransfer;
use Transfer\ManifestTransfer;
use Transfer\ManifestTranslationTransfer;

class AppManifestReader implements AppManifestReaderInterface
{
    /**
     * @var \SprykerSdk\Acp\AcpConfig
     */
    protected AcpConfig $acpConfig;

    /**
     * @var \SprykerSdk\Acp\Validator\Finder\FinderInterface
     */
    protected FinderInterface $finder;

    /**
     * @param \SprykerSdk\Acp\AcpConfig $acpConfig
     * @param \SprykerSdk\Acp\Validator\Finder\FinderInterface $finder
     */
    public function __construct(AcpConfig $acpConfig, FinderInterface $finder)
    {
        $this->acpConfig = $acpConfig;
        $this->finder = $finder;
    }

    /**
     * @param \Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return \Transfer\ManifestCollectionTransfer
     */
    public function getManifestCollection(ManifestCriteriaTransfer $manifestCriteriaTransfer): ManifestCollectionTransfer
    {
        $manifestCollectionTransfer = new ManifestCollectionTransfer();

        $this->extendCollectionByManifest($manifestCollectionTransfer, $manifestCriteriaTransfer);
        $this->extendCollectionByConfiguration($manifestCollectionTransfer, $manifestCriteriaTransfer);
        $this->extendCollectionByTranslation($manifestCollectionTransfer, $manifestCriteriaTransfer);

        return $manifestCollectionTransfer;
    }

    /**
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     * @param \Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return void
     */
    protected function extendCollectionByManifest(
        ManifestCollectionTransfer $manifestCollectionTransfer,
        ManifestCriteriaTransfer $manifestCriteriaTransfer
    ): void {
        try {
            /** @var string $manifestFolder */
            $manifestFolder = $manifestCriteriaTransfer->getManifestConditionsOrFail()->getManifestFolder();
        } catch (NullValueException $exception) {
            $manifestFolder = $this->acpConfig->getDefaultManifestFolder();
        }

        try {
            /** @var \Symfony\Component\Finder\SplFileInfo $manifestFile */
            foreach ($this->finder->getFiles($manifestFolder) as $manifestFile) {
                $manifestTransfer = (new ManifestTransfer())
                    ->setLocaleName($manifestFile->getBasename('.json'));

                $manifestCollectionTransfer->addManifest($manifestTransfer);
            }
        } catch (DirectoryNotFoundException $exception) {
        }
    }

    /**
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     * @param \Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return void
     */
    protected function extendCollectionByConfiguration(
        ManifestCollectionTransfer $manifestCollectionTransfer,
        ManifestCriteriaTransfer $manifestCriteriaTransfer
    ): void {
        try {
            /** @var string $configurationFilePath */
            $configurationFilePath = $manifestCriteriaTransfer->getManifestConditionsOrFail()->getConfigurationFilePath();
        } catch (NullValueException $exception) {
            $configurationFilePath = $this->acpConfig->getDefaultConfigurationFile();
        }

        if (!$this->finder->hasFile($configurationFilePath)) {
            return;
        }

        /** @var \SplFileInfo $configurationFile */
        $configurationFile = $this->finder->getFile($configurationFilePath);

        $manifestConfigurationTransfer = (new ManifestConfigurationTransfer())
            ->setConfiguration(json_decode((string)file_get_contents($configurationFile->getPathname()), true));

        if (json_last_error()) {
            return;
        }

        $manifestCollectionTransfer->setConfiguration($manifestConfigurationTransfer);
    }

    /**
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     * @param \Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return void
     */
    protected function extendCollectionByTranslation(
        ManifestCollectionTransfer $manifestCollectionTransfer,
        ManifestCriteriaTransfer $manifestCriteriaTransfer
    ): void {
        try {
            /** @var string $translationFilePath */
            $translationFilePath = $manifestCriteriaTransfer->getManifestConditionsOrFail()->getTranslationFilePath();
        } catch (NullValueException $exception) {
            $translationFilePath = $this->acpConfig->getDefaultTranslationFile();
        }

        if (!$this->finder->hasFile($translationFilePath)) {
            return;
        }

        /** @var \Symfony\Component\Finder\SplFileInfo $translationFile */
        $translationFile = $this->finder->getFile($translationFilePath);

        $manifestTranslationFile = (new ManifestTranslationTransfer())
            ->setTranslations(json_decode((string)file_get_contents($translationFile->getPathname()), true));

        if (json_last_error()) {
            return;
        }

        $manifestCollectionTransfer->setTranslation($manifestTranslationFile);
    }
}
