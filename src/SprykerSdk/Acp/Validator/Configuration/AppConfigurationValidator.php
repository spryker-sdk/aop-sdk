<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Validator\Configuration;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidateRequestTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\Acp\Validator\AbstractValidator;

class AppConfigurationValidator extends AbstractValidator
{
    /**
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer|null $validateResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validate(
        ValidateRequestTransfer $validateRequestTransfer,
        ?ValidateResponseTransfer $validateResponseTransfer = null
    ): ValidateResponseTransfer {
        $validateResponseTransfer ??= new ValidateResponseTransfer();

        if (!$this->finder->hasFile($validateRequestTransfer->getConfigurationFileOrFail())) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf('No "%s" file found.', basename($validateRequestTransfer->getConfigurationFileOrFail())));
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        $splFileInfo = $this->finder->getFile($validateRequestTransfer->getConfigurationFileOrFail());

        $fileData = json_decode((string)file_get_contents($splFileInfo->getPathname()), true);

        if (json_last_error()) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf('Configuration file "%s" contains invalid JSON. Error: "%s".', $splFileInfo->getPathname(), json_last_error_msg()));
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        $validateResponseTransfer = $this->validateFileData($fileData, $splFileInfo->getFilename(), $validateResponseTransfer);

        if ($validateResponseTransfer->getErrors()->count() === 0) {
            $validateResponseTransfer->addMessage((new MessageTransfer())->setMessage(sprintf('No errors found in "%s".', $validateRequestTransfer->getConfigurationFileOrFail())));
        }

        return $validateResponseTransfer;
    }
}
