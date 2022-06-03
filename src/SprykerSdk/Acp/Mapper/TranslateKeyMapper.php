<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Mapper;

use Transfer\ManifestCollectionTransfer;
use Transfer\ManifestConfigurationTransfer;

class TranslateKeyMapper implements TranslateKeyMapperInterface
{
    /**
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     *
     * @return array<string>
     */
    public function mapManifestCollectionToTranslateKeys(ManifestCollectionTransfer $manifestCollectionTransfer): array
    {
        $configurationTransfer = $manifestCollectionTransfer->getConfiguration();

        if ($configurationTransfer === null || !isset($configurationTransfer->getConfiguration()['properties'])) {
            return [];
        }

        return $this->mapConfigurationProperties($configurationTransfer);
    }

    /**
     * @param \Transfer\ManifestConfigurationTransfer $configurationTransfer
     *
     * @return array
     */
    protected function mapConfigurationProperties(ManifestConfigurationTransfer $configurationTransfer): array
    {
        $keysToTranslate = [];

        foreach ($configurationTransfer->getConfiguration()['properties'] as $propertyConfiguration) {
            $keysToTranslate = $this->addKeyIfIsset($keysToTranslate, $propertyConfiguration, 'title');
            $keysToTranslate = $this->addKeyIfIsset($keysToTranslate, $propertyConfiguration, 'placeholder');

            $keysToTranslate = $this->mapOneOfProperties($propertyConfiguration, $keysToTranslate);
            $keysToTranslate = $this->mapItemsOneOfProperties($propertyConfiguration, $keysToTranslate);
        }

        return array_unique($keysToTranslate);
    }

    /**
     * @param array $propertyConfiguration
     * @param array $keysToTranslate
     *
     * @return array
     */
    protected function mapItemsOneOfProperties(array $propertyConfiguration, array $keysToTranslate): array
    {
        if (!isset($propertyConfiguration['items']['oneOf']) || !is_array($propertyConfiguration['items']['oneOf'])) {
            return $keysToTranslate;
        }

        foreach ($propertyConfiguration['items']['oneOf'] as $element) {
            $keysToTranslate = $this->addKeyIfIsset($keysToTranslate, $element, 'description');

            if (isset($element['enum']) && is_array($element['enum'])) {
                $keysToTranslate = array_merge($keysToTranslate, $element['enum']);
            }
        }

        return $keysToTranslate;
    }

    /**
     * @param array $propertyConfiguration
     * @param array $keysToTranslate
     *
     * @return array
     */
    protected function mapOneOfProperties(array $propertyConfiguration, array $keysToTranslate): array
    {
        if (!isset($propertyConfiguration['oneOf']) || !is_array($propertyConfiguration['oneOf'])) {
            return $keysToTranslate;
        }

        foreach ($propertyConfiguration['oneOf'] as $element) {
            $keysToTranslate = $this->addKeyIfIsset($keysToTranslate, $element, 'description');
        }

        return $keysToTranslate;
    }

    /**
     * @param array $keysToTranslate
     * @param array $element
     * @param string $key
     *
     * @return array<string>
     */
    protected function addKeyIfIsset(array $keysToTranslate, array $element, string $key): array
    {
        if (isset($element[$key])) {
            $keysToTranslate[] = $element[$key];
        }

        return $keysToTranslate;
    }
}
