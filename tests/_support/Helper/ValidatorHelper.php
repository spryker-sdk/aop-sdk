<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Generated\Shared\Transfer\ValidateRequestTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerSdk\Zed\AppSdk\Business\AppSdkBusinessFactory;
use SprykerSdk\Zed\AppSdk\Business\AppSdkFacade;
use SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface;

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
            'getProjectRootPath' => function () {
                return $this->rootPath;
            },
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\ValidateRequestTransfer
     */
    public function haveValidateRequest(): ValidateRequestTransfer
    {
        $config = $this->getConfig() ?? new AppSdkConfig();

        $validateRequest = new ValidateRequestTransfer();
        $validateRequest->setManifestPath($config->getDefaultManifestPath());
        $validateRequest->setConfigurationFile($config->getDefaultConfigurationFile());
        $validateRequest->setTranslationFile($config->getDefaultTranslationFile());

        return $validateRequest;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return array
     */
    public function getMessagesFromValidateResponseTransfer(ValidateResponseTransfer $validateResponseTransfer): array
    {
        $messages = [];

        foreach ($validateResponseTransfer->getErrors() as $messageTransfer) {
            $messages[] = $messageTransfer->getMessage();
        }

        return $messages;
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
