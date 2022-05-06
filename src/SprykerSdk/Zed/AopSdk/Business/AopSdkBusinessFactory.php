<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AopSdk\Business;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerSdk\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdk\AsyncApi\Loader\AsyncApiLoaderInterface;
use SprykerSdk\Zed\AopSdk\Business\AsyncApi\Builder\AsyncApiBuilder;
use SprykerSdk\Zed\AopSdk\Business\AsyncApi\Builder\AsyncApiBuilderInterface;
use SprykerSdk\Zed\AopSdk\Business\AsyncApi\Builder\AsyncApiCodeBuilder;
use SprykerSdk\Zed\AopSdk\Business\AsyncApi\Builder\AsyncApiCodeBuilderInterface;
use SprykerSdk\Zed\AopSdk\Business\Configuration\Builder\AppConfigurationBuilder;
use SprykerSdk\Zed\AopSdk\Business\Configuration\Builder\AppConfigurationBuilderInterface;
use SprykerSdk\Zed\AopSdk\Business\Manifest\Builder\AppManifestBuilder;
use SprykerSdk\Zed\AopSdk\Business\Manifest\Builder\AppManifestBuilderInterface;
use SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder\OpenApiBuilder;
use SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder\OpenApiBuilderInterface;
use SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder\OpenApiCodeBuilder;
use SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder\OpenApiCodeBuilderInterface;
use SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\CheckerInterface;
use SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\ComposerChecker;
use SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\EnvChecker;
use SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\PluginsChecker;
use SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\ReadinessChecker;
use SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\ReadinessCheckerInterface;
use SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\RecipeLoader\RecipeLoader;
use SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\RecipeLoader\RecipeLoaderInterface;
use SprykerSdk\Zed\AopSdk\Business\Translation\Builder\AppTranslationBuilder;
use SprykerSdk\Zed\AopSdk\Business\Translation\Builder\AppTranslationBuilderInterface;
use SprykerSdk\Zed\AopSdk\Business\Validator\AsyncApi\AsyncApiValidator;
use SprykerSdk\Zed\AopSdk\Business\Validator\AsyncApi\Validator\AsyncApiMessageValidator;
use SprykerSdk\Zed\AopSdk\Business\Validator\AsyncApi\Validator\AsyncApiOperationIdValidator;
use SprykerSdk\Zed\AopSdk\Business\Validator\Configuration\AppConfigurationValidator;
use SprykerSdk\Zed\AopSdk\Business\Validator\FileValidatorInterface;
use SprykerSdk\Zed\AopSdk\Business\Validator\Finder\Finder;
use SprykerSdk\Zed\AopSdk\Business\Validator\Finder\FinderInterface;
use SprykerSdk\Zed\AopSdk\Business\Validator\Manifest\AppManifestValidator;
use SprykerSdk\Zed\AopSdk\Business\Validator\Manifest\Validator\PagesFileValidator;
use SprykerSdk\Zed\AopSdk\Business\Validator\Manifest\Validator\RequiredFieldsFileValidator;
use SprykerSdk\Zed\AopSdk\Business\Validator\OpenApi\OpenApiValidator;
use SprykerSdk\Zed\AopSdk\Business\Validator\Translation\AppTranslationValidator;
use SprykerSdk\Zed\AopSdk\Business\Validator\Translation\Validator\TranslationFileValidator;
use SprykerSdk\Zed\AopSdk\Business\Validator\Validator;
use SprykerSdk\Zed\AopSdk\Business\Validator\ValidatorInterface;

/**
 * @method \SprykerSdk\Zed\AopSdk\AopSdkConfig getConfig()
 */
class AopSdkBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\Validator
     */
    public function createValidator(): Validator
    {
        return new Validator([
            $this->createAppManifestValidator(),
            $this->createAppConfigurationValidator(),
            $this->createAppTranslationValidator(),
            $this->createAsyncApiValidator(),
        ]);
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\ValidatorInterface
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
     * @return array<\SprykerSdk\Zed\AopSdk\Business\Validator\FileValidatorInterface>
     */
    protected function getManifestFileValidators(): array
    {
        return [
            $this->createManifestRequiredFieldsFileValidator(),
            $this->createManifestPagesFileValidator(),
        ];
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\FileValidatorInterface
     */
    protected function createManifestRequiredFieldsFileValidator(): FileValidatorInterface
    {
        return new RequiredFieldsFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\FileValidatorInterface
     */
    protected function createManifestPagesFileValidator(): FileValidatorInterface
    {
        return new PagesFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\ValidatorInterface
     */
    public function createAppConfigurationValidator(): ValidatorInterface
    {
        return new AppConfigurationValidator(
            $this->getConfig(),
            $this->createFinder(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\Finder\FinderInterface
     */
    protected function createFinder(): FinderInterface
    {
        return new Finder();
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\ValidatorInterface
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
     * @return array<\SprykerSdk\Zed\AopSdk\Business\Validator\FileValidatorInterface>
     */
    protected function getTranslationFileValidators(): array
    {
        return [
            $this->createTranslationFileValidator(),
        ];
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\FileValidatorInterface
     */
    protected function createTranslationFileValidator(): FileValidatorInterface
    {
        return new TranslationFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\AsyncApi\Builder\AsyncApiBuilderInterface
     */
    public function createAsyncApiBuilder(): AsyncApiBuilderInterface
    {
        return new AsyncApiBuilder();
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\ReadinessCheckerInterface
     */
    public function createReadinessChecker(): ReadinessCheckerInterface
    {
        return new ReadinessChecker($this->createRecipeLoader(), $this->getReadinessChecker());
    }

    /**
     * @return array<\SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\CheckerInterface>
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
     * @return \SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\CheckerInterface
     */
    public function createComposerChecker(): CheckerInterface
    {
        return new ComposerChecker();
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\CheckerInterface
     */
    public function createPluginsChecker(): CheckerInterface
    {
        return new PluginsChecker();
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\CheckerInterface
     */
    public function createEnvChecker(): CheckerInterface
    {
        return new EnvChecker();
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\RecipeLoader\RecipeLoaderInterface
     */
    public function createRecipeLoader(): RecipeLoaderInterface
    {
        return new RecipeLoader($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\AsyncApi\Builder\AsyncApiCodeBuilderInterface
     */
    public function createAsyncApiCodeBuilder(): AsyncApiCodeBuilderInterface
    {
        return new AsyncApiCodeBuilder($this->getConfig(), $this->createAsyncApiLoader());
    }

    /**
     * @return \SprykerSdk\AsyncApi\Loader\AsyncApiLoaderInterface
     */
    public function createAsyncApiLoader(): AsyncApiLoaderInterface
    {
        return new AsyncApiLoader();
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder\OpenApiCodeBuilderInterface
     */
    public function createOpenApiCodeBuilder(): OpenApiCodeBuilderInterface
    {
        return new OpenApiCodeBuilder($this->getConfig(), $this->getInflector());
    }

    /**
     * @return \Doctrine\Inflector\Inflector
     */
    public function getInflector(): Inflector
    {
        return InflectorFactory::create()->build();
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\AsyncApi\AsyncApiValidator
     */
    public function createAsyncApiValidator(): AsyncApiValidator
    {
        return new AsyncApiValidator(
            $this->getConfig(),
            $this->createFinder(),
            $this->getAsyncApiValidators(),
        );
    }

    /**
     * @return array
     */
    public function getAsyncApiValidators(): array
    {
        return [
            $this->createAsyncApiMessageValidator(),
            $this->createAsyncApiOperationIdValidator(),
        ];
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\OpenApi\OpenApiValidator
     */
    public function createOpenApiValidator(): OpenApiValidator
    {
        return new OpenApiValidator(
            $this->getConfig(),
            $this->createFinder(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\FileValidatorInterface
     */
    protected function createAsyncApiMessageValidator(): FileValidatorInterface
    {
        return new AsyncApiMessageValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Validator\FileValidatorInterface
     */
    protected function createAsyncApiOperationIdValidator(): FileValidatorInterface
    {
        return new AsyncApiOperationIdValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder\OpenApiBuilderInterface
     */
    public function createOpenApiBuilder(): OpenApiBuilderInterface
    {
        return new OpenApiBuilder();
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Manifest\Builder\AppManifestBuilderInterface
     */
    public function createAppManifestBuilder(): AppManifestBuilderInterface
    {
        return new AppManifestBuilder();
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Configuration\Builder\AppConfigurationBuilderInterface
     */
    public function createAppConfigurationBuilder(): AppConfigurationBuilderInterface
    {
        return new AppConfigurationBuilder();
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Business\Translation\Builder\AppTranslationBuilderInterface
     */
    public function createAppTranslationBuilder(): AppTranslationBuilderInterface
    {
        return new AppTranslationBuilder();
    }
}
