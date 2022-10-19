<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Configuration\SchemaGenerator;

use cebe\openapi\spec\OpenApi;
use cebe\openapi\Writer;

class SchemaWriter implements SchemaWriterInterface
{
    /**
     * @param \cebe\openapi\spec\OpenApi $schema
     * @param string $filePath
     *
     * @return bool
     */
    public function writeSchemaToFile(OpenApi $schema, string $filePath): bool
    {
        $yamlContents = Writer::writeToYaml($schema);

        return $this->writeToFile($filePath, $yamlContents);
    }

    /**
     * @param string $targetFile
     * @param string $fileContents
     *
     * @return bool
     */
    protected function writeToFile(string $targetFile, string $fileContents): bool
    {
        $dirname = dirname($targetFile);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0770, true);
        }

        return (bool)file_put_contents($targetFile, $fileContents);
    }
}
