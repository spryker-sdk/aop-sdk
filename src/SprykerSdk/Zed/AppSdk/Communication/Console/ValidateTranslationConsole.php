<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Communication\Console;

use SprykerSdk\Zed\AppSdk\Business\Request\ValidateRequest;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface getFacade()
 */
class ValidateTranslationConsole extends AbstractConsole
{
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
        $this->setName('validate:translation')
            ->setDescription('Validates the translation file.')
            ->addOption(static::TRANSLATION_FILE, static::TRANSLATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultTranslationFile())
            ->addOption(ValidateManifestConsole::MANIFEST_PATH, ValidateManifestConsole::MANIFEST_PATH_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultManifestPath())
            ->addOption(ValidateConfigurationConsole::CONFIGURATION_FILE, ValidateConfigurationConsole::CONFIGURATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultConfigurationFile());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $validatorRequest = new ValidateRequest();
        $validatorRequest->setTranslationFile($input->getOption(static::TRANSLATION_FILE));
        $validatorRequest->setManifestPath($input->getOption(ValidateManifestConsole::MANIFEST_PATH));
        $validatorRequest->setConfigurationFile($input->getOption(ValidateConfigurationConsole::CONFIGURATION_FILE));

        $validatorResult = $this->getFacade()->validateTranslation($validatorRequest);

        if ($validatorResult->isValid()) {
            return static::CODE_SUCCESS;
        }

        if ($output->isVerbose()) {
            foreach ($validatorResult->getErrors() as $error) {
                $output->writeln($error);
            }
        }

        return static::CODE_ERROR;
    }
}
