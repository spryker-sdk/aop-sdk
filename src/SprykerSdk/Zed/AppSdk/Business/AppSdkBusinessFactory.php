<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\AsyncApiBuilder;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\AsyncApiBuilderInterface;
use SprykerSdk\Zed\AppSdk\Business\Validator\Configuration\ConfigurationValidator;
use SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface;
use SprykerSdk\Zed\AppSdk\Business\Validator\Finder\Finder;
use SprykerSdk\Zed\AppSdk\Business\Validator\Finder\FinderInterface;
use SprykerSdk\Zed\AppSdk\Business\Validator\Manifest\ManifestValidator;
use SprykerSdk\Zed\AppSdk\Business\Validator\Manifest\Validator\PagesFileValidator;
use SprykerSdk\Zed\AppSdk\Business\Validator\Manifest\Validator\RequiredFieldsFileValidator;
use SprykerSdk\Zed\AppSdk\Business\Validator\Translation\TranslationValidator;
use SprykerSdk\Zed\AppSdk\Business\Validator\Translation\Validator\TranslationFileValidator;
use SprykerSdk\Zed\AppSdk\Business\Validator\Validator;
use SprykerSdk\Zed\AppSdk\Business\Validator\ValidatorInterface;

/**
 * @method \SprykerSdk\Zed\AppSdk\AppSdkConfig getConfig()
 */
class AppSdkBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\Validator\Validator
     */
    public function createValidator(): Validator
    {
        return new Validator([
            $this->createManifestValidator(),
            $this->createConfigurationValidator(),
            $this->createTranslationValidator(),
        ]);
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\Validator\ValidatorInterface
     */
    public function createManifestValidator(): ValidatorInterface
    {
        return new ManifestValidator(
            $this->getConfig(),
            $this->createFinder(),
            $this->getManifestFileValidators(),
        );
    }

    /**
     * @return array<\SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface>
     */
    protected function getManifestFileValidators(): array
    {
        return [
            $this->createManifestRequiredFieldsFileValidator(),
            $this->createManifestPagesFileValidator(),
        ];
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface
     */
    protected function createManifestRequiredFieldsFileValidator(): FileValidatorInterface
    {
        return new RequiredFieldsFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface
     */
    protected function createManifestPagesFileValidator(): FileValidatorInterface
    {
        return new PagesFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\Validator\ValidatorInterface
     */
    public function createConfigurationValidator(): ValidatorInterface
    {
        return new ConfigurationValidator(
            $this->getConfig(),
            $this->createFinder(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\Validator\Finder\FinderInterface
     */
    protected function createFinder(): FinderInterface
    {
        return new Finder();
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\Validator\ValidatorInterface
     */
    public function createTranslationValidator(): ValidatorInterface
    {
        return new TranslationValidator(
            $this->getConfig(),
            $this->createFinder(),
            $this->getTranslationFileValidators(),
        );
    }

    /**
     * @return array<\SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface>
     */
    protected function getTranslationFileValidators(): array
    {
        return [
            $this->createTranslationFileValidator(),
        ];
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\Validator\FileValidatorInterface
     */
    protected function createTranslationFileValidator(): FileValidatorInterface
    {
        return new TranslationFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\AsyncApi\AsyncApiBuilderInterface
     */
    public function createAsyncApiBuilder(): AsyncApiBuilderInterface
    {
        return new AsyncApiBuilder();
    }
}
