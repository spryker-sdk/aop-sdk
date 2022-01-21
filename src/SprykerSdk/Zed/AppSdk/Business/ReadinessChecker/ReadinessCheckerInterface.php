<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\ReadinessChecker;

use Generated\Shared\Transfer\CheckReadinessResponseTransfer;
use Generated\Shared\Transfer\CheckReadinessTransfer;

interface ReadinessCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return \Generated\Shared\Transfer\CheckReadinessResponseTransfer
     */
    public function checkReadiness(CheckReadinessTransfer $checkReadinessTransfer): CheckReadinessResponseTransfer;
}
