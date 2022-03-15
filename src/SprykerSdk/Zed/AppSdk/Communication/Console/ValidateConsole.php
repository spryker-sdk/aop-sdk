<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Communication\Console;

use Generated\Shared\Transfer\ValidateRequestTransfer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface getFacade()
 */
class ValidateConsole extends AbstractConsole
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:validate')
            ->setDescription('Validates the translation file.')
            ->addOption(AsyncApiValidateConsole::ASYNC_API_FILE, AsyncApiValidateConsole::ASYNC_API_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultAsyncApiFile())
            ->addOption(ConfigurationValidateConsole::CONFIGURATION_FILE, ConfigurationValidateConsole::CONFIGURATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultConfigurationFile())
            ->addOption(ManifestValidateConsole::MANIFEST_PATH, ManifestValidateConsole::MANIFEST_PATH_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultManifestPath())
            ->addOption(TranslationValidateConsole::TRANSLATION_FILE, TranslationValidateConsole::TRANSLATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultTranslationFile());
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
        $validateRequestTransfer->setAsyncApiFile($input->getOption(AsyncApiValidateConsole::ASYNC_API_FILE));
        $validateRequestTransfer->setManifestPath($input->getOption(ManifestValidateConsole::MANIFEST_PATH));
        $validateRequestTransfer->setConfigurationFile($input->getOption(ConfigurationValidateConsole::CONFIGURATION_FILE));
        $validateRequestTransfer->setTranslationFile($input->getOption(TranslationValidateConsole::TRANSLATION_FILE));

        $validateResponseTransfer = $this->getFacade()->validate($validateRequestTransfer);

        if ($validateResponseTransfer->getErrors()->count() === 0) {
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
