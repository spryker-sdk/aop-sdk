<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\ReadinessChecker\RecipeLoader;

use Transfer\CheckReadinessTransfer;

interface RecipeLoaderInterface
{
    /**
     * @param \Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return iterable<\Transfer\RecipeTransfer>
     */
    public function loadRecipes(CheckReadinessTransfer $checkReadinessTransfer): iterable;
}
