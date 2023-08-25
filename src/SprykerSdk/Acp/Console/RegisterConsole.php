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
            ->addOption('private', null, InputOption::VALUE_NONE, 'Set this option when this App should be private to you. This requires to pass your Tenant Identifier with the option `--tenant-identifier`.')
            ->addOption('appIdentifier', null, InputOption::VALUE_REQUIRED, 'The App Identifier of your App.')
            ->addOption('tenantIdentifier', null, InputOption::VALUE_REQUIRED, 'When this App needs to be private this option needs to be set to your Tenant Identifier.')
            ->addOption('baseUrl', null, InputOption::VALUE_REQUIRED, 'The Base URL to your App.')
            ->addOption('registryUrl', null, InputOption::VALUE_REQUIRED, 'The URL to your App repository e.g. https://github.com/organization/package.')
            ->addOption('authorizationToken', null, InputOption::VALUE_REQUIRED, 'The Token that is required to be able to send requests to the Registry Service.')
            ->addOption(AppConfigurationValidateConsole::CONFIGURATION_FILE, AppConfigurationValidateConsole::CONFIGURATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultConfigurationFile())
            ->addOption(AppManifestValidateConsole::MANIFEST_PATH, AppManifestValidateConsole::MANIFEST_PATH_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultManifestPath())
            ->addOption(AppTranslationValidateConsole::TRANSLATION_FILE, AppTranslationValidateConsole::TRANSLATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultTranslationFile());
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

        $registerRequestTransfer->setManifestPath($input->getOption(AppManifestValidateConsole::MANIFEST_PATH));
        $registerRequestTransfer->setConfigurationFile($input->getOption(AppConfigurationValidateConsole::CONFIGURATION_FILE));
        $registerRequestTransfer->setTranslationFile($input->getOption(AppTranslationValidateConsole::TRANSLATION_FILE));

        $registerResponseTransfer = $this->getFacade()->registerApp($registerRequestTransfer);

        $message = 'App successfully registered or updated in ACP.';
        $responseCode = static::CODE_SUCCESS;

        if ($registerResponseTransfer->getErrors()->count() > 0) {
            $message = 'Could not register the App in ACP.';
            $responseCode = static::CODE_ERROR;
        }

        if ($output->isVerbose()) {
            $output->writeln($message);
        }

        foreach ($registerResponseTransfer->getErrors() as $errorTransfer) {
            $output->writeln($errorTransfer->getMessageOrFail());
        }

        return $responseCode;
    }
}
