<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Registrator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use SprykerSdk\Acp\AcpConfig;
use Throwable;
use Transfer\MessageTransfer;
use Transfer\RegisterRequestTransfer;
use Transfer\RegisterResponseTransfer;

class Registrator implements RegistratorInterface
{
    /**
     * @param \SprykerSdk\Acp\AcpConfig $config
     * @param \SprykerSdk\Acp\Registrator\RequestBuilder $requestBuilder
     */
    public function __construct(protected AcpConfig $config, protected RequestBuilder $requestBuilder)
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
        $httpClient = $this->getGuzzleClient($registryUrl);
        try {
            $request = [
                'body' => $this->requestBuilder->buildRequestBody($registerRequestTransfer),
                'headers' => [
                    'content-type' => 'application/json',
                    'accept' => 'application/json',
                    'authorization' => 'Bearer ' . $registerRequestTransfer->getAuthorizationTokenOrFail(),
                ],
            ];
            $httpClient->post('/apps', $request);
        } catch (ClientException $e) {
            // A 409 indicates that the App already exist and we need to update instead
            if ($e->getCode() !== 409) {
                $registerResponseTransfer->addError(
                    (new MessageTransfer())->setMessage($e->getMessage()),
                );

                return $registerResponseTransfer;
            }

            try {
                // PATCH the existing app
                $httpClient->patch(sprintf('/apps/%s', $registerRequestTransfer->getAppIdentifierOrFail()), $request);
            } catch (Throwable $e) {
                $registerResponseTransfer->addError(
                    (new MessageTransfer())->setMessage($e->getMessage()),
                );
            }
        } catch (Throwable $e) {
            $registerResponseTransfer->addError(
                (new MessageTransfer())->setMessage($e->getMessage()),
            );
        }

        return $registerResponseTransfer;
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
