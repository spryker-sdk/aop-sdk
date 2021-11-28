<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator\Finder;

use Symfony\Component\Finder\Finder as SymfonyFinder;
use Symfony\Component\Finder\SplFileInfo;

class Finder implements FinderInterface
{
    /**
     * @param string $path
     *
     * @return bool
     */
    public function hasFile(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function hasFiles(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * @param string $path
     *
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    public function getFile(string $path): SplFileInfo
    {
        $iterator = $this->getFinder(dirname($path), basename($path))->files()->getIterator();
        $iterator->rewind();

        return $iterator->current();
    }

    /**
     * @param string $path
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function getFiles(string $path): SymfonyFinder
    {
        return $this->getFinder($path);
    }

    /**
     * @param string $path
     * @param string|null $fileName
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder(string $path, ?string $fileName = null): SymfonyFinder
    {
        $finder = new SymfonyFinder();
        $finder->in($path);

        if ($fileName) {
            $finder->name($fileName);
        }

        return $finder;
    }
}
