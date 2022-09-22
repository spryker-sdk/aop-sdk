<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Transfer\ValidateRequestTransfer;

/**
 * @method \SprykerSdk\Acp\AcpFacadeInterface getFacade()
 */
class ValidateConsole extends AbstractConsole
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:validate')
            ->setDescription('Validates the configuration, manifest, and translation files.')
            ->addOption(AppConfigurationValidateConsole::CONFIGURATION_FILE, AppConfigurationValidateConsole::CONFIGURATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultConfigurationFile())
            ->addOption(AppManifestValidateConsole::MANIFEST_PATH, AppManifestValidateConsole::MANIFEST_PATH_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultManifestPath())
            ->addOption(AppTranslationValidateConsole::TRANSLATION_FILE, AppTranslationValidateConsole::TRANSLATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultTranslationFile());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $validateRequestTransfer = new ValidateRequestTransfer();
        $validateRequestTransfer->setManifestPath($input->getOption(AppManifestValidateConsole::MANIFEST_PATH));
        $validateRequestTransfer->setConfigurationFile($input->getOption(AppConfigurationValidateConsole::CONFIGURATION_FILE));
        $validateRequestTransfer->setTranslationFile($input->getOption(AppTranslationValidateConsole::TRANSLATION_FILE));

        $validateResponseTransfer = $this->getFacade()->validate($validateRequestTransfer);

        if ($validateResponseTransfer->getErrors()->count() === 0) {
            $output->write('Validated manifest, configuration and translation files, no errors found.');

            return static::CODE_SUCCESS;
        }

        if ($output->isVerbose()) {
            foreach ($validateResponseTransfer->getErrors() as $error) {
                $output->writeln($error->getMessageOrFail());
            }
        }

        return static::CODE_ERROR;
    }
}
