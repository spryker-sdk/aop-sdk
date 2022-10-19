<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Configuration\SchemaGenerator;

use cebe\openapi\spec\OpenApi;

interface SchemaWriterInterface
{
    /**
     * @param \cebe\openapi\spec\OpenApi $schema
     * @param string $filePath
     *
     * @return bool
     */
    public function writeSchemaToFile(OpenApi $schema, string $filePath): bool;
}
