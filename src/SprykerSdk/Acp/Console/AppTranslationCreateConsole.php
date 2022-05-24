<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Console;

use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Transfer\AppTranslationRequestTransfer;
use Transfer\AppTranslationResponseTransfer;
use Transfer\ManifestCollectionTransfer;
use Transfer\ManifestConditionsTransfer;
use Transfer\ManifestCriteriaTransfer;
use const SIGINT;

/**
 * @method \SprykerSdk\Acp\AcpFacadeInterface getFacade()
 */
class AppTranslationCreateConsole extends AbstractConsole implements SignalableCommandInterface
{
    /**
     * @var array
     */
    protected array $translations = [];

    /**
     * @var string
     */
    public const TRANSLATION_FILE = 'translation-file';

    /**
     * @var string
     */
    public const MANIFEST_FOLDER = 'manifest-folder';

    /**
     * @var string
     */
    public const CONFIGURATION_FILE = 'configuration-file';

    /**
     * @var string
     */
    public const TRANSLATION_FILE_SHORT = 't';

    /**
     * @var string
     */
    public const MANIFEST_FOLDER_SHORT = 'm';

    /**
     * @var string
     */
    public const CONFIGURATION_FILE_SHORT = 'c';

    /**
     * @var string
     */
    protected const CHOICE_NEW_LOCALE = 'Select this to add a new locale';

    /**
     * @var \Symfony\Component\Console\Input\InputInterface|null
     */
    protected ?InputInterface $input = null;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface|null
     */
    protected ?OutputInterface $output = null;

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:translation:create')
        ->setDescription('Create a translation file.')
        ->addOption(static::TRANSLATION_FILE, static::TRANSLATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultTranslationFile())
        ->addOption(static::MANIFEST_FOLDER, static::MANIFEST_FOLDER_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultManifestFolder())
        ->addOption(static::CONFIGURATION_FILE, static::CONFIGURATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultConfigurationFile());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $this->printWelcomeMessage($output);

        $manifestCollectionTransfer = $this->getManifestCollection(
            $input->getOption(static::MANIFEST_FOLDER),
            $input->getOption(static::CONFIGURATION_FILE),
            $input->getOption(static::TRANSLATION_FILE),
        );

        $existingKeysToTranslate = $this->getExistingKeysToTranslate($manifestCollectionTransfer);
        $existingTranslations = $this->getExistingTranslations($manifestCollectionTransfer);
        $existingLocales = $this->getExistingLocales($manifestCollectionTransfer);

        $localeName = '';

        do {
            if (count($existingLocales) > 0) {
                $localeName = $this->chooseLocale($input, $output, $existingLocales);
            }

            if (empty($localeName) || $localeName === static::CHOICE_NEW_LOCALE) {
                $localeName = $this->createNewLocale($input, $output);
            }

            $this->getTranslationsInput($input, $output, $localeName, $existingKeysToTranslate, $existingTranslations);
        } while ($this->askForConfirmation($input, $output, 'Would you like to add translations for another locale?') === 'Yes');

        $appTranslationResponseTransfer = $this->saveTranslations($input);

        if ($appTranslationResponseTransfer->getErrors()->count() === 0) {
            $this->printMessages($output, $appTranslationResponseTransfer->getMessages());

            return static::CODE_SUCCESS;
        }

        $this->printMessages($output, $appTranslationResponseTransfer->getErrors());

