<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Translation\Builder;

use Transfer\AppTranslationRequestTransfer;
use Transfer\AppTranslationResponseTransfer;

interface AppTranslationBuilderInterface
{
    /**
     * @param \Transfer\AppTranslationRequestTransfer $appTranslationRequestTransfer
     *
     * @return \Transfer\AppTranslationResponseTransfer
     */
    public function createTranslation(AppTranslationRequestTransfer $appTranslationRequestTransfer): AppTranslationResponseTransfer;
}
