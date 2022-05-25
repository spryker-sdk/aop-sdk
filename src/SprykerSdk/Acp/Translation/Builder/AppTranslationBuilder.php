<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Translation\Builder;

use Error;
use Exception;
use Transfer\AppTranslationRequestTransfer;
use Transfer\AppTranslationResponseTransfer;
use Transfer\MessageTransfer;

class AppTranslationBuilder implements AppTranslationBuilderInterface
{
    /**
     * @param \Transfer\AppTranslationRequestTransfer $appTranslationRequestTransfer
     *
     * @return \Transfer\AppTranslationResponseTransfer
     */
    public function createTranslation(AppTranslationRequestTransfer $appTranslationRequestTransfer): AppTranslationResponseTransfer
    {
        $appTranslationResponseTransfer = new AppTranslationResponseTransfer();

        try {
            $this->writeToFile(
                $appTranslationRequestTransfer->getTranslationFileOrFail(),
                $appTranslationRequestTransfer->getTranslations(),
            );
        } catch (Exception | Error $error) {
            $appTranslationResponseTransfer->addError(
                (new MessageTransfer())->setMessage(
                    sprintf('<error>Could not write to file. Details: "%s"</error>', $error->getMessage()),
                ),
            );

            return $appTranslationResponseTransfer;
        }

        $successMessage = (new MessageTransfer())
            ->setMessage(sprintf(
                '<info>We stored the configuration in</info> <comment>%s</comment>',
                $appTranslationRequestTransfer->getTranslationFile(),
            ));
        $appTranslationResponseTransfer->addMessage($successMessage);

        return $appTranslationResponseTransfer;
    }

    /**
     * @param string $targetFile
     * @param array $translations
     *
     * @return bool
     */
    protected function writeToFile(string $targetFile, array $translations): bool
    {
        $dirname = dirname($targetFile);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0770, true);
        }

        return (bool)file_put_contents($targetFile, json_encode($translations, JSON_PRETTY_PRINT));
    }
}
