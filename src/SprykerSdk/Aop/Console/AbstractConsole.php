<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Aop\Console;

use ArrayObject;
use SprykerSdk\Aop\AopConfig;
use SprykerSdk\Aop\AopFacade;
use SprykerSdk\Aop\AopFacadeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class AbstractConsole extends Command
{
    /**
     * @var int
     */
    public const CODE_SUCCESS = 0;

    /**
     * @var int
     */
    public const CODE_ERROR = 1;

    /**
     * @var \SprykerSdk\Aop\AopConfig|null
     */
    protected ?AopConfig $config = null;

    /**
     * @var \SprykerSdk\Aop\AopFacadeInterface|null
     */
    protected ?AopFacadeInterface $facade = null;

    /**
     * @param string|null $name
     * @param \SprykerSdk\Aop\AopConfig|null $config
     */
    public function __construct(?string $name = null, ?AopConfig $config = null)
    {
        $this->config = $config;

        parent::__construct($name);
    }

    /**
     * @codeCoverageIgnore
     *
     * @param \SprykerSdk\Aop\AopConfig $config
     *
     * @return void
     */
    public function setConfig(AopConfig $config): void
    {
        $this->config = $config;
    }

    /**
     * @return \SprykerSdk\Aop\AopConfig
     */
    protected function getConfig(): AopConfig
    {
        if ($this->config === null) {
            $this->config = new AopConfig();
        }

        return $this->config;
    }

    /**
     * @param \SprykerSdk\Aop\AopFacadeInterface $facade
     *
     * @return void
     */
    public function setFacade(AopFacadeInterface $facade): void
    {
        $this->facade = $facade;
    }

    /**
     * @return \SprykerSdk\Aop\AopFacadeInterface
     */
    protected function getFacade(): AopFacadeInterface
    {
        if ($this->facade === null) {
            $this->facade = new AopFacade();
        }

        return $this->facade;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @return void
     */
    protected function printMessages(OutputInterface $output, ArrayObject $messageTransfers): void
    {
        if ($output->isVerbose()) {
            foreach ($messageTransfers as $messageTransfer) {
                $output->writeln($messageTransfer->getMessageOrFail());
            }
        }
    }
}
