<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Console;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Transfer\CheckConfigurationTransfer;
use Transfer\CheckReadinessResponseTransfer;
use Transfer\CheckReadinessTransfer;
use Transfer\RecipeTransfer;

/**
 * @method \SprykerSdk\Acp\AcpFacadeInterface getFacade()
 */
class CheckReadinessConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const ARGUMENT_CHECK_RECIPE = 'check-recipe';

    /**
     * @var string
     */
    public const OPTION_PROJECT_NAMESPACE = 'project-namespace';

    /**
     * @var string
     */
    public const OPTION_PROJECT_NAMESPACE_SHORT = 'p';

    /**
     * @var string
     */
    public const OPTION_ROOT_PATH = 'root-path';

    /**
     * @var string
     */
    public const OPTION_ROOT_PATH_SHORT = 'r';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('check:readiness')
            ->setDescription('Checks the readiness of a project against recipes.')
            ->addArgument(static::ARGUMENT_CHECK_RECIPE, InputArgument::IS_ARRAY, 'Recipe(s) to use for the readiness check. You can run selected ones or all at once when not using a recipe name.')
            ->addOption(static::OPTION_PROJECT_NAMESPACE, static::OPTION_PROJECT_NAMESPACE_SHORT, InputOption::VALUE_REQUIRED, 'Project namespace that should be used for class and file loading.', 'Pyz')
            ->addOption(static::OPTION_ROOT_PATH, static::OPTION_ROOT_PATH_SHORT, InputOption::VALUE_REQUIRED, 'Project root path that should be used file loading.', getcwd());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $checkReadinessTransfer = $this->createCheckReadinessTransfer($input);

        $checkReadinessResponseTransfer = $this->getFacade()->checkReadiness($checkReadinessTransfer);

        if ($checkReadinessResponseTransfer->getIsSuccessful()) {
            $output->write('No errors found, project seems to be ready.');

            return static::CODE_SUCCESS;
        }

        if ($output->isVerbose()) {
            $this->printReadinessMessages($checkReadinessResponseTransfer, $output);
        }

        return static::CODE_ERROR;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Transfer\CheckReadinessTransfer
     */
    protected function createCheckReadinessTransfer(InputInterface $input): CheckReadinessTransfer
    {
        $recipeNames = (array)$input->getArgument(static::ARGUMENT_CHECK_RECIPE);

        $checkReadinessTransfer = new CheckReadinessTransfer();

        foreach ($recipeNames as $recipeName) {
            $recipeTransfer = new RecipeTransfer();
            $recipeTransfer->setName($recipeName);
            $checkReadinessTransfer->addRecipe($recipeTransfer);
        }

        $checkConfigurationTransfer = new CheckConfigurationTransfer();
        $checkConfigurationTransfer
            ->setProjectNamespace($input->getOption(static::OPTION_PROJECT_NAMESPACE))
            ->setRootPath($input->getOption(static::OPTION_ROOT_PATH));

        $checkReadinessTransfer->setCheckConfiguration($checkConfigurationTransfer);

        return $checkReadinessTransfer;
    }

    /**
     * @param \Transfer\CheckReadinessResponseTransfer $checkReadinessResponseTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printReadinessMessages(CheckReadinessResponseTransfer $checkReadinessResponseTransfer, OutputInterface $output): void
    {
        foreach ($checkReadinessResponseTransfer->getRecipes() as $recipeTransfer) {
            $this->printRecipeInformation($recipeTransfer, $output);
        }
    }

    /**
     * @param \Transfer\RecipeTransfer $recipeTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printRecipeInformation(RecipeTransfer $recipeTransfer, OutputInterface $output): void
    {
        $tableRows = [];

        foreach ($recipeTransfer->getCheckerMessages() as $checkerMessageTransfer) {
            $tableRows[] = [
                $checkerMessageTransfer->getType(),
                $checkerMessageTransfer->getMessage(),
            ];
        }

        $this->renderTable($output, [new TableCell(sprintf('Recipe check result for <fg=yellow>%s</>', $recipeTransfer->getName()), ['colspan' => 2])], $tableRows);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $headers
     * @param array $rows
     *
     * @return void
     */
    protected function renderTable(OutputInterface $output, array $headers, array $rows): void
    {
        $table = new Table($output);
        $table->setHeaders($headers);
        $table->addRows($rows);
        $table->render();
    }
}
