<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Request;

class ValidateRequest implements ValidateRequestInterface
{
    /**
     * @var string
     */
    protected string $manifestPath = '';

    /**
     * @var string
     */
    protected string $configurationFile = '';

    /**
     * @var string
     */
    protected string $translationFile = '';

    /**
     * @param string $manifestPath
     *
     * @return void
     */
    public function setManifestPath(string $manifestPath): void
    {
        $this->manifestPath = $manifestPath;
    }

    /**
     * @return string
     */
    public function getManifestPath(): string
    {
        return $this->manifestPath;
    }

    /**
     * @param string $configurationFile
     *
     * @return void
     */
    public function setConfigurationFile(string $configurationFile): void
    {
        $this->configurationFile = $configurationFile;
    }

    /**
     * @return string
     */
    public function getConfigurationFile(): string
    {
        return $this->configurationFile;
    }

    /**
     * @param string $translationFile
     *
     * @return void
     */
    public function setTranslationFile(string $translationFile): void
    {
        $this->translationFile = $translationFile;
    }

    /**
     * @return string
     */
    public function getTranslationFile(): string
    {
        return $this->translationFile;
    }
}
