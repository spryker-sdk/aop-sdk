<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Transfer\AppTranslationRequestTransfer;

/**
 * @method \SprykerSdk\Acp\AcpFacadeInterface getFacade()
 */
class AppTranslationCreateConsole extends AbstractConsole
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
    public const TRANSLATION_FILE_SHORT = 't';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:translation:create')
        ->setDescription('Create a translation file.')
        ->addOption(static::TRANSLATION_FILE, static::TRANSLATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultTranslationFile());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $appTranslationRequestTransfer = new AppTranslationRequestTransfer();

        $appTranslationRequestTransfer->setTranslationFile($input->getOption(static::TRANSLATION_FILE));

        $output->writeln([
            'Welcome to the App translation builder.',
            '',
            'Translations will be used to show translated text for displaying informations in the App Store Catalog and for displaying a translated form on the configuration page of the app.',
            '',
            'For each translation you will be prompted to enter details.',
            '',
            'When the process is done a translation file will be created in: ' . $appTranslationRequestTransfer->getTranslationFile(),
            'When you have a typo or anything else you\'d like to change you can do that manually in the created file after this process is finished.',
            '',
        ]);

        $this->getTranslationsInput($input, $output);

        $appTranslationRequestTransfer->setTranslations($this->translations);

        $appTranslationResponseTransfer = $this->getFacade()->createAppTranslation($appTranslationRequestTransfer);

        if ($appTranslationResponseTransfer->getErrors()->count() === 0) {
            $this->printMessages($output, $appTranslationResponseTransfer->getMessages());

            return static::CODE_SUCCESS;
        }

        $this->printMessages($output, $appTranslationResponseTransfer->getErrors());

        return static::CODE_ERROR;
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
        return $this->getHelper('question')->ask($input, $output, new Question($questionText));
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function getTranslationsInput(InputInterface $input, OutputInterface $output): void
    {
        do {
            $translationKey = $this->askTextQuestion($input, $output, 'Please enter a translation key: ');

            $this->setLocale(
                $input,
                $output,
                $translationKey,
            );
        } while ($this->askForConfirmation($input, $output, 'Do you want to add more translations?') == 'Yes');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $translationKey
     *
     * @return void
     */
    protected function setLocale(InputInterface $input, OutputInterface $output, string $translationKey): void
    {
        do {
            $localeName = $this->askTextQuestion($input, $output, 'Please enter a locale name: ');
            $translationValue = $this->askTextQuestion($input, $output, 'Please enter a translation value: ');

            if ($localeName !== null) {
                $this->translations[$translationKey][$localeName] = $translationValue;
            }
        } while ($this->askForConfirmation($input, $output, 'Do you want to add more locales?') == 'Yes');
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
        return $this->getHelper('question')->ask($input, $output, new ChoiceQuestion($questionText, [1 => 'Yes', 2 => 'No'], 1));
    }
}
