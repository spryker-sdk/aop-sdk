<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator\Manifest\Validator;

use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;
use SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface;

class RequiredFieldsFileValidator implements FileValidatorInterface
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
        foreach ($this->config->getRequiredManifestFieldNames() as $requiredManifestFieldName) {
            if (!isset($data[$requiredManifestFieldName])) {
                $validateResponse->addError(sprintf('Field "%s" must be present in the manifest file "%s" but was not found.', $requiredManifestFieldName, $fileName));
            }
        }

        return $validateResponse;
    }
}
