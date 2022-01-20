<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace BazBat\Zed\BazBat;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use stdClass;

class BazBatDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @return array
     */
    protected function getBazBatPlugins(): array
    {
        return [
            new stdClass(),
        ];
    }
}
