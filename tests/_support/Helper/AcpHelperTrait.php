<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

trait AcpHelperTrait
{
    /**
     * @return \SprykerSdkTest\Helper\AcpHelper
     */
    protected function getAcpHelper(): AcpHelper
    {
        /** @var \SprykerSdkTest\Helper\AcpHelper $acpSdkHelper */
        $acpSdkHelper = $this->getModule('\\' . AcpHelper::class);

        return $acpSdkHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
