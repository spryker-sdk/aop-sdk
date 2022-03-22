<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Communication\Console;

use Generated\Shared\Transfer\AppConfigurationRequestTransfer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * @method \SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface getFacade()
 */
class AppConfigurationCreateConsole extends AbstractConsole
{
 /**
  * @var string
  */
    protected $properties;

    /**
     * @var string
     */
    protected $fieldset;

    /**
     * @var string
     */
    protected $required;

    /**
     * @var string
     */
    public const CONFIGURATION_FILE = 'configuration-file';

    /**
     * @var string
     */
    public const CONFIGURATION_FILE_SHORT = 'c';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:configuration:create')
            ->setDescription('Create configuration file.')
            ->addOption(static::CONFIGURATION_FILE, static::CONFIGURATION_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultConfigurationFile());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $appConfigurationRequestTransfer = new AppConfigurationRequestTransfer();

        $appConfigurationRequestTransfer->setConfigurationFile($input->getOption(static::CONFIGURATION_FILE));

        $this->getPropertiesInput($input, $output);

        $this->getFieldsetInput($input, $output);

        $appConfigurationRequestTransfer->setProperties([
            'properties' => $this->properties,
            'required' => $this->required,
            'fieldsets' => $this->fieldset]);

        $appConfigurationResponseTransfer = $this->getFacade()->appConfigurationCreate($appConfigurationRequestTransfer);

        if ($appConfigurationResponseTransfer->getErrors()->count() === 0) {
            return static::CODE_SUCCESS;
        }

        // @codeCoverageIgnoreStart
        if ($output->isVerbose()) {
            foreach ($appConfigurationResponseTransfer->getErrors() as $error) {
                $output->writeln($error->getMessageOrFail());
            }
        }

