<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\ReadinessChecker\Checker;

use Transfer\CheckConfigurationTransfer;
use Transfer\CheckerMessageTransfer;
use Transfer\RecipeTransfer;

class EnvChecker implements CheckerInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'env';
    }

    /**
     * @param \Transfer\RecipeTransfer $recipeTransfer
     * @param \Transfer\CheckConfigurationTransfer $checkConfigurationTransfer
     *
     * @return \Transfer\RecipeTransfer
     */
    public function check(RecipeTransfer $recipeTransfer, CheckConfigurationTransfer $checkConfigurationTransfer): RecipeTransfer
    {
        foreach ($checkConfigurationTransfer->getCheckConfiguration() as $envVariable) {
            if (!getenv($envVariable)) {
                $checkerMessageTransfer = new CheckerMessageTransfer();
                $checkerMessageTransfer->setType('error')
                    ->setMessage(sprintf('The env variable "%s" does not exists, please add it.', $envVariable));

                $recipeTransfer->addCheckerMessage($checkerMessageTransfer);
            }
        }

        return $recipeTransfer;
    }
}
