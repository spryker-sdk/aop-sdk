<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\ReadinessChecker\Checker;

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;
use Transfer\CheckConfigurationTransfer;
use Transfer\CheckerMessageTransfer;
use Transfer\RecipeTransfer;

class ComposerChecker implements CheckerInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'composer';
    }

    /**
     * @param \Transfer\RecipeTransfer $recipeTransfer
     * @param \Transfer\CheckConfigurationTransfer $checkConfigurationTransfer
     *
     * @return \Transfer\RecipeTransfer
     */
    public function check(RecipeTransfer $recipeTransfer, CheckConfigurationTransfer $checkConfigurationTransfer): RecipeTransfer
    {
        foreach ($checkConfigurationTransfer->getCheckConfiguration() as $package => $expectedVersion) {
            if (!InstalledVersions::isInstalled($package)) {
                $checkerMessageTransfer = new CheckerMessageTransfer();
                $checkerMessageTransfer->setType('error')
                    ->setMessage(sprintf('Required package "%1$s" was not found. Please install it with "composer install %1$s".', $package));

                $recipeTransfer->addCheckerMessage($checkerMessageTransfer);

                return $recipeTransfer;
            }

            if (!InstalledVersions::satisfies(new VersionParser(), $package, $expectedVersion)) {
                $checkerMessageTransfer = new CheckerMessageTransfer();
                $checkerMessageTransfer->setType('error')
                    ->setMessage(sprintf('Required package "%s" does not satisfy the expected version "%s". Please update your composer dependencies.', $package, $expectedVersion));

                $recipeTransfer->addCheckerMessage($checkerMessageTransfer);
            }
        }

        return $recipeTransfer;
    }
}
