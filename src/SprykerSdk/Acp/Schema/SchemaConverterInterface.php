<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Schema;

use cebe\openapi\spec\OpenApi;

interface SchemaConverterInterface
{
    /**
     * @param string $configurationFilePath
     *
     * @return OpenApi
     */
    public function convertConfigurationToSchema(string $configurationFilePath): OpenApi;
}
