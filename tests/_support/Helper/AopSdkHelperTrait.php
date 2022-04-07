<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

trait AopSdkHelperTrait
{
    /**
     * @return \SprykerSdkTest\Helper\AopSdkHelper
     */
    protected function getAopSdkHelper(): AopSdkHelper
    {
        /** @var \SprykerSdkTest\Helper\AopSdkHelper $aopSdkHelper */
        $aopSdkHelper = $this->getModule('\\' . AopSdkHelper::class);

        return $aopSdkHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
