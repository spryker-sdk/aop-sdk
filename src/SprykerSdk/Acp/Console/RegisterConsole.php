<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Transfer\RegisterRequestTransfer;
use Transfer\ValidateRequestTransfer;

/**
 * @method \SprykerSdk\Acp\AcpFacadeInterface getFacade()
 */
class RegisterConsole extends AbstractConsole
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:register')
            ->setDescription('Registers an App in ACP.')
            ->addOption('private', 'p', InputOption::VALUE_NONE, 'Set this option when this App should be private to you. This requires to pass your Tenant Identifier with the option `--tenant-identifier|-ti`.')
            ->addOption('tenantIdentifier', 'ti', InputOption::VALUE_REQUIRED, 'When this App needs to be private this option needs to be set to your Tenant Identifier.')
            ->addOption('appIdentifier', 'ai', InputOption::VALUE_REQUIRED, 'The App Identifier of your App.')
            ->addOption('repositoryUrl', 'u', InputOption::VALUE_REQUIRED, 'The URL to your App repository e.g. https://github.com/organization/package.')
            ->addOption('repositoryToken', 't', InputOption::VALUE_REQUIRED, 'The Token that is required by Spryker to be able to interact with your repository.')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $registerRequestTransfer = new RegisterRequestTransfer();
        $registerResponseTransfer = $this->getFacade()->registerApp($registerRequestTransfer);

        $message = 'App successfully registered in ACP.';
        $responseCode = static::CODE_SUCCESS;

        if ($registerResponseTransfer->getErrors()->count() > 0) {
            $message = 'Could not register the App in ACP.';
            $responseCode = static::CODE_ERROR;
        }

        if ($output->isVerbose()) {
            $output->writeln($message);
        }

        foreach ($registerResponseTransfer->getErrors() as $errorTransfer) {
            $output->writeln($errorTransfer->getMessage());
        }

        return $responseCode;
    }
}
