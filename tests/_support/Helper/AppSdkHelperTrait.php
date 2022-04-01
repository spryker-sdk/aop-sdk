<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

trait AppSdkHelperTrait
{
    /**
     * @return \SprykerSdkTest\Helper\AppSdkHelper
     */
    protected function getAppSdkHelper(): AppSdkHelper
    {
        /** @var \SprykerSdkTest\Helper\AppSdkHelper $appSdkHelper */
        $appSdkHelper = $this->getModule('\\' . AppSdkHelper::class);

        return $appSdkHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
