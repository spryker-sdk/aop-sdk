<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp;

use SprykerSdk\Acp\Configuration\Builder\AppConfigurationBuilder;
use SprykerSdk\Acp\Configuration\Builder\AppConfigurationBuilderInterface;
use SprykerSdk\Acp\Configuration\Reader\AppConfigurationReader;
use SprykerSdk\Acp\Configuration\Reader\AppConfigurationReaderInterface;
use SprykerSdk\Acp\Manifest\Builder\AppManifestBuilder;
use SprykerSdk\Acp\Manifest\Builder\AppManifestBuilderInterface;
use SprykerSdk\Acp\Manifest\Reader\AppManifestReader;
use SprykerSdk\Acp\Manifest\Reader\AppManifestReaderInterface;
use SprykerSdk\Acp\Mapper\TranslateKeyMapper;
use SprykerSdk\Acp\Mapper\TranslateKeyMapperInterface;
use SprykerSdk\Acp\ReadinessChecker\Checker\CheckerInterface;
use SprykerSdk\Acp\ReadinessChecker\Checker\ComposerChecker;
use SprykerSdk\Acp\ReadinessChecker\Checker\EnvChecker;
use SprykerSdk\Acp\ReadinessChecker\Checker\PluginsChecker;
use SprykerSdk\Acp\ReadinessChecker\ReadinessChecker;
use SprykerSdk\Acp\ReadinessChecker\ReadinessCheckerInterface;
use SprykerSdk\Acp\ReadinessChecker\RecipeLoader\RecipeLoader;
use SprykerSdk\Acp\ReadinessChecker\RecipeLoader\RecipeLoaderInterface;
use SprykerSdk\Acp\Schema\SchemaConverter;
use SprykerSdk\Acp\Schema\SchemaExtender;
use SprykerSdk\Acp\Schema\SchemaExtenderInterface;
use SprykerSdk\Acp\Schema\SchemaWriter;
use SprykerSdk\Acp\Schema\SchemaWriterInterface;
use SprykerSdk\Acp\Translation\Builder\AppTranslationBuilder;
use SprykerSdk\Acp\Translation\Builder\AppTranslationBuilderInterface;
use SprykerSdk\Acp\Validator\Configuration\AppConfigurationValidator;
use SprykerSdk\Acp\Validator\FileValidatorInterface;
use SprykerSdk\Acp\Validator\Finder\Finder;
use SprykerSdk\Acp\Validator\Finder\FinderInterface;
use SprykerSdk\Acp\Validator\Manifest\AppManifestValidator;
use SprykerSdk\Acp\Validator\Manifest\Validator\PagesFileValidator;
use SprykerSdk\Acp\Validator\Manifest\Validator\RequiredFieldsFileValidator;
use SprykerSdk\Acp\Validator\Translation\AppTranslationValidator;
use SprykerSdk\Acp\Validator\Translation\Validator\TranslationFileValidator;
use SprykerSdk\Acp\Validator\Validator;
use SprykerSdk\Acp\Validator\ValidatorInterface;

class AcpFactory
{
    /**
     * @var \SprykerSdk\Acp\AcpConfig|null
     */
    protected ?AcpConfig $config = null;

    /**
     * @param \SprykerSdk\Acp\AcpConfig $config
     *
     * @return void
     */
    public function setConfig(AcpConfig $config): void
    {
        $this->config = $config;
    }

    /**
     * @return \SprykerSdk\Acp\Validator\Validator
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
     * @return \SprykerSdk\Acp\Validator\ValidatorInterface
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
     * @return \SprykerSdk\Acp\Validator\ValidatorInterface
     */
    public function createAppConfigurationValidator(): ValidatorInterface
    {
        return new AppConfigurationValidator(
            $this->getConfig(),
            $this->createFinder(),
        );
    }

    /**
     * @return \SprykerSdk\Acp\Validator\Finder\FinderInterface
     */
    protected function createFinder(): FinderInterface
    {
        return new Finder();
    }

    /**
     * @return \SprykerSdk\Acp\Validator\ValidatorInterface
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
     * @return \SprykerSdk\Acp\ReadinessChecker\ReadinessCheckerInterface
     */
    public function createReadinessChecker(): ReadinessCheckerInterface
    {
        return new ReadinessChecker($this->createRecipeLoader(), $this->getReadinessChecker());
    }

