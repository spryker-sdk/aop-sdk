<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use org\bovigo\vfs\vfsStream;

class AppConfigurationCreateHelper extends Module
{
    /**
     * @var string|null
     */
    protected ?string $rootUrl = null;

    /**
     * @return string
     */
    public function getRootUrl(): string
    {
        if (!$this->rootUrl) {
            $this->rootUrl = vfsStream::setup('root')->url();
        }

        return $this->rootUrl;
    }
}
