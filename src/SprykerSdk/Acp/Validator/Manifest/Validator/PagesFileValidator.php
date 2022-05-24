<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Validator\Manifest\Validator;

use SprykerSdk\Acp\AcpConfig;
use SprykerSdk\Acp\Validator\FileValidatorInterface;
use Transfer\MessageTransfer;
use Transfer\ValidateResponseTransfer;

class PagesFileValidator implements FileValidatorInterface
{
    /**
     * @var \SprykerSdk\Acp\AcpConfig
     */
    protected AcpConfig $config;

    /**
     * @param \SprykerSdk\Acp\AcpConfig $config
     */
    public function __construct(AcpConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $data
     * @param string $fileName
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     * @param array|null $context
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validate(
        array $data,
        string $fileName,
        ValidateResponseTransfer $validateResponseTransfer,
        ?array $context = null
    ): ValidateResponseTransfer {
        foreach ($data['pages'] as $pageName => $page) {
            $validateResponseTransfer = $this->validatePage($page, $pageName, $fileName, $validateResponseTransfer);
        }

        return $validateResponseTransfer;
    }

    /**
     * @param array $page
     * @param string $pageName
     * @param string $manifestFileName
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    protected function validatePage(
        array $page,
        string $pageName,
        string $manifestFileName,
        ValidateResponseTransfer $validateResponseTransfer
    ): ValidateResponseTransfer {
        foreach ($page as $pageBlock) {
            $validateResponseTransfer = $this->validatePageBlockRequiredFields($pageBlock, $pageName, $manifestFileName, $validateResponseTransfer);
            $validateResponseTransfer = $this->validatePageBlockType($pageBlock, $pageName, $manifestFileName, $validateResponseTransfer);
        }

        return $validateResponseTransfer;
    }

    /**
     * @param array $pageBlock
     * @param string $pageName
     * @param string $manifestFileName
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    protected function validatePageBlockRequiredFields(
        array $pageBlock,
        string $pageName,
        string $manifestFileName,
        ValidateResponseTransfer $validateResponseTransfer
    ): ValidateResponseTransfer {
        $requiredManifestPageBlockFields = $this->config->getRequiredManifestPageBlockFields();

        foreach ($requiredManifestPageBlockFields as $requiredManifestPageBlockField) {
            if (!isset($pageBlock[$requiredManifestPageBlockField])) {
                $messageTransfer = new MessageTransfer();
                $messageTransfer->setMessage(sprintf('Page block field "%s" in page "%s" must be present in the manifest file "%s" but was not found.', $requiredManifestPageBlockField, $pageName, $manifestFileName));
                $validateResponseTransfer->addError($messageTransfer);
            }
        }

        return $validateResponseTransfer;
    }

    /**
     * @param array $pageBlock
     * @param string $pageName
     * @param string $manifestFileName
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    protected function validatePageBlockType(
        array $pageBlock,
        string $pageName,
        string $manifestFileName,
        ValidateResponseTransfer $validateResponseTransfer
    ): ValidateResponseTransfer {
        if (!isset($pageBlock['type'])) { // Validation already done in `validatePageBlockRequiredFields()`, no additional error message needed.
            return $validateResponseTransfer;
        }

        $allowedManifestPageBlockTypes = $this->config->getAllowedManifestPageTypes();

        if (!in_array($pageBlock['type'], $allowedManifestPageBlockTypes)) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf(
                'Page block type "%s" not allowed in page "%s" in the manifest file "%s".',
                $pageBlock['type'],
                $pageName,
                $manifestFileName,
            ));
            $validateResponseTransfer->addError($messageTransfer);
        }

        return $validateResponseTransfer;
    }
}
