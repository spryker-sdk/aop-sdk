<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Configuration\Builder;

use Transfer\AppConfigurationRequestTransfer;
use Transfer\AppConfigurationResponseTransfer;

interface AppConfigurationBuilderInterface
{
    /**
     * @param \Transfer\AppConfigurationRequestTransfer $appConfigurationRequestTransfer
     *
     * @return \Transfer\AppConfigurationResponseTransfer
     */
    public function createConfiguration(AppConfigurationRequestTransfer $appConfigurationRequestTransfer): AppConfigurationResponseTransfer;
}
