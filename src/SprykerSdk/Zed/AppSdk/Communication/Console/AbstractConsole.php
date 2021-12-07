<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use SprykerSdk\Zed\AppSdk\AppSdkConfig;

/**
 * @method \SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface getFacade()
 */
class AbstractConsole extends Console
{
    /**
     * @var int
     */
    public const CODE_SUCCESS = 0;

    /**
     * @var int
     */
    public const CODE_ERROR = 1;

    /**
     * @var \SprykerSdk\Zed\AppSdk\AppSdkConfig|null
     */
    protected ?AppSdkConfig $config = null;

    /**
     * @param string|null $name
     * @param \SprykerSdk\Zed\AppSdk\AppSdkConfig|null $config
     */
    public function __construct(?string $name = null, ?AppSdkConfig $config = null)
    {
        $this->config = $config;

        parent::__construct($name);
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\AppSdkConfig
     */
    protected function getConfig(): AppSdkConfig
    {
        if ($this->config === null) {
            $this->config = new AppSdkConfig();
        }

        return $this->config;
    }
}
