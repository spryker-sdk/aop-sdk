<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\CheckReadinessConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\ConsoleBootstrap;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use Symfony\Component\Console\Tester\CommandTester;

class CommandHelper extends Module
{
    use AppSdkHelperTrait;
    use BusinessHelperTrait;
    use ConfigHelperTrait;

    /**
     * @param \SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole|string $command
     *
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getConsoleTester($command): CommandTester
    {
        if (!($command instanceof AbstractConsole)) {
            $command = new $command(null, $this->getAppSdkHelper()->getConfig());
        }

        $application = new ConsoleBootstrap();
        $application->add($command);

        $command = $application->find($command->getName());

        return new CommandTester($command);
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Communication\Console\CheckReadinessConsole
     */
    public function createCheckReadinessConsoleCommand(): CheckReadinessConsole
    {
        $this->getConfigHelper()->mockConfigMethod('getRootPath', codecept_data_dir());
        $facade = $this->getBusinessHelper()->getFacade();
        $checkReadinessConsole = new CheckReadinessConsole();
        $checkReadinessConsole->setFacade($facade);

        return $checkReadinessConsole;
    }
}
