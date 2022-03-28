<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Translation\Builder;

use Generated\Shared\Transfer\AppTranslationRequestTransfer;
use Generated\Shared\Transfer\AppTranslationResponseTransfer;

class AppTranslationBuilder implements AppTranslationBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AppTranslationRequestTransfer $appTranslationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AppTranslationResponseTransfer
     */
    public function appTranslationCreate(AppTranslationRequestTransfer $appTranslationRequestTransfer): AppTranslationResponseTransfer
    {
        $appTranslationResponseTransfer = new AppTranslationResponseTransfer();

        $this->writeToFile(
            $appTranslationRequestTransfer->getTranslationFileOrFail(),
            $appTranslationRequestTransfer->getTranslations(),
        );

        return $appTranslationResponseTransfer;
    }

    /**
     * @param string $targetFile
     * @param array $translations
     *
     * @return void
     */
    protected function writeToFile(string $targetFile, array $translations): void
    {
        $dirname = dirname($targetFile);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0770, true);
        }

        file_put_contents($targetFile, json_encode($translations, JSON_PRETTY_PRINT));
    }
}
