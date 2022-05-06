<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Aop;

use SprykerSdk\Aop\Configuration\Builder\AppConfigurationBuilder;
use SprykerSdk\Aop\Configuration\Builder\AppConfigurationBuilderInterface;
use SprykerSdk\Aop\Manifest\Builder\AppManifestBuilder;
use SprykerSdk\Aop\Manifest\Builder\AppManifestBuilderInterface;
use SprykerSdk\Aop\ReadinessChecker\Checker\CheckerInterface;
use SprykerSdk\Aop\ReadinessChecker\Checker\ComposerChecker;
use SprykerSdk\Aop\ReadinessChecker\Checker\EnvChecker;
use SprykerSdk\Aop\ReadinessChecker\Checker\PluginsChecker;
use SprykerSdk\Aop\ReadinessChecker\ReadinessChecker;
use SprykerSdk\Aop\ReadinessChecker\ReadinessCheckerInterface;
use SprykerSdk\Aop\ReadinessChecker\RecipeLoader\RecipeLoader;
use SprykerSdk\Aop\ReadinessChecker\RecipeLoader\RecipeLoaderInterface;
use SprykerSdk\Aop\Translation\Builder\AppTranslationBuilder;
use SprykerSdk\Aop\Translation\Builder\AppTranslationBuilderInterface;
use SprykerSdk\Aop\Validator\Configuration\AppConfigurationValidator;
use SprykerSdk\Aop\Validator\FileValidatorInterface;
use SprykerSdk\Aop\Validator\Finder\Finder;
use SprykerSdk\Aop\Validator\Finder\FinderInterface;
use SprykerSdk\Aop\Validator\Manifest\AppManifestValidator;
use SprykerSdk\Aop\Validator\Manifest\Validator\PagesFileValidator;
use SprykerSdk\Aop\Validator\Manifest\Validator\RequiredFieldsFileValidator;
use SprykerSdk\Aop\Validator\Translation\AppTranslationValidator;
use SprykerSdk\Aop\Validator\Translation\Validator\TranslationFileValidator;
use SprykerSdk\Aop\Validator\Validator;
use SprykerSdk\Aop\Validator\ValidatorInterface;

class AopFactory
{
    /**
     * @var \SprykerSdk\Aop\AopConfig|null
     */
    protected ?AopConfig $config = null;

    /**
     * @param \SprykerSdk\Aop\AopConfig $config
     *
     * @return void
     */
    public function setConfig(AopConfig $config): void
    {
        $this->config = $config;
    }

    /**
     * @return \SprykerSdk\Aop\AopConfig
     */
    protected function getConfig(): AopConfig
    {
        if (!$this->config) {
            $this->config = new AopConfig();
        }

        return $this->config;
    }

    /**
     * @return \SprykerSdk\Aop\Validator\Validator
     */
    public function createValidator(): Validator
    {
        return new Validator([
            $this->createAppManifestValidator(),
            $this->createAppConfigurationValidator(),
            $this->createAppTranslationValidator(),
        ]);
    }

    /**
     * @return \SprykerSdk\Aop\Validator\ValidatorInterface
     */
    public function createAppManifestValidator(): ValidatorInterface
    {
        return new AppManifestValidator(
            $this->getConfig(),
            $this->createFinder(),
            $this->getManifestFileValidators(),
        );
    }

    /**
     * @return array<\SprykerSdk\Aop\Validator\FileValidatorInterface>
     */
    protected function getManifestFileValidators(): array
    {
        return [
            $this->createManifestRequiredFieldsFileValidator(),
            $this->createManifestPagesFileValidator(),
        ];
    }

    /**
     * @return \SprykerSdk\Aop\Validator\FileValidatorInterface
     */
    protected function createManifestRequiredFieldsFileValidator(): FileValidatorInterface
    {
        return new RequiredFieldsFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Aop\Validator\FileValidatorInterface
     */
    protected function createManifestPagesFileValidator(): FileValidatorInterface
    {
        return new PagesFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Aop\Validator\ValidatorInterface
     */
    public function createAppConfigurationValidator(): ValidatorInterface
    {
        return new AppConfigurationValidator(
            $this->getConfig(),
            $this->createFinder(),
        );
    }

    /**
     * @return \SprykerSdk\Aop\Validator\Finder\FinderInterface
     */
    protected function createFinder(): FinderInterface
    {
        return new Finder();
    }

    /**
     * @return \SprykerSdk\Aop\Validator\ValidatorInterface
     */
    public function createAppTranslationValidator(): ValidatorInterface
    {
        return new AppTranslationValidator(
            $this->getConfig(),
            $this->createFinder(),
            $this->getTranslationFileValidators(),
        );
    }

    /**
     * @return array<\SprykerSdk\Aop\Validator\FileValidatorInterface>
     */
    protected function getTranslationFileValidators(): array
    {
        return [
            $this->createTranslationFileValidator(),
        ];
    }

    /**
     * @return \SprykerSdk\Aop\Validator\FileValidatorInterface
     */
    protected function createTranslationFileValidator(): FileValidatorInterface
    {
        return new TranslationFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Aop\ReadinessChecker\ReadinessCheckerInterface
     */
    public function createReadinessChecker(): ReadinessCheckerInterface
    {
        return new ReadinessChecker($this->createRecipeLoader(), $this->getReadinessChecker());
    }

    /**
     * @return array<\SprykerSdk\Aop\ReadinessChecker\Checker\CheckerInterface>
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
     * @return \SprykerSdk\Aop\ReadinessChecker\Checker\CheckerInterface
     */
    public function createComposerChecker(): CheckerInterface
    {
        return new ComposerChecker();
    }

    /**
     * @return \SprykerSdk\Aop\ReadinessChecker\Checker\CheckerInterface
     */
    public function createPluginsChecker(): CheckerInterface
    {
        return new PluginsChecker();
    }

    /**
     * @return \SprykerSdk\Aop\ReadinessChecker\Checker\CheckerInterface
     */
    public function createEnvChecker(): CheckerInterface
    {
        return new EnvChecker();
    }

    /**
     * @return \SprykerSdk\Aop\ReadinessChecker\RecipeLoader\RecipeLoaderInterface
     */
    public function createRecipeLoader(): RecipeLoaderInterface
    {
        return new RecipeLoader($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Aop\Manifest\Builder\AppManifestBuilderInterface
     */
    public function createAppManifestBuilder(): AppManifestBuilderInterface
    {
        return new AppManifestBuilder();
    }

    /**
     * @return \SprykerSdk\Aop\Configuration\Builder\AppConfigurationBuilderInterface
     */
    public function createAppConfigurationBuilder(): AppConfigurationBuilderInterface
    {
        return new AppConfigurationBuilder();
    }

    /**
     * @return \SprykerSdk\Aop\Translation\Builder\AppTranslationBuilderInterface
     */
    public function createAppTranslationBuilder(): AppTranslationBuilderInterface
    {
        return new AppTranslationBuilder();
    }
}
