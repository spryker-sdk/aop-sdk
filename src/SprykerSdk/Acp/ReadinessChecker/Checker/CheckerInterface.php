<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\ReadinessChecker\Checker;

use Generated\Shared\Transfer\CheckConfigurationTransfer;
use Generated\Shared\Transfer\RecipeTransfer;

interface CheckerInterface
{
    /**
     * Returns the name of the Checker. This is used to map from keys in the recipes.
     *
     * @example
     * ```
     * checkName:
     *   bar: baz
     * ```
     * Given this recipe this should return `checkName` to be executed.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Generated\Shared\Transfer\RecipeTransfer $recipeTransfer
     * @param \Generated\Shared\Transfer\CheckConfigurationTransfer $checkConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\RecipeTransfer
     */
    public function check(RecipeTransfer $recipeTransfer, CheckConfigurationTransfer $checkConfigurationTransfer): RecipeTransfer;
}
