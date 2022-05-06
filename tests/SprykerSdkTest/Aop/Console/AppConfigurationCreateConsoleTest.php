<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Aop\Console;

use Codeception\Test\Unit;
use SprykerSdk\Aop\Console\AppConfigurationCreateConsole;

/**
 * @group SprykerSdkTest
 * @group Aop
 * @group Console
 * @group AppConfigurationCreateConsoleTest
 */
class AppConfigurationCreateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Aop\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAppConfigurationCreateConsole(): void
    {
        $commandTester = $this->tester->getConsoleTester(AppConfigurationCreateConsole::class);

        // Arrange
        $commandTester->setInputs([
            'Text_Configuration',
            'Yes',
            'Text',
            'String',
            'Yes',
            'Text_Configuration',
            'No',
            'Radio_Configuration',
            'Yes',
            'Radio',
            'Int',
            'Option1',
            'Yes',
            'String',
            'Yes',
            'Option1',
            'Yes',
            'Option2',
            'No',
            'Yes',
            'Checkbox_Configuration',
            'No',
            'Checkbox',
            'Array',
            'Int',
            'Option1',
            'Yes',
            'String',
            'Yes',
            'Option1',
            'Yes',
            'Option2',
            'No',
            'No',
            'Yes',
            'Text_Group',
            'Text_Configuration',
            'Yes',
            'Options Group',
            'Radio_Configuration,Checkbox_Configuration',
        ]);

        // Act
        $commandTester->execute([]);

        // Assert
        $this->assertFileEquals(codecept_data_dir('valid/configuration/console-test-configuration.json'), $this->tester->getRootPath() . '/config/app/configuration/configuration.json');
    }
}
