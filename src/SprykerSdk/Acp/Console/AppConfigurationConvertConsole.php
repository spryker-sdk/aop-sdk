<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \SprykerSdk\Acp\AcpFacadeInterface getFacade()
 */
class AppConfigurationConvertConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const CONFIGURATION_FILE = 'configuration-file';

    /**
     * @var string
     */
    public const OPENAPI_FILE = 'openapi-file';

    /**
     * @var string
     */
    public const CONFIGURATION_FILE_SHORT = 'c';

    /**
     * @var string
     */
    public const OPENAPI_FILE_SHORT = 'o';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:configuration:convert')
            ->setDescription('Validates the configuration file.')
            ->addOption(static::CONFIGURATION_FILE, static::CONFIGURATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultConfigurationFile())
            ->addOption(static::OPENAPI_FILE, static::OPENAPI_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultOpenapiFile());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->convertConfigurationToSchemaFile(
            $input->getOption(static::CONFIGURATION_FILE),
            $input->getOption(static::OPENAPI_FILE),
        );

        return static::CODE_SUCCESS;
    }
}
