<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Codeception\Stub;
use SprykerSdk\Zed\AopSdk\AopSdkConfig;
use SprykerSdk\Zed\AopSdk\Business\AopSdkBusinessFactory;
use SprykerSdk\Zed\AopSdk\Business\AopSdkFacade;
use SprykerSdk\Zed\AopSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\CheckReadinessConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\ConsoleBootstrap;
use Symfony\Component\Console\Tester\CommandTester;

class CommandHelper extends Module
{
    use AopSdkHelperTrait;

    /**
     * @param \SprykerSdk\Zed\AopSdk\Communication\Console\AbstractConsole|string $command
     *
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getConsoleTester($command): CommandTester
    {
        if (!($command instanceof AbstractConsole)) {
            $command = new $command(null, $this->getAopSdkHelper()->getConfig());
        }

        $application = new ConsoleBootstrap();
        $application->add($command);

        $command = $application->find($command->getName());

        return new CommandTester($command);
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Communication\Console\CheckReadinessConsole
     */
    public function createCheckReadinessConsoleCommand(): CheckReadinessConsole
    {
        $configStub = Stub::make(AopSdkConfig::class, [
            'getRootPath' => codecept_data_dir(),
        ]);

        $factory = new AopSdkBusinessFactory();
        $factory->setConfig($configStub);

        $facade = new AopSdkFacade();
        $facade->setFactory($factory);

        $checkReadinessConsole = new CheckReadinessConsole();
        $checkReadinessConsole->setFacade($facade);

        return $checkReadinessConsole;
    }
}
