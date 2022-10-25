<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Schema;

interface SchemaConverterInterface
{
    /**
     * @param string $configurationFilePath
     * @param string $openapiFilePath
     *
     * @return bool
     */
    public function convertConfigurationToSchemaFile(string $configurationFilePath, string $openapiFilePath): bool;

    /**
     * @param string $configurationFilePath
     *
     * @return string
     */
    public function convertConfigurationToSchemaJson(string $configurationFilePath): string;
}
