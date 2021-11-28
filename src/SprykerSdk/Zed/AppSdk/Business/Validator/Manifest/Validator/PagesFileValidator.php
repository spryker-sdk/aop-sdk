<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator\Manifest\Validator;

use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;
use SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface;

class PagesFileValidator implements FileValidatorInterface
{
    /**
     * @var \SprykerSdk\Zed\AppSdk\AppSdkConfig
     */
    protected AppSdkConfig $config;

    /**
     * @param \SprykerSdk\Zed\AppSdk\AppSdkConfig $config
     */
    public function __construct(AppSdkConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $data
     * @param string $fileName
     * @param \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface $validateResponse
     * @param array|null $context
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    public function validate(array $data, string $fileName, ValidateResponseInterface $validateResponse, ?array $context = null): ValidateResponseInterface
    {
        foreach ($data['pages'] as $pageName => $page) {
            $validateResponse = $this->validatePage($page, $pageName, $fileName, $validateResponse);
        }

        return $validateResponse;
    }

    /**
     * @param array $page
     * @param string $pageName
     * @param string $manifestFileName
     * @param \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface $validateResponse
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    protected function validatePage(
        array $page,
        string $pageName,
        string $manifestFileName,
        ValidateResponseInterface $validateResponse
    ): ValidateResponseInterface {
        foreach ($page as $pageBlock) {
            $validateResponse = $this->validatePageBlockRequiredFields($pageBlock, $pageName, $manifestFileName, $validateResponse);
            $validateResponse = $this->validatePageBlockType($pageBlock, $pageName, $manifestFileName, $validateResponse);
        }

        return $validateResponse;
    }

    /**
     * @param array $pageBlock
     * @param string $pageName
     * @param string $manifestFileName
     * @param \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface $validateResponse
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    protected function validatePageBlockRequiredFields(
        array $pageBlock,
        string $pageName,
        string $manifestFileName,
        ValidateResponseInterface $validateResponse
    ): ValidateResponseInterface {
        $requiredManifestPageBlockFields = $this->config->getRequiredManifestPageBlockFields();

        foreach ($requiredManifestPageBlockFields as $requiredManifestPageBlockField) {
            if (!isset($pageBlock[$requiredManifestPageBlockField])) {
                $validateResponse->addError(sprintf('Page block field "%s" in page "%s" must be present in the manifest file "%s" but was not found.', $requiredManifestPageBlockField, $pageName, $manifestFileName));
            }
        }

        return $validateResponse;
    }

    /**
     * @param array $pageBlock
     * @param string $pageName
     * @param string $manifestFileName
     * @param \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface $validateResponse
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    protected function validatePageBlockType(
        array $pageBlock,
        string $pageName,
        string $manifestFileName,
        ValidateResponseInterface $validateResponse
    ): ValidateResponseInterface {
        if (!isset($pageBlock['type'])) { // Validation already done in `validatePageBlockRequiredFields()`, no additional error message needed.
            return $validateResponse;
        }

        $allowedManifestPageBlockTypes = $this->config->getAllowedManifestPageTypes();

        if (!in_array($pageBlock['type'], $allowedManifestPageBlockTypes)) {
            $validateResponse->addError(sprintf(
                'Page block type "%s" not allowed in page "%s" in the manifest file "%s".',
                $pageBlock['type'],
                $pageName,
                $manifestFileName,
            ));
        }

        return $validateResponse;
    }
}
