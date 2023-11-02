<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use org\bovigo\vfs\vfsStream;
use SprykerSdk\Acp\AcpConfig;
use SprykerSdk\Acp\AcpFacade;
use SprykerSdk\Acp\AcpFacadeInterface;
use SprykerSdk\Acp\AcpFactory;
use SprykerSdk\Acp\Console\AbstractConsole;
use SprykerSdk\Acp\Console\RegisterConsole;
use SprykerSdk\Acp\Registrator\Registrator;
use SprykerSdk\Acp\Registrator\RequestBuilder;
use SprykerSdk\Acp\Validator\Finder\Finder;
use SprykerSdk\Acp\Validator\Validator;
use Transfer\ValidateResponseTransfer;

class AcpHelper extends Module
{
    /**
     * @var string|null
     */
    protected ?string $rootPath = null;

    protected Client $guzzleClient;

    /**
     * @codeCoverageIgnore
     *
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        // Ensure we are always using the virtual filesystem even if none of the have* methods was called.
        $this->rootPath = vfsStream::setup('root')->url();
    }

    /**
     * @codeCoverageIgnore
     *
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->rootPath = null;
    }

    /**
     * @return \SprykerSdk\Acp\Console\AbstractConsole
     */
    public function getRegisterConsoleWithAtrsResponse(int $code = 201, ?int $secondStatus = null): AbstractConsole
    {
        // Array of responses is needed for MockHandler when first response returns 40x and httpClient is executed second time.
        $responses = [new Response($code)];
        if ($secondStatus) {
            $responses[] = new Response($secondStatus);
        }

        // Middleware http_errors is added because by default Client is created with \GuzzleHttp\HandlerStack::create() handler.
        $mockHandler = MockHandler::createWithMiddleware($responses);
        $mockHandler->push(Middleware::httpErrors(), 'http_errors');
        $guzzleClientMock = new Client(['handler' => $mockHandler]);

        $registratorMock = Stub::construct(Registrator::class, [
            $this->getConfig(),
            new RequestBuilder(new Finder()),
        ], [
            'getGuzzleClient' => $guzzleClientMock,
            'config' => $this->getConfig(),
        ]);

        $validatorMock = Stub::make(Validator::class, [
            'validate' => new ValidateResponseTransfer(),
        ]);

        $factoryMock = Stub::make(AcpFactory::class, [
            'createAppRegistrator' => $registratorMock,
            'createValidator' => $validatorMock,
            'getConfig' => $this->getConfig(),
        ]);

        $facade = $this->getFacade();
        $facade->setFactory($factoryMock);

        $registerConsole = new RegisterConsole();
        $registerConsole->setFacade($facade);
        $registerConsole->setConfig($this->getConfig());

        return $registerConsole;
    }

    /**
     * @return \SprykerSdk\Acp\AcpFacadeInterface
     */
    public function getFacade(): AcpFacadeInterface
    {
        return new AcpFacade();
    }

    /**
     * @return \SprykerSdk\Acp\AcpConfig
     */
    public function getConfig(): AcpConfig
    {
        return Stub::make(AcpConfig::class, [
            'getProjectRootPath' => function () {
                return $this->rootPath;
            },
            'getPackageRootPath' => function () {
                return $this->rootPath;
            },
        ]);
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * This will ensure that the AcpConfig::getProjectRootPath() will return the passed path.
     *
     * @param string $rootPath
     *
     * @return void
     */
    public function mockRoot(string $rootPath): void
    {
        $this->rootPath = $rootPath;
    }

    /**
     * Sets up an expected directory structure in the virtual filesystem
     *
     * @param array $structure
     *
     * @return void
     */
    public function mockDirectoryStructure(array $structure): void
    {
        // Set up the virtual filesystem structure
        $root = vfsStream::setup('root', null, $structure);
        $this->mockRoot($root->url());
    }
}
