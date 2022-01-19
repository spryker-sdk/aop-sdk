<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace FooBar\Zed\FooBar;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;

class FooBarDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @return array
     */
    protected function getFooBarPlugins(): array
    {
        return [];
    }
}
