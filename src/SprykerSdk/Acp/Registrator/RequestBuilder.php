<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Registrator;

use SprykerSdk\Acp\Validator\Finder\FinderInterface;
use Transfer\RegisterRequestTransfer;

class RequestBuilder
{
    /**
     * @param \SprykerSdk\Acp\Validator\Finder\FinderInterface $finder
     */
    public function __construct(protected FinderInterface $finder)
    {
    }

    /**
     * @param \Transfer\RegisterRequestTransfer $registerRequestTransfer
     *
     * @return string
     */
    public function buildRequestBody(RegisterRequestTransfer $registerRequestTransfer): string
    {
        return (string)json_encode($this->getBody($registerRequestTransfer));
    }

    /**
     * @param \Transfer\RegisterRequestTransfer $registerRequestTransfer
     *
     * @return array<array>
     */
    protected function getBody(RegisterRequestTransfer $registerRequestTransfer): array
    {
        return [
            'data' => [
                'type' => 'apps',
                'attributes' => [
                    'id' => $registerRequestTransfer->getAppIdentifierOrFail(),
                    'baseUrl' => rtrim($registerRequestTransfer->getBaseUrlOrFail(), '/'),
                    'api' => json_encode($this->getJsonContentFromFile($registerRequestTransfer->getAcpApiFileOrFail(), $this->getDefaultApi())),
                    'configuration' => json_encode($this->getJsonContentFromFile($registerRequestTransfer->getConfigurationFileOrFail())),
                    'translation' => json_encode($this->getJsonContentFromFile($registerRequestTransfer->getTranslationFileOrFail())),
                    'manifest' => json_encode($this->getManifests($registerRequestTransfer)),
                ],
            ],
        ];
    }

    /**
     * @param string $filePath
     *
     * @return array
     */
    protected function getJsonContentFromFile(string $filePath, array $defaultValue = []): array
    {
        if (!file_exists($filePath)) {
            return $defaultValue;
        }

        return json_decode((string)file_get_contents($filePath), true) ?? $defaultValue;
    }

    /**
     * @param \Transfer\RegisterRequestTransfer $registerRequestTransfer
     *
     * @return array
     */
    protected function getManifests(RegisterRequestTransfer $registerRequestTransfer): array
    {
        $manifests = [];

        /** @var \Symfony\Component\Finder\SplFileInfo $manifestFile */
        foreach ($this->finder->getFiles($registerRequestTransfer->getManifestPathOrFail()) as $manifestFile) {
            if ($manifestFile->isDir()) {
                continue;
            }
            $manifestData = json_decode((string)file_get_contents($manifestFile->getPathname()), true);
            $localeName = $manifestFile->getFilenameWithoutExtension();
            $manifests[$localeName] = $manifestData;
        }

        return $manifests;
    }

    /**
     * @return array<string, string>
     */
    protected function getDefaultApi(): array
    {
        return [
            'configuration' => '/private/configure',
            'disconnection' => '/private/disconnect',
        ];
    }
}
