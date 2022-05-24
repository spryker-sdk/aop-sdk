<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\ReadinessChecker\RecipeLoader;

use SprykerSdk\Acp\AcpConfig;
use SprykerSdk\Acp\Exception\RecipeNotFoundException;
use Symfony\Component\Yaml\Yaml;
use Transfer\CheckReadinessTransfer;
use Transfer\RecipeTransfer;

class RecipeLoader implements RecipeLoaderInterface
{
    /**
     * @var \SprykerSdk\Acp\AcpConfig
     */
    protected AcpConfig $config;

    /**
     * @param \SprykerSdk\Acp\AcpConfig $config
     */
    public function __construct(AcpConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return iterable<\Transfer\RecipeTransfer>
     */
    public function loadRecipes(CheckReadinessTransfer $checkReadinessTransfer): iterable
    {
        foreach ($checkReadinessTransfer->getRecipes() as $recipeTransfer) {
            yield $this->loadRecipe($recipeTransfer);
        }
    }

    /**
     * @param \Transfer\RecipeTransfer $recipeTransfer
     *
     * @return \Transfer\RecipeTransfer
     */
    protected function loadRecipe(RecipeTransfer $recipeTransfer): RecipeTransfer
    {
        $loadedRecipeYml = $this->load($recipeTransfer->getNameOrFail());

        $loadedRecipeArray = Yaml::parse($loadedRecipeYml);

        $recipeTransfer->setLoadedRecipe($loadedRecipeArray);

        return $recipeTransfer;
    }

    /**
     * @param string $recipeName
     *
     * @throws \SprykerSdk\Acp\Exception\RecipeNotFoundException
     *
     * @return string
     */
    protected function load(string $recipeName): string
    {
        $pathToRecipe = sprintf('%s/%s.yml', $this->config->getPathToCheckRecipes(), $recipeName);

        if (!file_exists($pathToRecipe)) {
            throw new RecipeNotFoundException(sprintf('Could not load recipe by name "%s".', $recipeName));
        }

        return (string)file_get_contents($pathToRecipe);
    }
}
