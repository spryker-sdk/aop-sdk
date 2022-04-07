<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AopSdk\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use SprykerSdk\Zed\AopSdk\AopSdkConfig;

/**
 * @method \SprykerSdk\Zed\AopSdk\Business\AopSdkFacadeInterface getFacade()
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
     * @var \SprykerSdk\Zed\AopSdk\AopSdkConfig|null
     */
    protected ?AopSdkConfig $config = null;

    /**
     * @param string|null $name
     * @param \SprykerSdk\Zed\AopSdk\AopSdkConfig|null $config
     */
    public function __construct(?string $name = null, ?AopSdkConfig $config = null)
    {
        $this->config = $config;

        parent::__construct($name);
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\AopSdkConfig
     */
    protected function getConfig(): AopSdkConfig
    {
        if ($this->config === null) {
            $this->config = new AopSdkConfig();
        }

        return $this->config;
    }
}