    /**
     * @return array<\SprykerSdk\Acp\ReadinessChecker\Checker\CheckerInterface>
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
     * @return \SprykerSdk\Acp\ReadinessChecker\Checker\CheckerInterface
     */
    public function createComposerChecker(): CheckerInterface
    {
        return new ComposerChecker();
    }

    /**
     * @return \SprykerSdk\Acp\ReadinessChecker\Checker\CheckerInterface
     */
    public function createPluginsChecker(): CheckerInterface
    {
        return new PluginsChecker();
    }

    /**
     * @return \SprykerSdk\Acp\ReadinessChecker\Checker\CheckerInterface
     */
    public function createEnvChecker(): CheckerInterface
    {
        return new EnvChecker();
    }

    /**
     * @return \SprykerSdk\Acp\ReadinessChecker\RecipeLoader\RecipeLoaderInterface
     */
    public function createRecipeLoader(): RecipeLoaderInterface
    {
        return new RecipeLoader($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Acp\Manifest\Builder\AppManifestBuilderInterface
     */
    public function createAppManifestBuilder(): AppManifestBuilderInterface
    {
        return new AppManifestBuilder();
    }

    /**
     * @return \SprykerSdk\Acp\Configuration\Builder\AppConfigurationBuilderInterface
     */
    public function createAppConfigurationBuilder(): AppConfigurationBuilderInterface
    {
        return new AppConfigurationBuilder();
    }

    /**
     * @return \SprykerSdk\Acp\Translation\Builder\AppTranslationBuilderInterface
     */
    public function createAppTranslationBuilder(): AppTranslationBuilderInterface
    {
        return new AppTranslationBuilder();
    }

    /**
     * @return \SprykerSdk\Acp\Manifest\Reader\AppManifestReaderInterface
     */
    public function createAppManifestReader(): AppManifestReaderInterface
    {
        return new AppManifestReader($this->getConfig(), $this->createFinder());
    }

    /**
     * @return \SprykerSdk\Acp\Mapper\TranslateKeyMapperInterface
     */
    public function createTranslateKeyMapper(): TranslateKeyMapperInterface
    {
        return new TranslateKeyMapper();
    }

    /**
     * @return \SprykerSdk\Acp\Schema\SchemaConverter
     */
    public function createSchemaConverter(): SchemaConverter
    {
        return new SchemaConverter(
            $this->createAppConfigurationReader(),
            $this->createSchemaWriter(),
        );
    }

    /**
     * @return \SprykerSdk\Acp\Schema\SchemaExtenderInterface
     */
    public function createSchemaExtender(): SchemaExtenderInterface
    {
        return new SchemaExtender(
            $this->createSchemaConverter(),
            $this->createSchemaWriter(),
        );
    }

    /**
     * @return \SprykerSdk\Acp\Configuration\Reader\AppConfigurationReaderInterface
     */
    protected function createAppConfigurationReader(): AppConfigurationReaderInterface
    {
        return new AppConfigurationReader();
    }

    /**
     * @return \SprykerSdk\Acp\Schema\SchemaWriterInterface
     */
    protected function createSchemaWriter(): SchemaWriterInterface
    {
        return new SchemaWriter();
    }

    /**
     * @return array<\SprykerSdk\Acp\Validator\FileValidatorInterface>
     */
    protected function getManifestFileValidators(): array
    {
        return [
            $this->createManifestRequiredFieldsFileValidator(),
            $this->createManifestPagesFileValidator(),
        ];
    }

    /**
     * @return \SprykerSdk\Acp\Validator\FileValidatorInterface
     */
    protected function createManifestRequiredFieldsFileValidator(): FileValidatorInterface
    {
        return new RequiredFieldsFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Acp\Validator\FileValidatorInterface
     */
    protected function createManifestPagesFileValidator(): FileValidatorInterface
    {
        return new PagesFileValidator($this->getConfig());
    }

    /**
     * @return array<\SprykerSdk\Acp\Validator\FileValidatorInterface>
     */
    protected function getTranslationFileValidators(): array
    {
        return [
            $this->createTranslationFileValidator(),
        ];
    }

    /**
     * @return \SprykerSdk\Acp\Validator\FileValidatorInterface
     */
    protected function createTranslationFileValidator(): FileValidatorInterface
    {
        return new TranslationFileValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Acp\AcpConfig
     */
    protected function getConfig(): AcpConfig
    {
        if (!$this->config) {
            $this->config = new AcpConfig();
        }

        return $this->config;
    }
}