        return static::CODE_ERROR;
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $questionText
     *
     * @return string
     */
    protected function askTextQuestion(InputInterface $input, OutputInterface $output, string $questionText): string
    {
        return $this->getHelper('question')->ask($input, $output, new Question($questionText));
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $questionText
     * @param array $questionOptions
     * @param string|int $defaultSelected
     *
     * @return string
     */
    protected function askChoiceQuestion(
        InputInterface $input,
        OutputInterface $output,
        string $questionText,
        array $questionOptions,
        $defaultSelected
    ): string {
        return $this->getHelper('question')->ask($input, $output, new ChoiceQuestion($questionText, $questionOptions, $defaultSelected));
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $questionText
     * @param array $questionOptions
     * @param string|int $defaultSelected
     *
     * @return array
     */
    protected function askMultipleChoiceQuestion(
        InputInterface $input,
        OutputInterface $output,
        string $questionText,
        array $questionOptions,
        $defaultSelected
    ): array {
        $question = new ChoiceQuestion($questionText, $questionOptions, $defaultSelected);
        $question->setMultiselect(true);

        return $this->getHelper('question')->ask($input, $output, $question);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function getPropertiesInput(InputInterface $input, OutputInterface $output): void
    {
        do {
            $propertyName = $this->setPropertyName($this->askTextQuestion($input, $output, $this->getPropertyNameQuestion()));

            $this->setIsRequired(
                $propertyName,
                $this->askChoiceQuestion($input, $output, $this->getIsRequiredQuestion(), $this->getConfirmationOptions(), 0),
            );

            $this->setWidget(
                $input,
                $output,
                $propertyName,
                $this->askChoiceQuestion($input, $output, $this->getWidgetQuestion(), $this->getWidgetOptions(), 0),
            );
        } while ($this->askChoiceQuestion($input, $output, $this->getNewConfigurationConfirmation(), $this->getConfirmationOptions(), 0) == 'Yes');
    }

    /**
     * @return array
     */
    protected function getConfirmationOptions(): array
    {
        return ['Yes', 'No'];
    }

    /**
     * @return string
     */
    protected function getPropertyNameQuestion(): string
    {
        return 'Please enter a name: ';
    }

    /**
     * @param string $propertyName
     *
     * @return string
     */
    protected function setPropertyName(string $propertyName): string
    {
        $this->properties[$propertyName] = ['placeholder' => $propertyName];

        return $propertyName;
    }

    /**
     * @return string
     */
    protected function getIsRequiredQuestion(): string
    {
        return 'Is this a required field?';
    }

    /**
     * @param string $propertyName
     * @param string $required
     *
     * @return void
     */
    protected function setIsRequired(string $propertyName, string $required): void
    {
        $this->properties[$propertyName]['isRequired'] = ($required == 'Yes') ? true : false;
        $this->required[] = $propertyName;
    }

    /**
     * @return string
     */
    protected function getWidgetQuestion(): string
    {
        return 'Please select a widget: ';
    }

    /**
     * @return string
     */
    protected function getMoreOptionConfirmation(): string
    {
        return 'Do you want to add more options?';
    }

    /**
     * @return string
     */
    protected function getNewConfigurationConfirmation(): string
    {
        return 'Do you want to add more configurations?';
    }

    /**
     * @return string
     */
    protected function getWidgetOptions(): string
    {
        return ['Text', 'Radio', 'Checkbox'];
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $propertyName
     * @param string $widget
     *
     * @return void
     */
    protected function setWidget(InputInterface $input, OutputInterface $output, string $propertyName, string $widget): void
    {
        $this->properties[$propertyName]['widget']['id'] = $widget;
        switch ($widget) {
            case 'Checkbox':
                $this->getWidgetCheckBoxOptions($input, $output, $propertyName);

                break;
            case 'Radio':
                $this->getWidgetRadioOptions($input, $output, $propertyName);

                break;
            case 'Text':
                $this->getWidgetTextOption($input, $output, $propertyName);

                break;
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $propertyName
     *
     * @return void
     */
    protected function getWidgetCheckBoxOptions(InputInterface $input, OutputInterface $output, string $propertyName): void
    {
        $this->setPropertyType(
            $propertyName,
            $this->askChoiceQuestion($input, $output, $this->getWidgetTypeQuestion(), $this->getWidgetCheckBoxTypeOptions(), 0),
        );

        $this->setItemsType(
            $propertyName,
            $this->askChoiceQuestion($input, $output, $this->getItemTypeQuestion(), $this->getWidgetTextAndRadioTypeOptions(), 0),
        );

        do {
            $this->setWidgetCheckBoxTypeItem(
                $input,
                $output,
                $propertyName,
                $this->askTextQuestion($input, $output, $this->getWidgetOptionInputQuestion()),
            );
        } while ($this->askChoiceQuestion($input, $output, $this->getMoreOptionConfirmation(), $this->getConfirmationOptions(), 0) == 'Yes');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $propertyName
     *
     * @return void
     */
    protected function getWidgetRadioOptions(InputInterface $input, OutputInterface $output, string $propertyName): void
    {
        $this->setPropertyType(
            $propertyName,
            $this->askChoiceQuestion($input, $output, $this->getWidgetTypeQuestion(), $this->getWidgetTextAndRadioTypeOptions(), 0),
        );

        do {
            $this->setWidgetRadioTypeItem(
                $input,
                $output,
                $propertyName,
                $this->askTextQuestion($input, $output, $this->getWidgetOptionInputQuestion()),
            );
        } while ($this->askChoiceQuestion($input, $output, $this->getMoreOptionConfirmation(), $this->getConfirmationOptions(), 0) == 'Yes');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $propertyName
     *
     * @return void
     */
    protected function getWidgetTextOption(InputInterface $input, OutputInterface $output, string $propertyName): void
    {
        $this->setPropertyType(
            $propertyName,
            $this->askChoiceQuestion($input, $output, $this->getWidgetTypeQuestion(), $this->getWidgetTextAndRadioTypeOptions(), 0),
        );

        $this->setTextTypeItem($propertyName, 'textline');
    }

    /**
     * @param string $propertyName
     * @param string $type
     *
     * @return void
     */
    protected function setPropertyType(string $propertyName, string $type): void
    {
        $this->properties[$propertyName]['type'] = $type;
    }

    /**
     * @param string $propertyName
     * @param string $type
     *
     * @return void
     */
    protected function setItemsType(string $propertyName, string $type): void
    {
        $this->properties[$propertyName]['items']['type'] = $type;
        $this->properties[$propertyName]['items']['widget']['id'] = $type;
    }

    /**
     * @return string
     */
    protected function getWidgetTypeQuestion(): string
    {
        return 'Please select a type: ';
    }

    /**
     * @return string
     */
    protected function getItemTypeQuestion(): string
    {
        return 'Please select a item type: ';
    }

    /**
     * @return array
     */
    protected function getWidgetTextAndRadioTypeOptions(): string
    {
        return ['String', 'Integer'];
    }

    /**
     * @return array
     */
    protected function getWidgetCheckBoxTypeOptions(): string
    {
        return ['Array'];
    }

    /**
     * @return string
     */
    protected function getWidgetOptionInputQuestion(): string
    {
        return 'Please enter an option: ';
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $propertyName
     * @param string $item
     *
     * @return void
     */
    protected function setWidgetCheckBoxTypeItem(InputInterface $input, OutputInterface $output, string $propertyName, string $item): void
    {
        if ($this->checkPropertyCheckboxTypeValue($input, $output, $propertyName, $item) === true) {
            $this->properties[$propertyName]['items']['oneOf'][] = [
                'description' => $item,
                'enum' => [$item],
            ];
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $propertyName
     * @param string $item
     *
     * @return bool
     */
    protected function checkPropertyCheckboxTypeValue(InputInterface $input, OutputInterface $output, string $propertyName, string $item): bool
    {
        if ($this->properties[$propertyName]['items']['type'] === 'Integer' && !is_numeric($item)) {
            if ($this->askChoiceQuestion($input, $output, $this->getTypeSwitchQuestion(), $this->getConfirmationOptions(), 0) == 'Yes') {
                $this->setItemsType(
                    $propertyName,
                    $this->askChoiceQuestion($input, $output, $this->getItemTypeQuestion(), $this->getWidgetTextAndRadioTypeOptions(), 0),
                );
            }

            return false;
        }

        return true;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $propertyName
     * @param string $item
     *
     * @return void
     */
    protected function setWidgetRadioTypeItem(InputInterface $input, OutputInterface $output, string $propertyName, string $item): void
    {
        if ($this->checkPropertyRadioTypeValue($input, $output, $propertyName, $item) === true) {
            $this->properties[$propertyName]['oneOf'][] = [
                'description' => $item,
                'enum' => [$item],
            ];
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $propertyName
     * @param string $item
     *
     * @return void
     */
    protected function checkPropertyRadioTypeValue(InputInterface $input, OutputInterface $output, string $propertyName, string $item): void
    {
        if ($this->properties[$propertyName]['type'] === 'Integer' && !is_numeric($item)) {
            if ($this->askChoiceQuestion($input, $output, $this->getTypeSwitchQuestion(), $this->getConfirmationOptions(), 0) == 'Yes') {
                $this->setPropertyType(
                    $propertyName,
                    $this->askChoiceQuestion($input, $output, $this->getWidgetTypeQuestion(), $this->getWidgetTextAndRadioTypeOptions(), 0),
                );
            }

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    protected function getTypeSwitchQuestion(): string
    {
        return 'You entered string value while int expected. Do you want to switch type?';
    }

    /**
     * @param string $propertyName
     * @param string $type
     *
     * @return void
     */
    protected function setTextTypeItem(string $propertyName, string $type): void
    {
        $this->properties[$propertyName]['widget']['id'] = $type;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function getFieldsetInput(InputInterface $input, OutputInterface $output): void
    {
        if ($this->askChoiceQuestion($input, $output, $this->getGroupConfirmationQuestion(), $this->getConfirmationOptions(), 0) == 'Yes') {
            $fieldsetOptions = array_keys($this->properties);

            do {
                $groupIndex = $this->setGroupName($this->askTextQuestion($input, $output, $this->getGroupNameQuestion()));

                $fieldsetOptions = array_diff($fieldsetOptions, $this->setGroupFields(
                    $groupIndex,
                    $this->askMultipleChoiceQuestion($input, $output, $this->getGroupSelectFieldsQuestion(), $fieldsetOptions, ''),
                ));
            } while ($fieldsetOptions && $this->askChoiceQuestion($input, $output, $this->getAddMoreGroupConfigurationQuestion(), $this->getConfirmationOptions(), 0) == 'Yes');
        }
    }

    /**
     * @return string
     */
    protected function getGroupConfirmationQuestion(): string
    {
        return 'Do you want to group the configurations?';
    }

    /**
     * @return string
     */
    protected function getGroupNameQuestion(): string
    {
        return 'Please enter a group name: ';
    }

    /**
     * @return string
     */
    protected function getGroupSelectFieldsQuestion(): string
    {
        return 'Please select all fields that should be in this group';
    }

    /**
     * @return string
     */
    protected function getAddMoreGroupConfigurationQuestion(): string
    {
        return 'Do you want to add more group configurations?';
    }

    /**
     * @param string $groupName
     *
     * @return int
     */
    protected function setGroupName(string $groupName): int
    {
        $this->fieldset[] = [
            'id' => $groupName,
            'title' => $groupName,
        ];

        return count($this->fieldset) - 1;
    }

    /**
     * @param int $groupIndex
     * @param array $fields
     *
     * @return array
     */
    protected function setGroupFields(int $groupIndex, array $fields): array
    {
        return $this->fieldset[$groupIndex]['fields'] = $fields;
    }
}
