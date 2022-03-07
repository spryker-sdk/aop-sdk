<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator\AsyncApi;

use Behat\Gherkin\Exception\ParserException;
use Exception;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidateRequestTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\Zed\AppSdk\Business\Validator\AbstractValidator;
use Symfony\Component\Yaml\Yaml;

class AsyncApiValidator extends AbstractValidator
{
    /**
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer|null $validateResponseTransfer
     *
     * @throws ParserException
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validate(
        ValidateRequestTransfer $validateRequestTransfer,
        ?ValidateResponseTransfer $validateResponseTransfer = null
    ): ValidateResponseTransfer {
        $validateResponseTransfer ??= new ValidateResponseTransfer();

        if (!$this->finder->hasFiles($validateRequestTransfer->getAsyncApiPathOrFail())) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage('No asyncApi files found.');
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        try {
            Yaml::parseFile($validateRequestTransfer->getAsyncApiPathOrFail());
            $validateResponseTransfer = $this->validateFileData(Yaml::parseFile($validateRequestTransfer->getAsyncApiPathOrFail()), 'asyncapi.yml', $validateResponseTransfer);
        } catch (Exception $e) {
            throw new ParserException(
                sprintf('AsyncApi file contains invalid Schema. Error: "%s".', $e->getMessage()),
                0,
                $e,
            );
        }

        return $validateResponseTransfer;
    }
}
