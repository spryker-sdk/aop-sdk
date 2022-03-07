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
class ValidateAsyncApiConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const ASYNC_API_PATH = 'asyncapi-path';

    /**
     * @var string
     */
    public const ASYNC_API_PATH_SHORT = 'a';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('validate:asyncapi')
            ->setDescription('Validates the asyncapi files.')
            ->addOption(static::ASYNC_API_PATH, static::ASYNC_API_PATH_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getValidationAsyncApiFile());
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
        $validateRequestTransfer->setAsyncApiPath($input->getOption(static::ASYNC_API_PATH));

        
        $validateResponseTransfer = $this->getFacade()->validateAsyncApi($validateRequestTransfer);

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
