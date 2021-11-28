<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator\Configuration;

use SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponse;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;
use SprykerSdk\Zed\AppSdk\Business\Validator\AbstractValidator;

class ConfigurationValidator extends AbstractValidator
{
    /**
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     * @param \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface|null $validateResponse
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validate(ValidateRequestInterface $validateRequest, ?ValidateResponseInterface $validateResponse = null): ValidateResponseInterface
    {
        $validateResponse ??= new ValidateResponse();

        if (!$this->finder->hasFile($validateRequest->getConfigurationFile())) {
            $validateResponse->addError(sprintf('No "%s" file found.', basename($validateRequest->getConfigurationFile())));

            return $validateResponse;
        }

        $splFileInfo = $this->finder->getFile($validateRequest->getConfigurationFile());

        $fileData = json_decode((string)file_get_contents($splFileInfo->getPathname()), true);

        if (json_last_error()) {
            $validateResponse->addError(sprintf('Configuration file "%s" contains invalid JSON. Error: "%s".', $splFileInfo->getPathname(), json_last_error_msg()));

            return $validateResponse;
        }

        return $this->validateFileData($fileData, $splFileInfo->getFilename(), $validateResponse);
    }
}
