<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Communication\Console;

use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiTransfer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface getFacade()
 */
class OpenApiCreateConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const ARGUMENT_TITLE = 'title';

    /**
     * @var string
     */
    public const OPTION_OPEN_API_FILE = 'openapi-file';

    /**
     * @var string
     */
    public const OPTION_OPEN_API_FILE_SHORT = 'a';

    /**
     * @var string
     */
    public const OPTION_API_VERSION = 'api-version';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('openapi:create')
            ->setDescription('Adds an OpenAPI file to the specified Open API schema file path.')
            ->addArgument(static::ARGUMENT_TITLE, InputArgument::REQUIRED, 'The name of the App.')
            ->addOption(static::OPTION_OPEN_API_FILE, static::OPTION_OPEN_API_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultOpenApiFile())
            ->addOption(static::OPTION_API_VERSION, null, InputOption::VALUE_REQUIRED, 'Version number of the OpenAPI schema. Defaults to 0.1.0', '0.1.0');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $openApiTransfer = new OpenApiTransfer();
        $openApiTransfer
            ->setTitle($input->getArgument(static::ARGUMENT_TITLE))
            ->setVersion($input->getOption(static::OPTION_API_VERSION));

        $openApiRequestTransfer = new OpenApiRequestTransfer();
        $openApiRequestTransfer
            ->setTargetFile($input->getOption(static::OPTION_OPEN_API_FILE))
            ->setOpenApi($openApiTransfer);

        $openApiResponseTransfer = $this->getFacade()->createOpenApi($openApiRequestTransfer);

        if ($openApiResponseTransfer->getErrors()->count() === 0) {
            return static::CODE_SUCCESS;
        }

        // @codeCoverageIgnoreStart
        if ($output->isVerbose()) {
            foreach ($openApiResponseTransfer->getErrors() as $error) {
                $output->writeln($error->getMessageOrFail());
            }
        }

        return static::CODE_ERROR;
        // @codeCoverageIgnoreEnd
    }
}
