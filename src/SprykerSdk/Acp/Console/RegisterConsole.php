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
            ->setDescription('Registers an App in ACP. When the App already exists in ACP it will be updated automatically.')
            ->addOption('appIdentifier', null, InputOption::VALUE_REQUIRED, 'The App Identifier of your App.')
            ->addOption('baseUrl', null, InputOption::VALUE_REQUIRED, 'The base URL to your App for app assets and APIs.')
            ->addOption('apiBaseUrl', null, InputOption::VALUE_OPTIONAL, 'The API base URL to your App for the App APIs. (Only needed when the App has different URLs for the App and the APIs.))')
            ->addOption('registryUrl', null, InputOption::VALUE_OPTIONAL, 'The base URL to the Registry Service (local, testing, staging)', 'https://api.atrs.spryker.com')
            ->addOption('authorizationToken', null, InputOption::VALUE_REQUIRED, 'The Token that is required to be able to send requests to the Registry Service.')
            ->addOption(AppConfigurationValidateConsole::CONFIGURATION_FILE, AppConfigurationValidateConsole::CONFIGURATION_FILE_SHORT, InputOption::VALUE_OPTIONAL, '', $this->getConfig()->getDefaultConfigurationFile())
            ->addOption(AppManifestValidateConsole::MANIFEST_PATH, AppManifestValidateConsole::MANIFEST_PATH_SHORT, InputOption::VALUE_OPTIONAL, '', $this->getConfig()->getDefaultManifestPath())
            ->addOption(AppTranslationValidateConsole::TRANSLATION_FILE, AppTranslationValidateConsole::TRANSLATION_FILE_SHORT, InputOption::VALUE_OPTIONAL, '', $this->getConfig()->getDefaultTranslationFile())
            ->addOption('acpApiFile', 'a', InputOption::VALUE_OPTIONAL, '', $this->getConfig()->getDefaultAcpApiFilePath())
            ->addOption('private', null, InputOption::VALUE_NONE, 'Set this option when this App should be private to you. This requires to pass your Tenant Identifier with the option `--tenant-identifier`.')
            ->addOption('tenantIdentifier', null, InputOption::VALUE_REQUIRED, 'When this App needs to be private this option needs to be set to your Tenant Identifier.')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Simulate the operation without running HTTP request, the request body will be displayed.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->validateOptions($input, $output)) {
            return static::CODE_ERROR;
        }

        if (!$this->validate($input, $output)) {
            return static::CODE_ERROR;
        }

        return $this->register($input, $output);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return bool
     */
    protected function validateOptions(InputInterface $input, OutputInterface $output): bool
    {
        $optionsValid = true;

        if (!$input->getOption('appIdentifier')) {
            $output->writeln('You need to pass an AppIdentifier with the option `--appIdentifier`.');
            $optionsValid = false;
        }

        if (!$input->getOption('baseUrl')) {
            $output->writeln('You need to pass a base URL to your App with the option `--baseUrl`.');
            $optionsValid = false;
        }

        if (!$input->getOption('authorizationToken')) {
            $output->writeln('You need to pass an authorization token with the option `--authorizationToken`.');
            $optionsValid = false;
        }

        if ($input->getOption('private') && !$input->getOption('tenantIdentifier')) {
            $output->writeln('You need to pass a Tenant Identifier with the option `--tenantIdentifier` when you want this App to be only visible to you.');
            $optionsValid = false;
        }

        return $optionsValid;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return bool
     */
    protected function validate(InputInterface $input, OutputInterface $output): bool
    {
        $validateRequestTransfer = new ValidateRequestTransfer();
        $validateRequestTransfer->setManifestPath($input->getOption(AppManifestValidateConsole::MANIFEST_PATH));
        $validateRequestTransfer->setConfigurationFile($input->getOption(AppConfigurationValidateConsole::CONFIGURATION_FILE));
        $validateRequestTransfer->setTranslationFile($input->getOption(AppTranslationValidateConsole::TRANSLATION_FILE));

        $validateResponseTransfer = $this->getFacade()->validate($validateRequestTransfer);

        if ($validateResponseTransfer->getErrors()->count() === 0) {
            return true;
        }

        foreach ($validateResponseTransfer->getErrors() as $error) {
            $output->writeln($error->getMessageOrFail());
        }

        return false;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function register(InputInterface $input, OutputInterface $output): int
    {
        $registerRequestTransfer = new RegisterRequestTransfer();

        $registerRequestTransfer->setPrivate((bool)$input->getOption('private'));
        $registerRequestTransfer->setAppIdentifier($input->getOption('appIdentifier'));
        $registerRequestTransfer->setTenantIdentifier($input->getOption('tenantIdentifier'));
        $registerRequestTransfer->setRegistryUrl($input->getOption('registryUrl'));
        $registerRequestTransfer->setAuthorizationToken($input->getOption('authorizationToken'));
        $registerRequestTransfer->setBaseUrl($input->getOption('baseUrl'));
        $registerRequestTransfer->setApiBaseUrl($input->getOption('apiBaseUrl'));

        $registerRequestTransfer->setManifestPath($input->getOption(AppManifestValidateConsole::MANIFEST_PATH));
        $registerRequestTransfer->setConfigurationFile($input->getOption(AppConfigurationValidateConsole::CONFIGURATION_FILE));
        $registerRequestTransfer->setTranslationFile($input->getOption(AppTranslationValidateConsole::TRANSLATION_FILE));
        $registerRequestTransfer->setAcpApiFile($input->getOption('acpApiFile'));

        if ($input->getOption('dry-run')) {
            $output->writeln('Running in dry-run mode. No actual changes will be made.');

            $requestBody = $this->getFacade()->getRegistrationRequestBody($registerRequestTransfer);
            $output->writeln($requestBody);

            return static::CODE_SUCCESS;
        }

        $registerResponseTransfer = $this->getFacade()->registerApp($registerRequestTransfer);

        if ($registerResponseTransfer->getErrors()->count() > 0) {
            $output->writeln('Could not register the App in ACP. Use -v to see errors.');

            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                foreach ($registerResponseTransfer->getErrors() as $errorTransfer) {
                    $output->writeln($errorTransfer->getMessageOrFail());
                }
            }

            return static::CODE_ERROR;
        }

        $output->writeln('App successfully registered or updated in ACP.');

        return static::CODE_SUCCESS;
    }
}
