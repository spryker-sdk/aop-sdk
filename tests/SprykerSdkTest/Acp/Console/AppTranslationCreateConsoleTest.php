<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Acp\Console;

use Codeception\Test\Unit;
use SprykerSdk\Acp\Console\AbstractConsole;
use SprykerSdk\Acp\Console\AppTranslationCreateConsole;
use SprykerSdkTest\Acp\Tester;
use function PHPUnit\Framework\assertStringContainsString;

/**
 * @group SprykerSdkTest
 * @group Acp
 * @group Console
 * @group AppTranslationCreateConsoleTest
 */
class AppTranslationCreateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Acp\Tester
     */
    protected Tester $tester;

    /**
     * @return void
     */
    public function testAsksForLocaleWhenNoLocaleCanBeExtractedFromManifestFiles(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(AppTranslationCreateConsole::class);

        // Act
        $commandTester->setInputs([
            'de_DE', // No locales from manifest found, console asks for one I'd like to add translations for.
            'No', // No missing translations found, console ask if I'd like to add new ones.
            'No', // Process is done once, console asks if I'd like to add translations for another locale.
        ]);
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        assertStringContainsString('Please enter a locale name you would like to define translations for:', $commandTester->getDisplay());
        assertStringContainsString('We haven\'t found any missing translations for the locale de_DE', $commandTester->getDisplay());
        assertStringContainsString('Would you like to add new translations?', $commandTester->getDisplay());
        assertStringContainsString('Would you like to add translations for another locale?', $commandTester->getDisplay());
    }
}
