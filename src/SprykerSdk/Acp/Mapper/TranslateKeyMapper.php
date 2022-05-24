<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Mapper;

use Transfer\ManifestCollectionTransfer;

class TranslateKeyMapper implements TranslateKeyMapperInterface
{
    /**
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     *
     * @return array<string>
     */
    public function getExistingKeysToTranslate(ManifestCollectionTransfer $manifestCollectionTransfer): array
    {
        $configuration = $manifestCollectionTransfer->getConfiguration();

        if ($configuration === null || !isset($configuration->getConfiguration()['properties'])) {
            return [];
        }

        $keysToTranslate = [];
        foreach ($configuration->getConfiguration()['properties'] as $propertyConfiguration) {
            $keysToTranslate = $this->addKeyIfIsset($keysToTranslate, $propertyConfiguration, 'title');
            $keysToTranslate = $this->addKeyIfIsset($keysToTranslate, $propertyConfiguration, 'placeholder');

            if (isset($propertyConfiguration['oneOf']) && is_array($propertyConfiguration['oneOf'])) {
                foreach ($propertyConfiguration['oneOf'] as $element) {
                    $keysToTranslate = $this->addKeyIfIsset($keysToTranslate, $element, 'description');
                }
            }
            if (isset($propertyConfiguration['items']['oneOf']) && is_array($propertyConfiguration['items']['oneOf'])) {
                foreach ($propertyConfiguration['items']['oneOf'] as $element) {
                    $keysToTranslate = $this->addKeyIfIsset($keysToTranslate, $element, 'description');

                    if (isset($element['enum']) && is_array($element['enum'])) {
                        $keysToTranslate = array_merge($keysToTranslate, $element['enum']);
                    }
                }
            }
        }

        return array_unique($keysToTranslate);
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
