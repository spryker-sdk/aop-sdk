<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\ReadinessChecker\Checker;

use Transfer\CheckConfigurationTransfer;
use Transfer\RecipeTransfer;

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
     * @param \Transfer\RecipeTransfer $recipeTransfer
     * @param \Transfer\CheckConfigurationTransfer $checkConfigurationTransfer
     *
     * @return \Transfer\RecipeTransfer
     */
    public function check(RecipeTransfer $recipeTransfer, CheckConfigurationTransfer $checkConfigurationTransfer): RecipeTransfer;
}
