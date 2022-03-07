<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use org\bovigo\vfs\vfsStream;
class AppAsyncApiValidatorHelper extends Module
{
    /**
     * @param string $targetFile
     * @return void
     */
    public function haveInvalidAsyncApiFileWithDuplicatedMessageNames(string $targetFile): void
    {
        dd($asyncApi);
        $asyncApi = Yaml::parseFile($targetFile);
    }
}
