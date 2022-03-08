<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator\AsyncApi\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface;

class OperationIdValidator implements FileValidatorInterface
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
        if (isset($data['components']['messages'])) {
            foreach ($data['components']['messages'] as $message) {
                if (!isset($message['operationId'])) {
                    $messageTransfer = new MessageTransfer();
                    $messageTransfer->setMessage(sprintf('Async API file "%s" have missing operation ids. Missing Messages: "%s".', $this->config->getDefaultAsyncApiFile(), $message['name']));
                    $validateResponseTransfer->addError($messageTransfer);
                }
            }
        } else {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf('Async API file "%s" does not contain messages', $this->config->getDefaultAsyncApiFile()));
            $validateResponseTransfer->addError($messageTransfer);
        }

        return $validateResponseTransfer;
    }
}
