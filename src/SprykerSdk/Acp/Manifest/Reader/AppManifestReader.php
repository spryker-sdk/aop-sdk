<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Manifest\Reader;

use Generated\Shared\Transfer\ManifestCollectionTransfer;
use Generated\Shared\Transfer\ManifestConfigurationTransfer;
use Generated\Shared\Transfer\ManifestCriteriaTransfer;
use Generated\Shared\Transfer\ManifestTransfer;
use Generated\Shared\Transfer\ManifestTranslationTransfer;
use SprykerSdk\Acp\AcpConfig;
use SprykerSdk\Acp\Validator\Finder\FinderInterface;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class AppManifestReader implements AppManifestReaderInterface
{
    /**
     * @var \SprykerSdk\Acp\AcpConfig
     */
    protected $acpConfig;

    /**
     * @var \SprykerSdk\Acp\Validator\Finder\FinderInterface
     */
    protected $finder;

    /**
     * @param \SprykerSdk\Acp\AcpConfig $acpConfig
     */
    public function __construct(AcpConfig $acpConfig, FinderInterface $finder)
    {
        $this->acpConfig = $acpConfig;
        $this->finder = $finder;
    }

    /**
     * @param \Generated\Shared\Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ManifestCollectionTransfer
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
     * @param \Generated\Shared\Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     * @param \Generated\Shared\Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return void
     */
    protected function extendCollectionByManifest(
        ManifestCollectionTransfer $manifestCollectionTransfer,
        ManifestCriteriaTransfer $manifestCriteriaTransfer
    ): void {
        $manifestFolder = $manifestCriteriaTransfer->getManifestConditions()->getManifestFolder();
        if ($manifestFolder === null) {
            $manifestFolder = $this->acpConfig->getDefaultManifestFolder();
        }

        try {
            /** @var \SplFileInfo $manifestFile */
            foreach ($this->finder->getFiles($manifestFolder) as $manifestFile) {
                $manifestTransfer = (new ManifestTransfer())
                    ->setLocaleName($manifestFile->getBasename('.json'));
                $manifestCollectionTransfer->addManifest($manifestTransfer);
            }
        } catch (DirectoryNotFoundException $exception) {
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     * @param \Generated\Shared\Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return void
     */
    protected function extendCollectionByConfiguration(
        ManifestCollectionTransfer $manifestCollectionTransfer,
        ManifestCriteriaTransfer $manifestCriteriaTransfer
    ): void {
        $configurationFilePath = $manifestCriteriaTransfer->getManifestConditions()->getConfigurationFilePath();
        if ($configurationFilePath === null) {
            $configurationFilePath = $this->acpConfig->getDefaultConfigurationFile();
        }

        /** @var \SplFileInfo $configurationFile */
        $configurationFile = $this->finder->getFile($configurationFilePath);

        if ($configurationFile === null) {
            return;
        }

        $manifestConfigurationTransfer = (new ManifestConfigurationTransfer())
            ->setConfiguration(json_decode((string)file_get_contents($configurationFile->getPathname()), true));

        if (json_last_error()) {
            return;
        }

        $manifestCollectionTransfer->setConfiguration($manifestConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     * @param \Generated\Shared\Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return void
     */
    protected function extendCollectionByTranslation(
        ManifestCollectionTransfer $manifestCollectionTransfer,
        ManifestCriteriaTransfer $manifestCriteriaTransfer
    ): void {
        $translationFilePath = $manifestCriteriaTransfer->getManifestConditions()->getTranslationFilePath();
        if ($translationFilePath === null) {
            $translationFilePath = $this->acpConfig->getDefaultTranslationFile();
        }

        /** @var \SplFileInfo $translationFile */
        $translationFile = $this->finder->getFile($translationFilePath);

        if ($translationFile === null) {
            return;
        }

        $manifestTranslationFile = (new ManifestTranslationTransfer())
            ->setTranslations(json_decode((string)file_get_contents($translationFile->getPathname()), true));

        if (json_last_error()) {
            return;
        }

        $manifestCollectionTransfer->setTranslation($manifestTranslationFile);
    }
}
