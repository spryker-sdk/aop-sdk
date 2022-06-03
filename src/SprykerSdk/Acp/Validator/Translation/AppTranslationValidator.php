<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Validator\Translation;

use SprykerSdk\Acp\Validator\AbstractValidator;
use Transfer\MessageTransfer;
use Transfer\ValidateRequestTransfer;
use Transfer\ValidateResponseTransfer;

class AppTranslationValidator extends AbstractValidator
{
    /**
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     * @param \Transfer\ValidateResponseTransfer|null $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validate(
        ValidateRequestTransfer $validateRequestTransfer,
        ?ValidateResponseTransfer $validateResponseTransfer = null
    ): ValidateResponseTransfer {
        $validateResponseTransfer ??= new ValidateResponseTransfer();

        if (!$this->finder->hasFile($validateRequestTransfer->getTranslationFile())) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage('No "translation.json" file found.');
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        /** @var \Symfony\Component\Finder\SplFileInfo $splFileInfo */
        $splFileInfo = $this->finder->getFile($validateRequestTransfer->getTranslationFileOrFail());
        $fileData = json_decode((string)file_get_contents($splFileInfo->getPathname()), true);

        if (json_last_error()) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf('Translation file "%s" contains invalid JSON. Error: "%s".', $splFileInfo->getPathname(), json_last_error_msg()));
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        if (!$this->finder->hasFiles($validateRequestTransfer->getManifestPath())) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf('Can not validate the Translation file "%s" without existing manifest file(s).', $splFileInfo->getPathname()));
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        if (!$this->finder->hasFile($validateRequestTransfer->getConfigurationFile())) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf('Can not validate the Translation file "%s" without existing "configuration.json" file.', $splFileInfo->getPathname()));
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        $context = [
            'locales' => $this->getLocalesFromManifestFiles($validateRequestTransfer),
        ];

        $validateResponseTransfer = $this->validateFileData($fileData, $splFileInfo->getFilename(), $validateResponseTransfer, $context);

        if ($validateResponseTransfer->getErrors()->count() === 0) {
            $validateResponseTransfer->addMessage((new MessageTransfer())->setMessage(sprintf('No errors found in "%s".', $validateRequestTransfer->getTranslationFileOrFail())));
        }

        return $validateResponseTransfer;
    }

    /**
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return array
     */
    protected function getLocalesFromManifestFiles(ValidateRequestTransfer $validateRequestTransfer): array
    {
        $manifestFiles = $this->finder->getFiles($validateRequestTransfer->getManifestPathOrFail());
        $extractedLocales = [];

        foreach ($manifestFiles as $manifestFile) {
            $pathInfo = pathinfo($manifestFile->getFilename());
            $extractedLocales[] = $pathInfo['filename'];
        }

        return $extractedLocales;
    }
}
