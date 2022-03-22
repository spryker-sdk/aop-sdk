<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\OpenApi\Builder;

use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiResponseTransfer;

interface OpenApiBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OpenApiRequestTransfer $OpenApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    public function addOpenApi(OpenApiRequestTransfer $OpenApiRequestTransfer): OpenApiResponseTransfer;
}
