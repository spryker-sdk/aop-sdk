<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use org\bovigo\vfs\vfsStream;
use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerSdk\Zed\AppSdk\Business\AppSdkFacade;
use SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface;

class AppSdkHelper extends Module
{
    /**
     * @var string|null
     */
    protected ?string $rootPath = null;

    /**
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
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->rootPath = null;
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface
     */
    public function getFacade(): AppSdkFacadeInterface
    {
        return new AppSdkFacade();
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\AppSdkConfig
     */
    public function getConfig(): AppSdkConfig
    {
        if ($this->rootPath === null) { // This will always be set, check if a test fails as it expects the normal filesystem
            return new AppSdkConfig();
        }

        return Stub::make(AppSdkConfig::class, [
            'getProjectRootPath' => function () {
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
     * This will ensure that the AppSdkConfig::getProjectRootPath() will return the passed path.
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
