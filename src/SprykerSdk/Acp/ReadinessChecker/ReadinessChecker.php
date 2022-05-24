<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\ReadinessChecker;

use SprykerSdk\Acp\Exception\CheckerNotFoundException;
use SprykerSdk\Acp\ReadinessChecker\Checker\CheckerInterface;
use SprykerSdk\Acp\ReadinessChecker\RecipeLoader\RecipeLoaderInterface;
use Transfer\CheckConfigurationTransfer;
use Transfer\CheckReadinessResponseTransfer;
use Transfer\CheckReadinessTransfer;
use Transfer\RecipeTransfer;

class ReadinessChecker implements ReadinessCheckerInterface
{
    /**
     * @var \SprykerSdk\Acp\ReadinessChecker\RecipeLoader\RecipeLoaderInterface
     */
    protected RecipeLoaderInterface $recipeLoader;

    /**
     * @var array<\SprykerSdk\Acp\ReadinessChecker\Checker\CheckerInterface>
     */
    protected array $checker;

    /**
     * @param \SprykerSdk\Acp\ReadinessChecker\RecipeLoader\RecipeLoaderInterface $recipeLoader
     * @param array<\SprykerSdk\Acp\ReadinessChecker\Checker\CheckerInterface> $checker
     */
    public function __construct(RecipeLoaderInterface $recipeLoader, array $checker)
    {
        $this->recipeLoader = $recipeLoader;
        $this->checker = $checker;
    }

    /**
     * @param \Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return \Transfer\CheckReadinessResponseTransfer
     */
    public function checkReadiness(CheckReadinessTransfer $checkReadinessTransfer): CheckReadinessResponseTransfer
    {
        $checkReadinessResponseTransfer = new CheckReadinessResponseTransfer();
        $checkReadinessResponseTransfer->setIsSuccessful(true);

        return $this->checkReadinessForRecipes($checkReadinessTransfer, $checkReadinessResponseTransfer);
    }

    /**
     * @param \Transfer\CheckReadinessTransfer $checkReadinessTransfer
     * @param \Transfer\CheckReadinessResponseTransfer $checkReadinessResponseTransfer
     *
     * @return \Transfer\CheckReadinessResponseTransfer
     */
    protected function checkReadinessForRecipes(
        CheckReadinessTransfer $checkReadinessTransfer,
        CheckReadinessResponseTransfer $checkReadinessResponseTransfer
    ): CheckReadinessResponseTransfer {
        foreach ($this->recipeLoader->loadRecipes($checkReadinessTransfer) as $recipeTransfer) {
            $recipeTransfer = $this->runChecks($recipeTransfer, $checkReadinessTransfer->getCheckConfigurationOrFail());

            if ($recipeTransfer->getCheckerMessages()->count() !== 0) {
                $checkReadinessResponseTransfer->setIsSuccessful(false);
                $checkReadinessResponseTransfer->addRecipe($recipeTransfer);
            }
        }

        return $checkReadinessResponseTransfer;
    }

    /**
     * @param \Transfer\RecipeTransfer $recipeTransfer
     * @param \Transfer\CheckConfigurationTransfer $checkConfigurationTransfer
     *
     * @return \Transfer\RecipeTransfer
     */
    protected function runChecks(RecipeTransfer $recipeTransfer, CheckConfigurationTransfer $checkConfigurationTransfer): RecipeTransfer
    {
        $checkerRecipe = $recipeTransfer->getLoadedRecipe();

        foreach ($checkerRecipe as $checker => $checkerConfiguration) {
            $checkConfigurationTransfer->setCheckConfiguration($checkerConfiguration);
            $recipeTransfer = $this->getCheckerByName($checker)->check($recipeTransfer, $checkConfigurationTransfer);
        }

        return $recipeTransfer;
    }

    /**
     * @param string $checkerName
     *
     * @throws \SprykerSdk\Acp\Exception\CheckerNotFoundException
     *
     * @return \SprykerSdk\Acp\ReadinessChecker\Checker\CheckerInterface
     */
    protected function getCheckerByName(string $checkerName): CheckerInterface
    {
        foreach ($this->checker as $checker) {
            if ($checker->getName() === $checkerName) {
                return $checker;
            }
        }

        throw new CheckerNotFoundException(sprintf('Could not find a checker by name "%s".', $checkerName));
    }
}
