<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Codeception\Stub;
use SprykerSdk\Aop\AopConfig;
use SprykerSdk\Aop\AopFacade;
use SprykerSdk\Aop\AopFactory;
use SprykerSdk\Aop\Console\AbstractConsole;
use SprykerSdk\Aop\Console\CheckReadinessConsole;
use SprykerSdk\Aop\Console\ConsoleBootstrap;
use Symfony\Component\Console\Tester\CommandTester;

class CommandHelper extends Module
{
    use AopSdkHelperTrait;

    /**
     * @param \SprykerSdk\Aop\Console\AbstractConsole|string $command
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
     * @return \SprykerSdk\Aop\Console\CheckReadinessConsole
     */
    public function createCheckReadinessConsoleCommand(): CheckReadinessConsole
    {
        $configStub = Stub::make(AopConfig::class, [
            'getRootPath' => codecept_data_dir(),
        ]);

        $factory = new AopFactory();
        $factory->setConfig($configStub);

        $facade = new AopFacade();
        $facade->setFactory($factory);

        $checkReadinessConsole = new CheckReadinessConsole();
        $checkReadinessConsole->setFacade($facade);

        return $checkReadinessConsole;
    }
}
