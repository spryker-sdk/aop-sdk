<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AopSdk\Business\Validator\Manifest;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidateRequestTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\Zed\AopSdk\Business\Validator\AbstractValidator;

class AppManifestValidator extends AbstractValidator
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

        return $validateResponseTransfer;
    }
}
