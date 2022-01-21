<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Builder\AsyncApiBuilder;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Builder\AsyncApiBuilderInterface;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Builder\AsyncApiCodeBuilder;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Builder\AsyncApiCodeBuilderInterface;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Loader\AsyncApiLoaderInterface;
use SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\CheckerInterface;
use SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\ComposerChecker;
use SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\EnvChecker;
use SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\PluginsChecker;
use SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\ReadinessChecker;
use SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\ReadinessCheckerInterface;
use SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\RecipeLoader\RecipeLoader;
use SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\RecipeLoader\RecipeLoaderInterface;
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
     * @return \SprykerSdk\Zed\AppSdk\Business\AsyncApi\Builder\AsyncApiBuilderInterface
     */
    public function createAsyncApiBuilder(): AsyncApiBuilderInterface
    {
        return new AsyncApiBuilder();
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\ReadinessCheckerInterface
     */
    public function createReadinessChecker(): ReadinessCheckerInterface
    {
        return new ReadinessChecker($this->createRecipeLoader(), $this->getReadinessChecker());
    }

    /**
     * @return array<\SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\CheckerInterface>
     */
    public function getReadinessChecker(): array
    {
        return [
            $this->createComposerChecker(),
            $this->createPluginsChecker(),
            $this->createEnvChecker(),
        ];
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\CheckerInterface
     */
    public function createComposerChecker(): CheckerInterface
    {
        return new ComposerChecker();
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\CheckerInterface
     */
    public function createPluginsChecker(): CheckerInterface
    {
        return new PluginsChecker();
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\CheckerInterface
     */
    public function createEnvChecker(): CheckerInterface
    {
        return new EnvChecker();
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\RecipeLoader\RecipeLoaderInterface
     */
    public function createRecipeLoader(): RecipeLoaderInterface
    {
        return new RecipeLoader($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\AsyncApi\Builder\AsyncApiCodeBuilderInterface
     */
    public function createAsyncApiCodeBuilder(): AsyncApiCodeBuilderInterface
    {
        return new AsyncApiCodeBuilder($this->getConfig(), $this->createAsyncApiLoader());
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Business\AsyncApi\Loader\AsyncApiLoaderInterface
     */
    public function createAsyncApiLoader(): AsyncApiLoaderInterface
    {
        return new AsyncApiLoader();
    }
}
