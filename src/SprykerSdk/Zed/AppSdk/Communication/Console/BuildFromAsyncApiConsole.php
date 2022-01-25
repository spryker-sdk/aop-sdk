<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Communication\Console;

use Generated\Shared\Transfer\AsyncApiRequestTransfer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface getFacade()
 */
class BuildFromAsyncApiConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const OPTION_ASYNC_API_FILE = 'asyncapi-file';

    /**
     * @var string
     */
    public const OPTION_ASYNC_API_FILE_SHORT = 'a';

    /**
     * @var string
     */
    public const OPTION_PROJECT_NAMESPACE = 'project-namespace';

    /**
     * @var string
     */
    public const OPTION_PROJECT_NAMESPACE_SHORT = 'p';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('build:from:asyncapi')
            ->setDescription('Builds code from an AsyncAPI file definition.')
            ->addOption(static::OPTION_ASYNC_API_FILE, static::OPTION_ASYNC_API_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultAsyncApiFile())
            ->addOption(static::OPTION_PROJECT_NAMESPACE, static::OPTION_PROJECT_NAMESPACE_SHORT, InputOption::VALUE_REQUIRED, 'Project namespace that should be used for the code builder.', 'App');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setTargetFile($input->getOption(static::OPTION_ASYNC_API_FILE))
            ->setProjectNamespace($input->getOption(static::OPTION_PROJECT_NAMESPACE));

        $asyncApiResponseTransfer = $this->getFacade()->buildFromAsyncApi($asyncApiRequestTransfer);

        if ($asyncApiResponseTransfer->getErrors()->count() === 0) {
            return static::CODE_SUCCESS;
        }

        if ($output->isVerbose()) {
            foreach ($asyncApiResponseTransfer->getErrors() as $error) {
                $output->writeln($error->getMessageOrFail());
            }
        }

        return static::CODE_ERROR;
    }
}
