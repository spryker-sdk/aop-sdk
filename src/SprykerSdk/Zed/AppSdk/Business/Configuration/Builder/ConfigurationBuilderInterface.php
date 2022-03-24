<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\Configuration\Builder;

use Generated\Shared\Transfer\AppConfigurationRequestTransfer;
use Generated\Shared\Transfer\AppConfigurationResponseTransfer;

interface ConfigurationBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AppConfigurationRequestTransfer $appConfigurationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigurationResponseTransfer
     */
    public function appConfigurationCreate(AppConfigurationRequestTransfer $appConfigurationRequestTransfer): AppConfigurationResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\AppConfigurationRequestTransfer $appConfigurationRequestTransfer
     *
     * @return array
     */
    public function getFormattedProperties(AppConfigurationRequestTransfer $appConfigurationRequestTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\AppConfigurationRequestTransfer $appConfigurationRequestTransfer
     *
     * @return array
     */
    public function getFormattedFieldsets(AppConfigurationRequestTransfer $appConfigurationRequestTransfer): array;
}
