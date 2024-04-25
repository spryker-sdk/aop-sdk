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

class BusinessModesValidator implements FileValidatorInterface
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
        ?array $context = null,
    ): ValidateResponseTransfer {
        if (!isset($data['businessModels'])) {
            return $validateResponseTransfer;
        }
        $allowedManifestBusinessModels = $this->config->getAllowedManifestBusinessModels();
        foreach ($data['businessModels'] as $businessModel) {
            if (!in_array($businessModel, $allowedManifestBusinessModels)) {
                $messageTransfer = new MessageTransfer();
                $messageTransfer->setMessage(sprintf(
                    'The business model with "%s" name not allowed in the manifest file "%s".',
                    $businessModel,
                    $fileName,
                ));
                $validateResponseTransfer->addError($messageTransfer);
            }
        }

        return $validateResponseTransfer;
    }
}
