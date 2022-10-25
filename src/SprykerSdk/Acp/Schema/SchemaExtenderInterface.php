<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Schema;

use Transfer\CreateDefaultEndpointsRequestTransfer;
use Transfer\CreateDefaultEndpointsResponseTransfer;

interface SchemaExtenderInterface
{
    /**
     * @param \Transfer\CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer
     *
     * @return \Transfer\CreateDefaultEndpointsResponseTransfer
     */
    public function extendProjectSchema(CreateDefaultEndpointsRequestTransfer $createDefaultEndpointsRequestTransfer): CreateDefaultEndpointsResponseTransfer;
}
