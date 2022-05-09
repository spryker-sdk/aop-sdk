<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Aop\ReadinessChecker\RecipeLoader;

use Generated\Shared\Transfer\CheckReadinessTransfer;
use Generated\Shared\Transfer\RecipeTransfer;
use SprykerSdk\Aop\AopConfig;
use SprykerSdk\Aop\Exception\RecipeNotFoundException;
use Symfony\Component\Yaml\Yaml;

class RecipeLoader implements RecipeLoaderInterface
{
    /**
     * @var \SprykerSdk\Aop\AopConfig
     */
    protected AopConfig $config;

    /**
     * @param \SprykerSdk\Aop\AopConfig $config
     */
    public function __construct(AopConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckReadinessTransfer $checkReadinessTransfer
     *
     * @return iterable<\Generated\Shared\Transfer\RecipeTransfer>
     */
    public function loadRecipes(CheckReadinessTransfer $checkReadinessTransfer): iterable
    {
        foreach ($checkReadinessTransfer->getRecipes() as $recipeTransfer) {
            yield $this->loadRecipe($recipeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RecipeTransfer $recipeTransfer
     *
     * @return \Generated\Shared\Transfer\RecipeTransfer
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
     * @throws \SprykerSdk\Aop\Exception\RecipeNotFoundException
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
