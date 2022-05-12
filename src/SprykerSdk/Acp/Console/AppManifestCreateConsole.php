<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Console;

use Generated\Shared\Transfer\ManifestRequestTransfer;
use Generated\Shared\Transfer\ManifestTransfer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \SprykerSdk\Acp\AcpFacadeInterface getFacade()
 */
class AppManifestCreateConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const MANIFEST_NAME = 'name';

    /**
     * @var string
     */
    public const MANIFEST_LOCALE = 'locale';

     /**
      * @var string
      */
    public const OPTION_MANIFEST_PATH = 'manifest-path';

    /**
     * @var string
     */
    public const OPTION_MANIFEST_PATH_SHORT = 'm';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:manifest:create')
            ->setDescription('Adds a Manifest file to the manifest path.')
            ->addArgument(static::MANIFEST_NAME, InputArgument::REQUIRED, 'The name of the Manifest.')
            ->addArgument(static::MANIFEST_LOCALE, InputOption::VALUE_REQUIRED, 'A valid locale e.g.: en_US', 'en_US')
            ->addOption(static::OPTION_MANIFEST_PATH, static::OPTION_MANIFEST_PATH_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultManifestPath());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $manifestTransfer = new ManifestTransfer();
        $manifestTransfer
            ->setName($input->getArgument(static::MANIFEST_NAME))
            ->setLocaleName($input->getArgument(static::MANIFEST_LOCALE));

        $manifestRequestTransfer = new ManifestRequestTransfer();

        $targetManifestPath = $input->getOption(static::OPTION_MANIFEST_PATH);

        $manifestRequestTransfer
            ->setManifest($manifestTransfer)
            ->setManifestPath($targetManifestPath);

        $manifestResponseTransfer = $this->getFacade()->createAppManifest($manifestRequestTransfer);

        if ($manifestResponseTransfer->getErrors()->count() === 0) {
            $this->printMessages($output, $manifestResponseTransfer->getMessages());

            return static::CODE_SUCCESS;
        }

        $this->printMessages($output, $manifestResponseTransfer->getErrors());

        return static::CODE_ERROR;
    }
}
