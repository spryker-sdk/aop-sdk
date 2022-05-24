<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Validator\Translation\Validator;

use SprykerSdk\Acp\AcpConfig;
use SprykerSdk\Acp\Validator\FileValidatorInterface;
use Transfer\MessageTransfer;
use Transfer\ValidateResponseTransfer;

class TranslationFileValidator implements FileValidatorInterface
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
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
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
