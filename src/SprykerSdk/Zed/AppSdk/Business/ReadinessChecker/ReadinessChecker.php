<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\ReadinessChecker;

use Generated\Shared\Transfer\CheckConfigurationTransfer;
use Generated\Shared\Transfer\CheckReadinessResponseTransfer;
use Generated\Shared\Transfer\CheckReadinessTransfer;
use Generated\Shared\Transfer\RecipeTransfer;
use SprykerSdk\Zed\AppSdk\Business\Exception\CheckerNotFoundExceptionException;
use SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\CheckerInterface;
use SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\RecipeLoader\RecipeLoaderInterface;

class ReadinessChecker implements ReadinessCheckerInterface
{
    /**
     * @var \SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\RecipeLoader\RecipeLoaderInterface
     */
    protected RecipeLoaderInterface $recipeLoader;

    /**
     * @var array<\SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\CheckerInterface>
     */
    protected array $checker;

    /**
     * @param \SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\RecipeLoader\RecipeLoaderInterface $recipeLoader
     * @param array<\SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\CheckerInterface> $checker
     */
    public function __construct(RecipeLoaderInterface $recipeLoader, array $checker)
    {
        $this->recipeLoader = $recipeLoader;
        $this->checker = $checker;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return \Generated\Shared\Transfer\CheckReadinessResponseTransfer
     */
    public function checkReadiness(CheckReadinessTransfer $checkReadinessTransfer): CheckReadinessResponseTransfer
    {
        $checkReadinessResponseTransfer = new CheckReadinessResponseTransfer();
        $checkReadinessResponseTransfer->setIsSuccessful(true);

        return $this->checkReadinessForRecipes($checkReadinessTransfer, $checkReadinessResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckReadinessTransfer $checkReadinessTransfer
     * @param \Generated\Shared\Transfer\CheckReadinessResponseTransfer $checkReadinessResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckReadinessResponseTransfer
     */
    protected function checkReadinessForRecipes(
        CheckReadinessTransfer $checkReadinessTransfer,
        CheckReadinessResponseTransfer $checkReadinessResponseTransfer
    ): CheckReadinessResponseTransfer {
        foreach ($this->recipeLoader->loadRecipes($checkReadinessTransfer) as $recipeTransfer) {
            $recipeTransfer = $this->runChecks($recipeTransfer, $checkReadinessTransfer->getCheckConfigurationOrFail());

            if ($recipeTransfer->getCheckerMessages()->count() !== 0) {
                $checkReadinessResponseTransfer->setIsSuccessful(false);
            }

            $checkReadinessResponseTransfer->addRecipe($recipeTransfer);
        }

        return $checkReadinessResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RecipeTransfer $recipeTransfer
     * @param \Generated\Shared\Transfer\CheckConfigurationTransfer $checkConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\RecipeTransfer
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
     * @throws \SprykerSdk\Zed\AppSdk\Business\Exception\CheckerNotFoundExceptionException
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\Checker\CheckerInterface
     */
    protected function getCheckerByName(string $checkerName): CheckerInterface
    {
        foreach ($this->checker as $checker) {
            if ($checker->getName() === $checkerName) {
                return $checker;
            }
        }

        throw new CheckerNotFoundExceptionException(sprintf('Could not find a checker by name "%s".', $checkerName));
    }
}
