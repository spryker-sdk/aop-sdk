# Readiness Check

AppSdk has a console command to check if a Project or an App is ready to be used with specific modules. It uses recipes that contains a description of things that need to be done to be ready.

## Command execution
`vendor/bin/aop-sdk check:readiness {recipe-name-a} [--project-namespace FooBar (-p FooBar)] [--root-path /root/path/ (-r /root/path/)]`

### Command options

#### Recipe Argument

The recipe Argument is the first argument of the console command. Recipes are (currently) placed in 'config/CheckRecipes/' and use the YML format.
A recipe thats located in `config/CheckRecipe/my-check-recipe.yml` can be executed by running:

`vendor/bin/aop-sdk check:readiness my-check-recipe`

#### Project namespace Option

This option is used to tell the check tools for which project namespace it should be executed. This is only used when you need to get classes from the project.

An example can be found in the `\SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\PluginsChecker`.

Example configuration:

```
plugins:
    - "\\{projectNamespace}\\Zed\\FooBar\\FooBarDependencyProvider":
        method: "getFooBarPlugins"
        plugins:
            - "\\Spryker\\Zed\\FooBar\\Communication\\Plugins\\BazBat\\FooBarPlugin"
```

To be able to get the plugins installed we need to get the DependencyProvider inside of the project. The placeholder `{projectNamespace}` will be replaced with the passed option from the console command.


#### Root path option (currently not used)

This option can be used to find e.g. Twig templates.

TODO: Implement Twig template finder.

## Recipes

Recipes are written in YML and will contain any kind of configuration for the checker tools. The root key inside of the YML points to a `\SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\CheckerInterface`. The checker classes have a method `getName()` which is used to map from the YML file to this checker.

## Checker

### ComposerChecker

This checks that a package is installed and satisfies a specific version.

```
composer:
    "missing/requirement": "^1.0.0"
```

---

### EnvChecker

This checks that required environment variables are set.

```
env:
    - "ENV_VAR_NAME"
```
---

### PluginsChecker

This checks that:
- A specific DependencyProvider exists
- The DependencyProvider has the method that provides plugins
- Required plugins exists in the returned plugin stack

---

```
plugins:
    - "\\{projectNamespace}\\Zed\\FooBar\\FooBarDependencyProvider":
        method: "getCatFacePlugins"
        plugins:
            - "\\Spryker\\Zed\\FooBar\\Communication\\Plugins\\BazBat\\FooBarPlugin"
```

## Adding a checker

To create a new checker you need to create a class in `src/SprykerSdk/Zed/AopSdk/Business/ReadinessChecker/Checker` that implements the `SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\CheckerInterface`.

This class needs to be added to `\SprykerSdk\Zed\AopSdk\Business\AopSdkBusinessFactory::getReadinessChecker()` to be accesible by the check tool.

The `\SprykerSdk\Zed\AopSdk\Business\ReadinessChecker\Checker\CheckerInterface::check()` method will get a `RecipeTransfer` and a `CheckConfigurationTransfer`. The method must return the `RecipeTransfer` after it was executed.

### Adding messages

TO inform the user of  this console command what he needs to do to make his code ready you need to add `CheckerMessageTransfer` messages to the `RecipeTransfer`.

```
$checkerMessageTransfer = new CheckerMessageTransfer();
$checkerMessageTransfer->setType('error')
    ->setMessage(sprintf('The class "%s" was not found please create one with a method "%s()".', $className, $methodName));

$recipeTransfer->addCheckerMessage($checkerMessageTransfer);
```