        return static::CODE_ERROR;
    }

    /**
     * @return array
     */
    public function getSubscribedSignals(): array
    {
        return [SIGINT];
    }

    /**
     * @param int $signal
     *
     * @return void
     */
    public function handleSignal(int $signal): void
    {
        if (
            $signal === SIGINT
            && $this->input !== null
            && $this->output !== null
            && $this->translations
        ) {
            $appTranslationResponseTransfer = $this->saveTranslations($this->input);
            if ($appTranslationResponseTransfer->getErrors()->count() === 0) {
                $this->printMessages($this->output, $appTranslationResponseTransfer->getMessages());
            }

            $this->printMessages($this->output, $appTranslationResponseTransfer->getErrors());
        }

        exit();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $questionText
     *
     * @return string
     */
    protected function askTextQuestion(InputInterface $input, OutputInterface $output, string $questionText): string
    {
        return $this->getHelper('question')->ask($input, $output, new Question($questionText)) ?: '';
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $localeName
     * @param array<string> $existingKeysToTranslate
     * @param array<string, array<string, string>> $existingTranslations
     *
     * @return void
     */
    protected function getTranslationsInput(
        InputInterface $input,
        OutputInterface $output,
        string $localeName,
        array $existingKeysToTranslate,
        array $existingTranslations
    ): void {
        $this->printHelper($output, $localeName);

        foreach ($existingKeysToTranslate as $keyToTranslate) {
            if (isset($existingTranslations[$keyToTranslate][$localeName])) {
                $this->translations[$keyToTranslate][$localeName] = $existingTranslations[$keyToTranslate][$localeName];

                continue;
            }

            $translationValue = $this->askTextQuestion(
                $input,
                $output,
                sprintf('Please enter a translation for: %s: ', $keyToTranslate),
            );
            $this->translations[$keyToTranslate][$localeName] = $translationValue;
        }

        $output->writeln(sprintf('<info>We haven\'t found any missing translations for the locale</info> <comment>%s</comment>', $localeName));

        while ($result = $this->askForConfirmation($input, $output, 'Would you like to add new translations?') === 'Yes') {
            $this->printHelper($output, $localeName);

            $keyToTranslate = $this->askTextQuestion($input, $output, 'Please enter a translation key: ');
            $translationValue = $this->askTextQuestion($input, $output, 'Please enter a translation value: ');
            $this->translations[$keyToTranslate][$localeName] = $translationValue;
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $localeName
     *
     * @return void
     */
    protected function printHelper(OutputInterface $output, string $localeName): void
    {
        $output->writeln(sprintf('<info>The following inputs will be used for your selected locale</info> <comment>%s</comment>', $localeName));
        $output->writeln('');
        $output->writeln('<info>When you like to leave the process please hit</info> <comment>CTRL+C</comment> <info>then</info> <comment>Enter</comment>. <info>Already entered data will be automatically saved.</info>');
        $output->writeln('');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array<string> $existingLocales
     *
     * @return string
     */
    protected function chooseLocale(InputInterface $input, OutputInterface $output, array $existingLocales): string
    {
        array_unshift($existingLocales, static::CHOICE_NEW_LOCALE);

        return $this->getHelper('question')->ask(
            $input,
            $output,
            new ChoiceQuestion(
                'We found the following locales, please select one you\'d like to define translations for:',
                $existingLocales,
            ),
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string
     */
    protected function createNewLocale(InputInterface $input, OutputInterface $output): string
    {
        return $this->askTextQuestion(
            $input,
            $output,
            'Please enter a locale name you would like to define translations for: ',
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $questionText
     *
     * @return string
     */
    protected function askForConfirmation(
        InputInterface $input,
        OutputInterface $output,
        string $questionText
    ): string {
        return $this->getHelper('question')->ask($input, $output, new ChoiceQuestion($questionText, ['Yes', 'No'], 0));
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printWelcomeMessage(OutputInterface $output): void
    {
        // TODO: complete the welcome message
        $output->writeln('Welcome to the Translation Creator');
    }

    protected function getManifestCollection(
        string $manifestFolder,
        string $configurationFilePath,
        string $translationFilePath
    ): ManifestCollectionTransfer {
        $manifestConditionsTransfer = (new ManifestConditionsTransfer())
            ->setManifestFolder($manifestFolder)
            ->setConfigurationFilePath($configurationFilePath)
            ->setTranslationFilePath($translationFilePath);
        $manifestCriteriaTransfer = (new ManifestCriteriaTransfer())
            ->setManifestConditions($manifestConditionsTransfer);

        return $this->getFacade()->getManifestCollection($manifestCriteriaTransfer);
    }

    /**
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     *
     * @return array<string>
     */
    protected function getExistingLocales(ManifestCollectionTransfer $manifestCollectionTransfer): array
    {
        $locales = [];
        foreach ($manifestCollectionTransfer->getManifests() as $manifestTransfer) {
            $locales[] = $manifestTransfer->getLocaleName();
        }

        return $locales;
    }

    /**
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     *
     * @return array<string>
     */
    protected function getExistingKeysToTranslate(ManifestCollectionTransfer $manifestCollectionTransfer): array
    {
        $configuration = $manifestCollectionTransfer->getConfiguration();

        if ($configuration === null || !isset($configuration->getConfiguration()['properties'])) {
            return [];
        }

        $keysToTranslate = [];
        foreach ($configuration->getConfiguration()['properties'] as $propertyConfiguration) {
            if (isset($propertyConfiguration['title'])) {
                $keysToTranslate[] = $propertyConfiguration['title'];
            }
            if (isset($propertyConfiguration['placeholder'])) {
                $keysToTranslate[] = $propertyConfiguration['placeholder'];
            }
            if (isset($propertyConfiguration['oneOf']) && is_array($propertyConfiguration['oneOf'])) {
                foreach ($propertyConfiguration['oneOf'] as $element) {
                    if (isset($element['description'])) {
                        $keysToTranslate[] = $element['description'];
                    }
                }
            }
            if (isset($propertyConfiguration['items']['oneOf']) && is_array($propertyConfiguration['items']['oneOf'])) {
                foreach ($propertyConfiguration['items']['oneOf'] as $element) {
                    if (isset($element['description'])) {
                        $keysToTranslate[] = $element['description'];
                    }
                    if (isset($element['enum']) && is_array($element['enum'])) {
                        $keysToTranslate = array_merge($keysToTranslate, $element['enum']);
                    }
                }
            }
        }

        return array_unique($keysToTranslate);
    }

    /**
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     *
     * @return array<string, array<string, string>>
     */
    protected function getExistingTranslations(ManifestCollectionTransfer $manifestCollectionTransfer): array
    {
        $translations = $manifestCollectionTransfer->getTranslation();

        return $translations ? $translations->getTranslations() : [];
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Transfer\AppTranslationResponseTransfer
     */
    protected function saveTranslations(InputInterface $input): AppTranslationResponseTransfer
    {
        $appTranslationRequestTransfer = (new AppTranslationRequestTransfer())
            ->setTranslationFile($input->getOption(static::TRANSLATION_FILE))
            ->setTranslations($this->translations);

        return $this->getFacade()->createAppTranslation($appTranslationRequestTransfer);
    }
}
