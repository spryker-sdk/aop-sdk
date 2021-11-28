<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerSdk\Zed\AppSdk\Business\AppSdkBusinessFactory;
use SprykerSdk\Zed\AppSdk\Business\AppSdkFacade;
use SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface;
use SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequest;
use SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface;

class ValidatorHelper extends Module
{
    protected ?string $rootPath = null;

    /**
     * @param string $rootPath
     *
     * @return void
     */
    public function mockRoot(string $rootPath): void
    {
        $this->rootPath = $rootPath;
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface
     */
    public function getFacade(): AppSdkFacadeInterface
    {
        return new AppSdkFacade($this->getFactory());
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\AppSdkBusinessFactory|null
     */
    public function getFactory(): ?AppSdkBusinessFactory
    {
        $config = $this->getConfig();

        if ($config === null) {
            return null;
        }

        return new AppSdkBusinessFactory($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\AppSdkConfig|null
     */
    public function getConfig(): ?AppSdkConfig
    {
        if ($this->rootPath === null) {
            return null;
        }

        return Stub::make(AppSdkConfig::class, [
            'getRootPath' => function () {
                return $this->rootPath;
            },
        ]);
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface
     */
    public function haveValidateRequest(): ValidateRequestInterface
    {
        $config = $this->getConfig() ?? new AppSdkConfig();

        $validateRequest = new ValidateRequest();
        $validateRequest->setManifestPath($config->getDefaultManifestPath());
        $validateRequest->setConfigurationFile($config->getDefaultConfigurationFile());
        $validateRequest->setTranslationFile($config->getDefaultTranslationFile());

        return $validateRequest;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->rootPath = null;
    }
}
