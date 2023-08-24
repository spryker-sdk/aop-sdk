<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Validator;

use SprykerSdk\Acp\AcpConfig;
use SprykerSdk\Acp\Validator\Finder\FinderInterface;
use Transfer\ValidateResponseTransfer;

abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @var \SprykerSdk\Acp\AcpConfig
     */
    protected AcpConfig $config;

    /**
     * @var \SprykerSdk\Acp\Validator\Finder\FinderInterface
     */
    protected FinderInterface $finder;

    /**
     * @var array<\SprykerSdk\Acp\Validator\FileValidatorInterface>
     */
    protected array $fileValidators;

    /**
     * @param \SprykerSdk\Acp\AcpConfig $config
     * @param \SprykerSdk\Acp\Validator\Finder\FinderInterface $finder
     * @param array $fileValidators
     */
    public function __construct(AcpConfig $config, FinderInterface $finder, array $fileValidators = [])
    {
        $this->config = $config;
        $this->finder = $finder;
        $this->fileValidators = $fileValidators;
    }

    /**
     * @param array $fileData
     * @param string $fileName
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     * @param array|null $context
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    protected function validateFileData(
        array $fileData,
        string $fileName,
        ValidateResponseTransfer $validateResponseTransfer,
        ?array $context = null,
    ): ValidateResponseTransfer {
        foreach ($this->fileValidators as $fileValidator) {
            $validateResponseTransfer = $fileValidator->validate($fileData, $fileName, $validateResponseTransfer, $context);
        }

        return $validateResponseTransfer;
    }
}
