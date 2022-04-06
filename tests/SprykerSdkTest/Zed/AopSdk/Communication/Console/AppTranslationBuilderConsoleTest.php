<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AopSdk\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\AopSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AopSdk\Communication\Console\AppTranslationCreateConsole;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AopSdk
 * @group Communication
 * @group Console
 * @group AppTranslationBuilderConsoleTest
 */
class AppTranslationBuilderConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AopSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAppTranslationCreateFile(): void
    {
        $command = new AppTranslationCreateConsole();
        $commandTester = $this->tester->getConsoleTester(AppTranslationCreateConsole::class);

        $commandTester->setInputs(['credentials', 'de_DE', 'Referenzen', 'Yes', 'de_US', 'Referenzen', 'No', 'No']);

        // Act
        $commandTester->execute(['command' => $command->getName()]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
