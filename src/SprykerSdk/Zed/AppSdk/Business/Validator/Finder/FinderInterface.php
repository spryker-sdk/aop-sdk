<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator\Finder;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

interface FinderInterface
{
    /**
     * @param string $path
     *
     * @return bool
     */
    public function hasFile(string $path): bool;

    /**
     * @param string $path
     *
     * @return bool
     */
    public function hasFiles(string $path): bool;

    /**
     * @param string $path
     *
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    public function getFile(string $path): SplFileInfo;

    /**
     * @param string $path
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function getFiles(string $path): Finder;
}
