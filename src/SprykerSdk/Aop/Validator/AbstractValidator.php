<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Aop\Validator;

use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\Aop\AopConfig;
use SprykerSdk\Aop\Validator\Finder\FinderInterface;

abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @var \SprykerSdk\Aop\AopConfig
     */
    protected AopConfig $config;

    /**
     * @var \SprykerSdk\Aop\Validator\Finder\FinderInterface
     */
    protected FinderInterface $finder;

    /**
     * @var array<\SprykerSdk\Aop\Validator\FileValidatorInterface>
     */
    protected array $fileValidators;

    /**
     * @param \SprykerSdk\Aop\AopConfig $config
     * @param \SprykerSdk\Aop\Validator\Finder\FinderInterface $finder
     * @param array $fileValidators
     */
    public function __construct(AopConfig $config, FinderInterface $finder, array $fileValidators = [])
    {
        $this->config = $config;
        $this->finder = $finder;
        $this->fileValidators = $fileValidators;
    }

    /**
     * @param array $fileData
     * @param string $fileName
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer $validateResponseTransfer
     * @param array|null $context
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    protected function validateFileData(
        array $fileData,
        string $fileName,
        ValidateResponseTransfer $validateResponseTransfer,
        ?array $context = null
    ): ValidateResponseTransfer {
        foreach ($this->fileValidators as $fileValidator) {
            $validateResponseTransfer = $fileValidator->validate($fileData, $fileName, $validateResponseTransfer, $context);
        }

        return $validateResponseTransfer;
    }
}
