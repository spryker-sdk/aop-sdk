<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerSdk\Zed\AppSdk\Communication\Console\ConsoleBootstrap;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CommandHelper extends Module
{
    /**
     * @param \AppSdk\Command\AbstractCommand|string $command
     *
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getConsoleTester($command): CommandTester
    {
        if (!($command instanceof Command)) {
            $command = new $command(null, $this->getConfig());
        }

        $application = new ConsoleBootstrap();
        $application->add($command);

        $command = $application->find($command->getName());

        return new CommandTester($command);
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\AppSdkConfig
     */
    protected function getConfig(): AppSdkConfig
    {
        return $this->getValidatorHelper()->getConfig() ?? new AppSdkConfig();
    }

    /**
     * @return \SprykerSdkTest\Helper\ValidatorHelper
     */
    protected function getValidatorHelper(): ValidatorHelper
    {
        return $this->getModule('\\' . ValidatorHelper::class);
    }
}
