<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Configuration\Reader;

class AppConfigurationReader implements AppConfigurationReaderInterface
{
    /**
     * @param string $filePath
     *
     * @return array
     */
    public function readConfigurationFile(string $filePath): array
    {
        $configurationFileContents = $this->readFile($filePath);

        return json_decode($configurationFileContents, true);
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    protected function readFile(string $filePath): string
    {
        $content = file_get_contents($filePath);

        if ($content === false) {
            return '';
        }

        return $content;
    }
}
