<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Manifest\Reader;

use Generated\Shared\Transfer\ManifestCollectionTransfer;
use Generated\Shared\Transfer\ManifestCriteriaTransfer;

interface AppManifestReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ManifestCriteriaTransfer $manifestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ManifestCollectionTransfer
     */
    public function getManifestCollection(ManifestCriteriaTransfer $manifestCriteriaTransfer): ManifestCollectionTransfer;
}
