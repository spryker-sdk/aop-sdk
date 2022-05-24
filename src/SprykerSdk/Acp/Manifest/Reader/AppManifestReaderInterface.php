<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Manifest\Reader;

use Transfer\ManifestCollectionTransfer;
use Transfer\ManifestCriteriaTransfer;

interface AppManifestReaderInterface
{
    /**
     * @param \Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return \Transfer\ManifestCollectionTransfer
     */
    public function getManifestCollection(ManifestCriteriaTransfer $manifestCriteriaTransfer): ManifestCollectionTransfer;
}
