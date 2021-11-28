<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Request;

interface ValidateRequestInterface
{
    /**
     * @param string $manifestPath
     *
     * @return void
     */
    public function setManifestPath(string $manifestPath): void;

    /**
     * @return string
     */
    public function getManifestPath(): string;

    /**
     * @param string $configurationFile
     *
     * @return void
     */
    public function setConfigurationFile(string $configurationFile): void;

    /**
     * @return string
     */
    public function getConfigurationFile(): string;

    /**
     * @param string $translationFile
     *
     * @return void
     */
    public function setTranslationFile(string $translationFile): void;

    /**
     * @return string
     */
    public function getTranslationFile(): string;
}
