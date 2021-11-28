<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Validator;

use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface;
use SprykerSdk\Zed\AppSdk\Business\Validator\Finder\FinderInterface;

abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @var \SprykerSdk\Zed\AppSdk\AppSdkConfig
     */
    protected AppSdkConfig $config;

    /**
     * @var \SprykerSdk\Zed\AppSdk\Business\Validator\Finder\FinderInterface
     */
    protected FinderInterface $finder;

    /**
     * @var array<\SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface>
     */
    protected array $fileValidators;

    /**
     * @param \SprykerSdk\Zed\AppSdk\AppSdkConfig $config
     * @param \SprykerSdk\Zed\AppSdk\Business\Validator\Finder\FinderInterface $finder
     * @param array $fileValidators
     */
    public function __construct(AppSdkConfig $config, FinderInterface $finder, array $fileValidators = [])
    {
        $this->config = $config;
        $this->finder = $finder;
        $this->fileValidators = $fileValidators;
    }

    /**
     * @param array $fileData
     * @param string $fileName
     * @param \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface $validateResponse
     * @param array|null $context
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\Response\ValidateResponseInterface
     */
    protected function validateFileData(
        array $fileData,
        string $fileName,
        ValidateResponseInterface $validateResponse,
        ?array $context = null
    ): ValidateResponseInterface {
        foreach ($this->fileValidators as $fileValidator) {
            $validateResponse = $fileValidator->validate($fileData, $fileName, $validateResponse, $context);
        }

        return $validateResponse;
    }
}
