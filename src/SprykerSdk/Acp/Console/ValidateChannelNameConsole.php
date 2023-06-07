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
class ValidateChannelNameConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const CONFIGURATION_FILE = 'configuration-file';

    /**
     * @var string
     */
    public const CONFIGURATION_FILE_SHORT = 'c';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('acp:channel-name:validate')
            ->setDescription('Validates the channel names.')
            ->addOption(static::CONFIGURATION_FILE, static::CONFIGURATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultProjectConfigurationFile());
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
        $validateRequestTransfer->setConfigurationFile($input->getOption(static::CONFIGURATION_FILE));

        $validateResponseTransfer = $this->getFacade()->validateChannelNames($validateRequestTransfer);

        if ($validateResponseTransfer->getErrors()->count() === 0) {
            $output->write('Validated channels, no errors found.');

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
