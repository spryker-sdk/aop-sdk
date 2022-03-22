<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Configuration\Builder;

use Generated\Shared\Transfer\AppConfigurationRequestTransfer;
use Generated\Shared\Transfer\AppConfigurationResponseTransfer;

class ConfigurationBuilder implements ConfigurationBuilderInterface
{
    /**
     * @var array<string>
     */
    protected $transferToAsyncApiTypeMap = [
        'int' => 'integer',
    ];

    /**
     * @param \Generated\Shared\Transfer\AppConfigurationRequestTransfer $appConfigurationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigurationResponseTransfer
     */
    public function appConfigurationCreate(AppConfigurationRequestTransfer $appConfigurationRequestTransfer): AppConfigurationResponseTransfer
    {
        $appConfigurationResponseTransfer = new AppConfigurationResponseTransfer();

        $this->writeToFile(
            $appConfigurationRequestTransfer->getConfigurationFile(),
            json_encode($appConfigurationRequestTransfer->getProperties(), JSON_PRETTY_PRINT),
        );

        return $appConfigurationResponseTransfer;
    }

    /**
     * @param string $targetFile
     * @param string $configurationFile
     *
     * @return void
     */
    protected function writeToFile(string $targetFile, string $configurationFile): void
    {
        $dirname = dirname($targetFile);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0770, true);
        }

        file_put_contents($targetFile, $configurationFile);
    }
}
