<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\AppTranslationBuilderConsole;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AppSdk
 * @group Communication
 * @group Console
 * @group AppTranslationBuilderConsoleTest
 */
class AppTranslationBuilderConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAppTranslationCreateFile(): void
    {
        $command = new AppTranslationBuilderConsole();
        $commandTester = $this->tester->getConsoleTester(AppTranslationBuilderConsole::class);

        $commandTester->setInputs(['credentials', 'de_DE', 'Referenzen', 'Yes', 'de_US', 'Referenzen', 'No', 'No']);

        // Act
        $commandTester->execute(['command' => $command->getName()]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
