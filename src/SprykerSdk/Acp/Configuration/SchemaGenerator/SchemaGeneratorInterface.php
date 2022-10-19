<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Configuration\SchemaGenerator;

interface SchemaGeneratorInterface
{
    /**
     * @param string $configurationFilePath
     * @param string $openapiFilePath
     *
     * @return bool
     */
    public function convertConfigurationToSchema(string $configurationFilePath, string $openapiFilePath): bool;
}
