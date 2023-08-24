<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Validator\Manifest;

use SprykerSdk\Acp\Validator\AbstractValidator;
use Transfer\MessageTransfer;
use Transfer\ValidateRequestTransfer;
use Transfer\ValidateResponseTransfer;

class AppManifestValidator extends AbstractValidator
{
    /**
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     * @param \Transfer\ValidateResponseTransfer|null $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validate(
        ValidateRequestTransfer $validateRequestTransfer,
        ?ValidateResponseTransfer $validateResponseTransfer = null,
    ): ValidateResponseTransfer {
        $validateResponseTransfer ??= new ValidateResponseTransfer();

        if (!$this->finder->hasFiles($validateRequestTransfer->getManifestPathOrFail())) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage('No manifest files found.');
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        foreach ($this->finder->getFiles($validateRequestTransfer->getManifestPathOrFail()) as $manifestFile) {
            $manifestData = json_decode((string)file_get_contents($manifestFile->getPathname()), true);

            if (json_last_error()) {
                $messageTransfer = new MessageTransfer();
                $messageTransfer->setMessage(sprintf('Manifest file "%s" contains invalid JSON. Error: "%s".', $manifestFile->getPathname(), json_last_error_msg()));
                $validateResponseTransfer->addError($messageTransfer);

                continue;
            }

            $validateResponseTransfer = $this->validateFileData($manifestData, $manifestFile->getFilename(), $validateResponseTransfer);
        }

        if ($validateResponseTransfer->getErrors()->count() === 0) {
            $validateResponseTransfer->addMessage((new MessageTransfer())->setMessage(sprintf('No errors found for manifest files in "%s".', $validateRequestTransfer->getManifestPathOrFail())));
        }

        return $validateResponseTransfer;
    }
}
