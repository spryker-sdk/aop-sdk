<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Registrator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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
            $request = [
                'body' => json_encode($this->getBody($registerRequestTransfer)),
                'headers' => [
                    'content-type' => 'application/json',
                    'accept' => 'application/json',
                    'authorization' => 'Bearer ' . $registerRequestTransfer->getAuthorizationTokenOrFail(),
                ],
            ];
            $this->getGuzzleClient($registryUrl)->post('/apps', $request);
        } catch (ClientException $e) {
            // A 409 indicates that the App already exist and we need to update instead
            if ($e->getCode() === 409) {
                $this->getGuzzleClient($registryUrl)->patch(sprintf('/apps/%s', $registerRequestTransfer->getAppIdentifierOrFail()), $request);
            }
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
        return [
            'data' => [
                'type' => 'apps',
                'attributes' => [
                    'id' => $registerRequestTransfer->getAppIdentifierOrFail(),
                    'baseUrl' => $registerRequestTransfer->getBaseUrl(),
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
        return (string)json_encode([
            'configuration' => '/private/configure',
            'disconnection' => '/private/disconnect',
        ]);
    }

    /**
     * @param string $registryUrl
     *
     * @return \GuzzleHttp\Client
     */
    protected function getGuzzleClient(string $registryUrl): Client
    {
        return new Client(['base_uri' => $registryUrl]);
    }
}
