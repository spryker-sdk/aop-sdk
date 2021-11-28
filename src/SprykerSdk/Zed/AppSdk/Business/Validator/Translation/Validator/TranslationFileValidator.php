<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator\Translation\Validator;

use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;
use SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface;

class TranslationFileValidator implements FileValidatorInterface
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
        $locales = $context['locales'] ?? [];

        foreach ($data as $translationKey => $translationValues) {
            $validateResponse = $this->validateTranslationValuesExistForLocales($translationKey, $translationValues, $locales, $validateResponse);
        }

        return $validateResponse;
    }

    /**
     * @param string $translationKey
     * @param array $translationValues
     * @param array $locales
     * @param \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface $validateResponse
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    protected function validateTranslationValuesExistForLocales(
        string $translationKey,
        array $translationValues,
        array $locales,
        ValidateResponseInterface $validateResponse
    ): ValidateResponseInterface {
        foreach ($locales as $locale) {
            if (!isset($translationValues[$locale])) {
                $validateResponse->addError(sprintf('Missing translation for key "%s" and locale "%s".', $translationKey, $locale));
            }
        }

        return $validateResponse;
    }
}
