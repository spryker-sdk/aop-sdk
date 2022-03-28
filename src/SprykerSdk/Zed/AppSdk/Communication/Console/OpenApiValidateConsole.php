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
class OpenApiValidateConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const OPEN_API_FILE = 'openapi-file';

    /**
     * @var string
     */
    public const OPEN_API_FILE_SHORT = 'o';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('openapi:validate')
            ->setDescription('Validates an OpenAPI file.')
            ->addOption(static::OPEN_API_FILE, static::OPEN_API_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultOpenApiFile());
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
        $validateRequestTransfer->setOpenApiFile($input->getOption(static::OPEN_API_FILE));

        $validateResponseTransfer = $this->getFacade()->validateOpenApi($validateRequestTransfer);

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
