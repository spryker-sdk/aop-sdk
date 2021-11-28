<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator\Manifest;

use SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponse;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;
use SprykerSdk\Zed\AppSdk\Business\Validator\AbstractValidator;

class ManifestValidator extends AbstractValidator
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

        if (!$this->finder->hasFiles($validateRequest->getManifestPath())) {
            $validateResponse->addError('No manifest files found.');

            return $validateResponse;
        }

        foreach ($this->finder->getFiles($validateRequest->getManifestPath()) as $manifestFile) {
            $manifestData = json_decode((string)file_get_contents($manifestFile->getPathname()), true);

            if (json_last_error()) {
                $validateResponse->addError(sprintf('Manifest file "%s" contains invalid JSON. Error: "%s".', $manifestFile->getPathname(), json_last_error_msg()));

                continue;
            }

            $validateResponse = $this->validateFileData($manifestData, $manifestFile->getFilename(), $validateResponse);
        }

        return $validateResponse;
    }
}
