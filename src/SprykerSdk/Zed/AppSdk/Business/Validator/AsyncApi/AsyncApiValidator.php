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
     * @throws \Behat\Gherkin\Exception\ParserException
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validate(
        ValidateRequestTransfer $validateRequestTransfer,
        ?ValidateResponseTransfer $validateResponseTransfer = null
    ): ValidateResponseTransfer {
        $validateResponseTransfer ??= new ValidateResponseTransfer();
        $asyncApiFile = $validateRequestTransfer->getAsyncApiFileOrFail();
        if (!$this->finder->hasFiles($asyncApiFile)) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage('No AsyncAPI file given, you need to pass a valid filename.');
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        try {
            $validateResponseTransfer = $this->validateFileData(Yaml::parseFile($asyncApiFile), $this->finder->getFile($asyncApiFile)->getFilename(), $validateResponseTransfer);
        } catch (Exception $e) {
            throw new ParserException(
                sprintf('Could not parse AsyncApi file. Error: "%s".', $e->getMessage()),
                0,
                $e,
            );
        }

        return $validateResponseTransfer;
    }
}
