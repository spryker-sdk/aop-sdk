<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Manifest\Builder;

use Transfer\ManifestRequestTransfer;
use Transfer\ManifestResponseTransfer;

interface AppManifestBuilderInterface
{
    /**
     * @param \Transfer\ManifestRequestTransfer $manifestRequestTransfer
     *
     * @return \Transfer\ManifestResponseTransfer
     */
    public function createManifest(ManifestRequestTransfer $manifestRequestTransfer): ManifestResponseTransfer;
}
