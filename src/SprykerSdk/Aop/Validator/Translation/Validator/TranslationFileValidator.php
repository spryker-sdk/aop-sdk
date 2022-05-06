<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Aop\Validator\Translation\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\Aop\AopConfig;
use SprykerSdk\Aop\Validator\FileValidatorInterface;

class TranslationFileValidator implements FileValidatorInterface
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
        $locales = $context['locales'] ?? [];

        foreach ($data as $translationKey => $translationValues) {
            $validateResponseTransfer = $this->validateTranslationValuesExistForLocales($translationKey, $translationValues, $locales, $validateResponseTransfer);
        }

        return $validateResponseTransfer;
    }

    /**
     * @param string $translationKey
     * @param array $translationValues
     * @param array $locales
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    protected function validateTranslationValuesExistForLocales(
        string $translationKey,
        array $translationValues,
        array $locales,
        ValidateResponseTransfer $validateResponseTransfer
    ): ValidateResponseTransfer {
        foreach ($locales as $locale) {
            if (!isset($translationValues[$locale])) {
                $messageTransfer = new MessageTransfer();
                $messageTransfer->setMessage(sprintf('Missing translation for key "%s" and locale "%s".', $translationKey, $locale));
                $validateResponseTransfer->addError($messageTransfer);
            }
        }

        return $validateResponseTransfer;
    }
}
