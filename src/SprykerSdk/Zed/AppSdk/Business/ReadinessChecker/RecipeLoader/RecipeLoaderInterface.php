<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\ReadinessChecker\RecipeLoader;

use Generated\Shared\Transfer\CheckReadinessTransfer;

interface RecipeLoaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return iterable<\Generated\Shared\Transfer\RecipeTransfer>
     */
    public function loadRecipes(CheckReadinessTransfer $checkReadinessTransfer): iterable;
}
