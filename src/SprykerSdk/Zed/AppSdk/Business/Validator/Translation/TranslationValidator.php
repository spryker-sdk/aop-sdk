<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator\Translation;

use SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponse;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;
use SprykerSdk\Zed\AppSdk\Business\Validator\AbstractValidator;

class TranslationValidator extends AbstractValidator
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

        if (!$this->finder->hasFile($validateRequest->getTranslationFile())) {
            $validateResponse->addError('No "translation.json" file found.');

            return $validateResponse;
        }

        $splFileInfo = $this->finder->getFile($validateRequest->getTranslationFile());
        $fileData = json_decode((string)file_get_contents($splFileInfo->getPathname()), true);

        if (json_last_error()) {
            $validateResponse->addError(sprintf('Translation file "%s" contains invalid JSON. Error: "%s".', $splFileInfo->getPathname(), json_last_error_msg()));

            return $validateResponse;
        }

        if (!$this->finder->hasFiles($validateRequest->getManifestPath())) {
            $validateResponse->addError(sprintf('Can not validate the Translation file "%s" without existing manifest file(s).', $splFileInfo->getPathname()));

            return $validateResponse;
        }

        if (!$this->finder->hasFile($validateRequest->getConfigurationFile())) {
            $validateResponse->addError(sprintf('Can not validate the Translation file "%s" without existing "configuration.json" file.', $splFileInfo->getPathname()));

            return $validateResponse;
        }

        $context = [
            'locales' => $this->getLocalesFromManifestFiles($validateRequest),
        ];

        return $this->validateFileData($fileData, $splFileInfo->getFilename(), $validateResponse, $context);
    }

    /**
     * @param \SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequestInterface $validateRequest
     *
     * @return array
     */
    protected function getLocalesFromManifestFiles(ValidateRequestInterface $validateRequest): array
    {
        $manifestFiles = $this->finder->getFiles($validateRequest->getManifestPath());
        $extractedLocales = [];

        foreach ($manifestFiles as $manifestFile) {
            $pathInfo = pathinfo($manifestFile->getFilename());
            $extractedLocales[] = $pathInfo['filename'];
        }

        return $extractedLocales;
    }
}
