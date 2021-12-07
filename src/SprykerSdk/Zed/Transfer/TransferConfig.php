<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\Transfer;

use Spryker\Zed\Transfer\TransferConfig as SprykerTransferConfig;

class TransferConfig extends SprykerTransferConfig
{
    /**
     * We don't wont to use any core Transfer, skipping them.
     *
     * @return array
     */
    protected function getCoreSourceDirectoryGlobPatterns(): array
    {
        return [];
    }

    /**
     * This will enable strict validation for transfer names upon generation.
     * The suffix "Transfer" is auto-appended and must not be inside the XML definitions.
     *
     * Defaults to false for BC reasons. Enable on project level if all modules in question
     * have been upgraded to the version they are fixed in.
     *
     * @api
     *
     * @return bool
     */
    public function isTransferNameValidated(): bool
    {
        return true;
    }

    /**
     * This will enable strict validation for case sensitive declaration.
     * Mainly for property names, and singular definition.
     *
     * Defaults to false for BC reasons. Enable on project level if all modules in question
     * have been upgraded to the version they are fixed in.
     *
     * @api
     *
     * @return bool
     */
    public function isCaseValidated(): bool
    {
        return true;
    }

    /**
     * This will enable strict validation for collections and singular definition.
     * The singular here is important to specify to avoid it being generated without inflection.
     *
     * Defaults to false for BC reasons. Enable on project level if all modules in question
     * have been upgraded to the version they comply with this rule.
     *
     * @api
     *
     * @return bool
     */
    public function isSingularRequired(): bool
    {
        return true;
    }

    /**
     * Specification:
     * - When enabled, all the available transfer XML files will be checked for validity during transfer validation.
     *
     * @api
     *
     * @return bool
     */
    public function isTransferXmlValidationEnabled(): bool
    {
        return true;
    }
}
