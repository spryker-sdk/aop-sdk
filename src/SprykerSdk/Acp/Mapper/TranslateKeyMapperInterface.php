<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Mapper;

use Transfer\ManifestCollectionTransfer;

interface TranslateKeyMapperInterface
{
    /**
     * @param \Transfer\ManifestCollectionTransfer $manifestCollectionTransfer
     *
     * @return array
     */
    public function mapManifestCollectionToTranslateKeys(ManifestCollectionTransfer $manifestCollectionTransfer): array;
}
