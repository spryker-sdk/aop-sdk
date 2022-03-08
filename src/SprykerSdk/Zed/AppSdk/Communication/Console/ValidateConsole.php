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
        $this->setName('validate:app')
            ->setDescription('Validates the translation file.')
            ->addOption(ValidateAsyncApiConsole::ASYNC_API_FILE, ValidateAsyncApiConsole::ASYNC_API_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultManifestPath())
            ->addOption(ValidateConfigurationConsole::CONFIGURATION_FILE, ValidateConfigurationConsole::CONFIGURATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultConfigurationFile())
            ->addOption(ValidateManifestConsole::MANIFEST_PATH, ValidateManifestConsole::MANIFEST_PATH_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultManifestPath())
            ->addOption(ValidateTranslationConsole::TRANSLATION_FILE, ValidateTranslationConsole::TRANSLATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultTranslationFile());
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
        $validateRequestTransfer->setAsyncApiFile($input->getOption(ValidateAsyncApiConsole::ASYNC_API_FILE));
        $validateRequestTransfer->setManifestPath($input->getOption(ValidateManifestConsole::MANIFEST_PATH));
        $validateRequestTransfer->setConfigurationFile($input->getOption(ValidateConfigurationConsole::CONFIGURATION_FILE));
        $validateRequestTransfer->setTranslationFile($input->getOption(ValidateTranslationConsole::TRANSLATION_FILE));

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
