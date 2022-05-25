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
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

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
    public function testAsksToEnterALocaleWhenNoLocaleCanBeExtractedFromManifestFiles(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(AppTranslationCreateConsole::class);

        // Act
        $commandTester->setInputs([
            'de_DE', // No locales from manifest found, console asks for a locale I'd like to add translations for.
            'No', // No missing translations found, console ask if I'd like to add new ones.
            'No', // Process is done once, console asks if I'd like to add translations for another locale.
        ]);
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('Please enter a locale name you would like to define translations for:', $commandTester->getDisplay());
        $this->assertStringContainsString('We haven\'t found any missing translations for the locale de_DE', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add new translations?', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add translations for another locale?', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testAsksToSelectALocaleWhenLocalesCanBeExtractedFromManifestFiles(): void
    {
        // Arrange
        $this->tester->haveValidManifestFile();
        $commandTester = $this->tester->getConsoleTester(AppTranslationCreateConsole::class);

        // Act
        $commandTester->setInputs([
            'en_US', // Locales from manifest found, console asks to select a locale I'd like to add translations for.
            'No', // No missing translations found, console ask if I'd like to add new ones.
            'No', // Process is done once, console asks if I'd like to add translations for another locale.
        ]);
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('We found the following locales, please select one you\'d like to define translations for:', $commandTester->getDisplay());
        $this->assertStringContainsString('We haven\'t found any missing translations for the locale en_US', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add new translations?', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add translations for another locale?', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testProcessIsRepeatedWhenAskedToAddTranslationsForAnotherLocaleAnsweredWithYes(): void
    {
        // Arrange
        $this->tester->haveValidManifestFiles(['en_US', 'de_DE']);
        $commandTester = $this->tester->getConsoleTester(AppTranslationCreateConsole::class);

        // Act
        $commandTester->setInputs([
            'en_US', // Locales from manifest found, console asks to select a locale I'd like to add translations for.
            'No', // No missing translations found, console ask if I'd like to add new ones.
            'Yes', // Process is done once, console asks if I'd like to add translations for another locale.
            'de_DE', // User wants to add translations for another locale, console asks second time to select a locale I'd like to add translations for.
            'No', // No missing translations found, console ask if I'd like to add new ones.
            'No', // Process is done once, console asks if I'd like to add translations for another locale.
        ]);
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('We found the following locales, please select one you\'d like to define translations for:', $commandTester->getDisplay());
        $this->assertStringContainsString('We haven\'t found any missing translations for the locale en_US', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add new translations?', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add translations for another locale?', $commandTester->getDisplay());
        $this->assertStringContainsString('We found the following locales, please select one you\'d like to define translations for:', $commandTester->getDisplay());
        $this->assertStringContainsString('We haven\'t found any missing translations for the locale de_DE', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add new translations?', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add translations for another locale?', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testAsksForMissingTranslationsUntilAllAreEntered(): void
    {
        // Arrange
        $this->tester->haveManifestsAndConfigurationFile();
        $commandTester = $this->tester->getConsoleTester(AppTranslationCreateConsole::class);

        // Act
        $commandTester->setInputs([
            'en_US', // Locales from manifest found, console asks to select a locale I'd like to add translations for.
            'Foo Bar', // First found missing translation.
            'Baz Bat', // Second found missing translation.
            'No', // Would you like to add new translations?
            'No', // Would you like to add translations for another locale?
        ]);
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('We found the following locales, please select one you\'d like to define translations for:', $commandTester->getDisplay());
        $this->assertStringContainsString('We found missing translations for the locale en_US', $commandTester->getDisplay());
        $this->assertStringContainsString('The following inputs will be used for your selected locale en_US', $commandTester->getDisplay());
        $this->assertStringContainsString('When you like to leave the process hit the Enter key until you see "Left the process, will continue with next steps". Already entered data will be automatically saved.', $commandTester->getDisplay());
        $this->assertStringContainsString('Please enter a translation for foo.bar: ', $commandTester->getDisplay());
        $this->assertStringContainsString('Please enter a translation for baz.bat: ', $commandTester->getDisplay());
        $this->assertStringContainsString('All missing translations for the locale en_US added.', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add new translations?', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add translations for another locale?', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testAsksForMissingTranslationsAndAbortAfterFirstTranslationWasEntered(): void
    {
        // Arrange
        $this->tester->haveManifestsAndConfigurationFile();
        $commandTester = $this->tester->getConsoleTester(AppTranslationCreateConsole::class);

        // Act
        $commandTester->setInputs([
            'en_US', // Locales from manifest found, console asks to select a locale I'd like to add translations for.
            'Foo Bar', // First found missing translation.
            '', // Empty value to leave the current process.
            'No', // Would you like to add new translations?
            'No', // Would you like to add translations for another locale?
        ]);
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('We found the following locales, please select one you\'d like to define translations for:', $commandTester->getDisplay());
        $this->assertStringContainsString('We found missing translations for the locale en_US', $commandTester->getDisplay());
        $this->assertStringContainsString('The following inputs will be used for your selected locale en_US', $commandTester->getDisplay());
        $this->assertStringContainsString('When you like to leave the process hit the Enter key until you see "Left the process, will continue with next steps". Already entered data will be automatically saved.', $commandTester->getDisplay());
        $this->assertStringContainsString('Please enter a translation for foo.bar: ', $commandTester->getDisplay());
        $this->assertStringContainsString('Left the process, will continue with next steps.', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add new translations?', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add translations for another locale?', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testAsksForMissingTranslationsAndAbortWhenNoTranslationWasEntered(): void
    {
        // Arrange
        $this->tester->haveValidManifestFiles(['en_US', 'de_DE']);
        $commandTester = $this->tester->getConsoleTester(AppTranslationCreateConsole::class);

        // Act
        $commandTester->setInputs([
            'en_US', // Locales from manifest found, console asks to select a locale I'd like to add translations for.
            'Yes', // Would you like to add new translations?
            '', // Entering empty value
            'No', // Would you like to add translations for another locale?
        ]);
        $commandTester->execute([]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('We haven\'t found any missing translations for the locale en_US', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add new translations?', $commandTester->getDisplay());
        $this->assertStringContainsString('The following inputs will be used for your selected locale en_US', $commandTester->getDisplay());
        $this->assertStringContainsString('When you like to leave the process hit the Enter key until you see "Left the process, will continue with next steps". Already entered data will be automatically saved.', $commandTester->getDisplay());
        $this->assertStringContainsString('Left the process, will continue with next steps.', $commandTester->getDisplay());
        $this->assertStringContainsString('Would you like to add translations for another locale?', $commandTester->getDisplay());
        $this->assertStringContainsString('We stored the configuration in vfs://root/config/app/translation.json', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testDoHandleSignal(): void
    {
        // Arrange
        $this->tester->mockDirectoryStructure(['translation.json']);

        $console = new AppTranslationCreateConsole();
        $console->setFacade($this->tester->getFacade());
        $console->setConfig($this->tester->getConfig());

        $translations = ['test.key' => 'test.value'];
        $input = new ArrayInput(
            ['--' . AppTranslationCreateConsole::TRANSLATION_FILE => $this->tester->getRootPath() . '/translation.json'],
            $console->getDefinition(),
        );
        $output = new NullOutput();

        // Act
        $exitCode = $console->doHandleSignal($input, $output, \SIGINT, $translations);

        // Assert
        $this->assertEquals(0, $exitCode);

        $this->assertJsonStringEqualsJsonFile(
            $this->tester->getRootPath() . '/translation.json',
            json_encode(['test.key' => 'test.value']),
        );
    }

    /**
     * @return void
     */
    public function testGetSubscribedSignalsShouldReturnSigint(): void
    {
        // Arrange
        $console = new AppTranslationCreateConsole();

        // Act
        $signals = $console->getSubscribedSignals();

        // Assert
        $this->assertEquals([\SIGINT], $signals);
    }
}
