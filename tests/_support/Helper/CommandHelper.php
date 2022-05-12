<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Codeception\Stub;
use SprykerSdk\Acp\AcpConfig;
use SprykerSdk\Acp\AcpFacade;
use SprykerSdk\Acp\AcpFactory;
use SprykerSdk\Acp\Console\AbstractConsole;
use SprykerSdk\Acp\Console\CheckReadinessConsole;
use SprykerSdk\Acp\Console\ConsoleBootstrap;
use Symfony\Component\Console\Tester\CommandTester;

class CommandHelper extends Module
{
    use AcpHelperTrait;

    /**
     * @param \SprykerSdk\Acp\Console\AbstractConsole|string $command
     *
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getConsoleTester($command): CommandTester
    {
        if (!($command instanceof AbstractConsole)) {
            $command = new $command(null, $this->getAcpHelper()->getConfig());
        }

        $application = new ConsoleBootstrap();
        $application->add($command);

        $command = $application->find($command->getName());

        return new CommandTester($command);
    }

    /**
     * @return \SprykerSdk\Acp\Console\CheckReadinessConsole
     */
    public function createCheckReadinessConsoleCommand(): CheckReadinessConsole
    {
        $configStub = Stub::make(AcpConfig::class, [
            'getRootPath' => codecept_data_dir(),
        ]);

        $factory = new AcpFactory();
        $factory->setConfig($configStub);

        $facade = new AcpFacade();
        $facade->setFactory($factory);

        $checkReadinessConsole = new CheckReadinessConsole();
        $checkReadinessConsole->setFacade($facade);

        return $checkReadinessConsole;
    }
}
