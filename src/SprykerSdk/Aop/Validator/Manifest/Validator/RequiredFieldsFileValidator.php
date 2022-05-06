<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Aop\Validator\Manifest\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\Aop\AopConfig;
use SprykerSdk\Aop\Validator\FileValidatorInterface;

class RequiredFieldsFileValidator implements FileValidatorInterface
{
    /**
     * @var \SprykerSdk\Aop\AopConfig
     */
    protected AopConfig $config;

    /**
     * @param \SprykerSdk\Aop\AopConfig $config
     */
    public function __construct(AopConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $data
     * @param string $fileName
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer $validateResponseTransfer
     * @param array|null $context
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validate(
        array $data,
        string $fileName,
        ValidateResponseTransfer $validateResponseTransfer,
        ?array $context = null
    ): ValidateResponseTransfer {
        foreach ($this->config->getRequiredManifestFieldNames() as $requiredManifestFieldName) {
            if (!isset($data[$requiredManifestFieldName])) {
                $messageTransfer = new MessageTransfer();
                $messageTransfer->setMessage(sprintf('Field "%s" must be present in the manifest file "%s" but was not found.', $requiredManifestFieldName, $fileName));
                $validateResponseTransfer->addError($messageTransfer);
            }
        }

        return $validateResponseTransfer;
    }
}
