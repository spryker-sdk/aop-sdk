<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Registrator;

use GuzzleHttp\Client;
use SprykerSdk\Acp\AcpConfig;
use SprykerSdk\Acp\Validator\Finder\FinderInterface;
use Throwable;
use Transfer\MessageTransfer;
use Transfer\RegisterRequestTransfer;
use Transfer\RegisterResponseTransfer;

class Registrator implements RegistratorInterface
{
    /**
     * @param \SprykerSdk\Acp\AcpConfig $config
     * @param \SprykerSdk\Acp\Validator\Finder\FinderInterface $finder
     */
    public function __construct(protected AcpConfig $config, protected FinderInterface $finder)
    {
    }

    /**
     * @param \Transfer\RegisterRequestTransfer $registerRequestTransfer
     *
     * @return \Transfer\RegisterResponseTransfer
     */
    public function register(RegisterRequestTransfer $registerRequestTransfer): RegisterResponseTransfer
    {
        $registerResponseTransfer = new RegisterResponseTransfer();

        $registryUrl = $registerRequestTransfer->getRegistryUrl() ?? $this->config->getRegistryUrl();

        try {
            $response = $this->getGuzzleClient($registryUrl)->post('/apps', [
                'body' => json_encode($this->getBody($registerRequestTransfer)),
                'headers' => [
                    'content-type' => 'application/json',
                    'accept' => 'application/json',
                    'authorization' => 'Bearer ' . $registerRequestTransfer->getAuthorizationTokenOrFail(),
                ],
            ]);
        } catch (Throwable $e) {
            $registerResponseTransfer->addError(
                (new MessageTransfer())->setMessage($e->getMessage()),
            );
        }

        return $registerResponseTransfer;
    }

    /**
     * @param \Transfer\RegisterRequestTransfer $registerRequestTransfer
     *
     * @return array<array>
     */
    protected function getBody(RegisterRequestTransfer $registerRequestTransfer): array
    {
        // PRODUCTION: https://backend-api.de.mini-framework-new.demo-spryker.com/
        // STAGING: https://backend-api.de.mini-app.demo-spryker.com/

        return [
            'data' => [
                'type' => 'apps',
                'attributes' => [
                    'id' => $registerRequestTransfer->getAppIdentifierOrFail(),
                    'baseUrl' => 'https://backend-api.de.mini-app.demo-spryker.com/',
                    'api' => $this->getApi(),
                    'manifest' => json_encode($this->getManifests($registerRequestTransfer)),
                    'configuration' => file_get_contents($registerRequestTransfer->getConfigurationFileOrFail()),
                    'translation' => file_get_contents($registerRequestTransfer->getTranslationFileOrFail()),
                ],
            ],
        ];
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
            $manifestData = json_decode((string)file_get_contents($manifestFile->getPathname()), true);
            $localeName = $manifestFile->getFilenameWithoutExtension();
            $manifests[$localeName] = $manifestData;
        }

        return $manifests;
    }

    /**
     * @return string
     */
    protected function getApi(): string
    {
        $api = (string)json_encode([
            'configuration' => '/private/configure',
            'disconnection' => '/private/disconnect',
        ]);

        return $api;
    }

    /**
     * @param string $registryUrl
     *
     * @return \GuzzleHttp\Client
     */
    protected function getGuzzleClient(string $registryUrl): Client
    {
        $guzzleClient = new Client(['base_uri' => $registryUrl]);

        return $guzzleClient;
    }
}
