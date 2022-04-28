<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\Operation;
use cebe\openapi\spec\PathItem;
use cebe\openapi\spec\Reference;
use cebe\openapi\spec\Schema;
use Doctrine\Inflector\Inflector;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiResponseTransfer;
use SprykerSdk\Zed\AopSdk\AopSdkConfig;
use Symfony\Component\Process\Process;

class OpenApiCodeBuilder implements OpenApiCodeBuilderInterface
{
    /**
     * @var string
     */
    protected const SPRYKER = 'Spryker';

    /**
     * @var string
     */
    protected const CORE = 'core';

    /**
     * @var \SprykerSdk\Zed\AopSdk\AopSdkConfig
     */
    protected AopSdkConfig $config;

    /**
     * @var \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    protected OpenApiResponseTransfer $openApiResponseTransfer;

    /**
     * @var \Doctrine\Inflector\Inflector
     */
    protected Inflector $inflector;

    /**
     * @var string
     */
    protected string $sprykMode = 'project';

    /**
     * @var array
     */
    protected array $parseParameters = [];

    /**
     * @param \SprykerSdk\Zed\AopSdk\AopSdkConfig $config
     * @param \Doctrine\Inflector\Inflector $inflector
     */
    public function __construct(AopSdkConfig $config, Inflector $inflector)
    {
        $this->config = $config;
        $this->inflector = $inflector;
        $this->openApiResponseTransfer = new OpenApiResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiRequestTransfer $openApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    public function build(OpenApiRequestTransfer $openApiRequestTransfer): OpenApiResponseTransfer
    {
        //Get load ApiRequest Traget File to process - yml file
        $openApi = $this->load($openApiRequestTransfer->getTargetFileOrFail());

        //Set default value for organization
        $organization = $openApiRequestTransfer->getOrganizationOrFail();
        if ($organization === static::SPRYKER) {
            $this->sprykMode = static::CORE; //Set sprykMode based on organization
        }

        //Main code logic to generate command based on input file or default given file
        $this->parserOpenApiDefinitions($organization, $openApi);

        //return response transfer object with messages
        return $this->openApiResponseTransfer;
    }

    /**
     * @param string $openApiFilePath
     *
     * @return \cebe\openapi\spec\OpenApi
     */
    public function load(string $openApiFilePath): OpenApi
    {
        //Read yml file from given path
        return Reader::readFromYamlFile((string)realpath($openApiFilePath));
    }

    /**
     * To get Module name using path
     *
     * @param string $path
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return string
     */
    protected function getModuleName(string $path, Operation $operation): string
    {
        if (isset($operation->operationId)) { //Check operationId existing or not
            $operationId = explode('.', $operation->operationId);

            //If operationId exist generate controller nam using operation Id
            return $this->inflector->classify(current($operationId));
        }
        //This code block will get executed only in case when Module name is empty
        if ($path === '') { //Set error message
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf('Module name not found for path %s', $path));
            $this->openApiResponseTransfer->addError($messageTransfer);

            return '';
        }
        //Get Module name using path string with fragments
        $pathFragments = explode('/', trim($path, '/'));

        return sprintf(ucwords(current($pathFragments)) . '%s', 'Api');
    }

    /**
     * Get Controller name based on business logic
     *
     * @param string $path
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return string
     */
    protected function getControllerName(string $path, Operation $operation): string
    {
        if (isset($operation->operationId)) { //Check operationId existing or not
            $operationId = explode('.', $operation->operationId);

            //If operationId exist generate controller nam using operation Id
            return $this->inflector->classify(sprintf(end($operationId) . '%s', 'Controller'));
        }

        //Exploding path to fragments based on '/'
        $pathFragments = explode('/', trim($path, '/'));
        foreach (array_reverse($pathFragments) as $key => $resource) { //Loop over path fragments
            //check if there is only single fragment then it will be -> Apps + Resource, employee + Resource
            //Example - In above case controller name will be AppsResourceController
            if ($key === (count($pathFragments) - 1)) {
                $resource = sprintf($resource . '%s', 'Resource');
            }
            //Checks to explode path parameter having dynamic value like - /apps/{appId}
            //Example - In above case controller name will be AppsController
            if (strpos($resource, '{') === false) {
                return $this->inflector->classify(sprintf("{$resource}%s", 'Controller'));
            }
        }

        //This code block will get executed only in case when controller name is empty
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setMessage(sprintf('Controller name not found for path %s', $path));
        $this->openApiResponseTransfer->addError($messageTransfer);

        return '';
    }

    /**
     * @param string $organization
     * @param \cebe\openapi\spec\OpenApi $openApi
     *
     * @return void
     */
    protected function parserOpenApiDefinitions(
        string $organization,
        OpenApi $openApi
    ): void {
        $parseParameters = [];
        /** @var \cebe\openapi\spec\PathItem $pathItem */
        foreach ($openApi->paths->getPaths() as $path => $pathItem) {
            //Parse data based on api path item
            $parseParameters[$path] = $this->getParasedDataFromPathItems($path, $pathItem);
        }

        //Checking error added in response transfer or not
        if ($this->openApiResponseTransfer->getErrors()->count() === 0) {
            //Generating Commands using parsed data and organization
            $this->generateTransfers($organization, $parseParameters);
        }
    }

    /**
     * @param string $path
     * @param \cebe\openapi\spec\PathItem $pathItem
     *
     * @return array
     */
    protected function getParasedDataFromPathItems(string $path, PathItem $pathItem): array
    {
        //Intialize array
        $parseParameters = [];

        //Loop over operations including method type of request
        /** @var \cebe\openapi\spec\Operation $operation */
        foreach ($pathItem->getOperations() as $method => $operation) {
            //Get Controller name
            $parseParameters[$method]['controllerName'] = $this->getControllerName($path, $operation);
            //Get Module name
            $parseParameters[$method]['moduleName'] = $this->getModuleName($path, $operation);

            //Check request body exist or not
            if ($operation->requestBody) {
                //Set Request Body by parsing
                $parseParameters[$method]['requestBody'] = $this->getParasedDataForRequestBody($operation);
            }

            //Set Response Body by parsing
            $parseParameters[$method]['responses'] = $this->getParasedDataForResponses($operation);
        }

        return $parseParameters;
    }

    /**
     * Get Parsed data from request body
     *
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return array
     */
    protected function getParasedDataForRequestBody(Operation $operation): array
    {
        //Intialize array
        $requestBodyParameters = [];
        /** @var \cebe\openapi\spec\RequestBody $mediaType */
        foreach ($this->getRequestBodyContentFromOperation($operation) as $mediaType) {
            //Check schema object exist or not
            //If not then continue to next itration
            if (!isset($mediaType->schema)) {
                continue;
            }
            if ($mediaType->schema instanceof Schema) { //Check Schema instance
                //Set request body parameter based on refrence class name key
                $requestBodyParameters[$this->getClassNameFromSchema($mediaType->schema)] = $this->parseRequestBodyParametersFromSchema($mediaType->schema);
            }
            if ($mediaType->schema instanceof Reference) { //Check Reference instance
                //Set request body parameter based on refrence class name key
                $requestBodyParameters[$this->getClassNameFromReference($mediaType->schema)] = $this->parseRequestBodyParametersFromReference($mediaType->schema);
            }
        }

        //return array of request body parameters
        return $requestBodyParameters;
    }

    /**
     * Get Request body content
     *
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return iterable
     */
    protected function getRequestBodyContentFromOperation(Operation $operation): iterable
    {
        //return instance of iterable class, either array of content or empty array
        return isset($operation->requestBody) && isset($operation->requestBody->content) ? $operation->requestBody->content : [];
    }

    /**
     * Get properties from Schema
     *
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return iterable
     */
    protected function getPropertiesFromSchema(Schema $schema): iterable
    {
        //Check properties exist or not
        if (isset($schema->properties)) {
            return $schema->properties;
        }

        return [];
    }

    /**
     * Get properties from Reference
     *
     * @param \cebe\openapi\spec\Reference $reference
     *
     * @return iterable
     */
    protected function getPropertiesFromReference(Reference $reference): iterable
    {
        //Check properties exist or not
        if (isset($reference->properties)) {
            return $reference->properties;
        }

        return [];
    }

    /**
     * Parsing request body parameters from Schema instance
     *
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return array
     */
    protected function parseRequestBodyParametersFromSchema(Schema $schema): array
    {
        //Initialize array
        $responseParameters = [];
        //Loop over properties for Schema/Reference instances
        //Loop to handle only one specific case - Example: "AppApiResponses" => "AppApiResponse[]:AppApiResponse"
        foreach ($this->getPropertiesFromSchema($schema) as $schemaObject) {
            //If properties from Schema/Reference instance is not found, then continue for next iteration
            if (!isset(($schemaObject->properties))) {
                continue;
            }

            if (empty($schemaObject->properties)) {
                continue;
            }

            if ($schemaObject instanceof Schema) {
                //Checking for Schema instance
                //Parsing request body parameter for schema instance
                return $this->parseRequestBodyParametersFromSchema($schemaObject);
            }
            if ($schemaObject instanceof Reference) {
                //Checking for Reference instance
                //Parsing request body parameter for Reference instance
                return $this->parseRequestBodyParametersFromReference($schemaObject);
            }
        }

        //Set Properties for request body,
        //Handling only those cases when there is not reference class in properties,
        //Example: ["code" => "string", "detail" => "string","status" => "integer"]
        return $this->setRequestBodyParameters($this->getPropertiesFromSchema($schema));
    }

    /**
     * @param \cebe\openapi\spec\Reference $schema
     *
     * @return array
     */
    protected function parseRequestBodyParametersFromReference(Reference $schema): array
    {
        //Loop over properties for Schema/Reference instances
        //Loop to handle only one specific case - Example: "AppApiResponses" => "AppApiResponse[]:AppApiResponse"
        foreach ($this->getPropertiesFromReference($schema) as $schemaObject) {
            //If properties from Schema/Reference instance is not found, then continue for next iteration
            if (!isset(($schemaObject->properties))) {
                continue;
            }

            if (empty($schemaObject->properties)) {
                continue;
            }

            if ($schemaObject instanceof Schema) {
                //Checking for Schema instance
                //Parsing request body parameter for schema instance
                return $this->parseRequestBodyParametersFromSchema($schemaObject);
            }

            if ($schemaObject instanceof Reference) {
                //Checking for Reference instance
                //Parsing request body parameter for Reference instance
                return $this->parseRequestBodyParametersFromReference($schemaObject);
            }
        }

        //Set Properties for request body,
        //Handling only those cases when there is not reference class in properties,
        //Example: ["code" => "string", "detail" => "string","status" => "integer"]
        return $this->setRequestBodyParameters($this->getPropertiesFromReference($schema));
    }

    /**
     * Set Properties for request body,
     * Handling only those cases when there is not reference class in properties,
     * Example: ["code" => "string", "detail" => "string","status" => "integer"]
     *
     * @param iterable $properties
     *
     * @return array
     */
    protected function setRequestBodyParameters(iterable $properties): array
    {
        //Intialize array
        $responseParameters = [];
        //Loop over properties to set as key and value pairing
        foreach ($properties as $key => $schemaObject) {
            //If schema object is not type of array set simply key value pair
            //Example: [.. code => string ..]
            if (isset($schemaObject->type) && $schemaObject->type !== 'array') {
                $responseParameters[$key] = $schemaObject->type;

                continue;
            }
            //If schema object's items not exist then continue
            if (!isset($schemaObject->items)) {
                continue;
            }

            //If schema object items's type is set then define array of values
            //Example: [ .. "categories" => "array[]:string" ..]
            if (isset($schemaObject->items->type)) {
                $responseParameters[$key] = 'array[]:' . $schemaObject->items->type;

                continue;
            }

            //Example: [ .. "categories" => "array[]:string" ..]
            if (isset($schemaObject->items->properties) && isset($schemaObject->items->properties['type'])) {
                $responseParameters[$key] = 'array[]:' . $schemaObject->items->properties['type']->type;

                continue;
            }
        }

        //simply return array of parameters
        return $responseParameters;
    }

    /**
     * Parsing Response content
     *
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return array
     */
    protected function getParasedDataForResponses(Operation $operation): array
    {
        //Initalize array
        $responses = [];

        /** @var \cebe\openapi\spec\Response|\cebe\openapi\spec\Reference $content */
        foreach ($this->getResponsesFromOperation($operation) as $content) { //Loop over operations to handle multiple type of responses
            //Invoke following method only when content is not empty
            if (isset($content->content) && !empty($content->content)) {
                //Parse array of content from responses
                $this->parseParametersFromContent($content->content, $responses);
            }
        }

        return $responses;
    }

    /**
     * Parsing Responses parameters from given contents
     *
     * @param array $contents
     * @param array $responses
     *
     * @return void
     */
    protected function parseParametersFromContent(array $contents, array &$responses): void
    {
        //Loop over contents
        foreach ($contents as $response) {
            //Continue, if Response does not have schema
            if (!isset($response->schema)) {
                continue;
            }
            //Check for instance of Schema or not
            if ($response->schema instanceof Schema) {
                //Set request body parameter based on refrence class name key
                $responses[$this->getClassNameFromSchema($response->schema)] = $this->getReponseParametersForSchema($response->schema, []);
            }
            //Check for instance of Reference or not
            if ($response->schema instanceof Reference) {
                //Set request body parameter based on refrence class name key
                $responses[$this->getClassNameFromReference($response->schema)] = $this->getReponseParametersForReference($response->schema, []);
            }
        }
    }

    /**
     * Getting responses from Operation instance
     *
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return array
     */
    protected function getResponsesFromOperation(Operation $operation): iterable
    {
        //Return array of responses or empty
        return $operation->responses ?? [];
    }

    /**
     * Get Properties for Response using Schema instance
     *
     * @param \cebe\openapi\spec\Schema $schema
     * @param array $rootType
     *
     * @return array
     */
    protected function getReponseParametersForSchema(Schema $schema, array $rootType): array
    {
        //Loop over properties fetched from Schema instance
        foreach ($this->getPropertiesFromSchema($schema) as $schemaObject) {
            //Check for schema properties and recursively calling for instance of Schema
            if ($schemaObject instanceof Schema && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                $rootType[] = false;

                return $this->getReponseParametersForSchema($schemaObject, $rootType);
            }
            //Check for schema properties and recursively calling for instance of Reference
            if ($schemaObject instanceof Reference && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                $rootType[] = false;

                return $this->getReponseParametersForReference($schemaObject, $rootType);
            }

            //Continue, if schemaObject does not have items
            if (!isset($schemaObject->items)) {
                continue;
            }
            //Check for schema's items properties and recursively calling for instance of Schema
            if ($schemaObject->items instanceof Schema && isset($schemaObject->items->properties) && !empty($schemaObject->items->properties)) {
                $rootType[] = true;

                return $this->getReponseParametersForSchema($schemaObject->items, $rootType);
            }
            //Check for schema's items properties and recursively calling for instance of Reference
            if ($schemaObject->items instanceof Reference && isset($schemaObject->items->properties) && !empty($schemaObject->items->properties)) {
                $rootType[] = true;

                return $this->getReponseParametersForReference($schemaObject->items, $rootType);
            }
        }
        //Checks for, this method is calling first time or not
        if (current($rootType) === true) {
            //To set instances array to parsed data
            //Example: [ .. "AppApiResponses" => "AppApiResponse[]:AppApiResponse" .. ]
            return $this->setResponseParameterForReferenceClass($this->getClassNameFromSchema($schema));
        }

        //To set array of key value pair to parsed data
        //Example: [ .. "id" => "string" .. ]
        return $this->setResponseParameterForKeyAndValue($this->getPropertiesFromSchema($schema));
    }

    /**
     * @param \cebe\openapi\spec\Reference $schema
     * @param array $rootType
     *
     * @return array
     */
    protected function getReponseParametersForReference(Reference $schema, array $rootType): array
    {
        //Loop over properties from Reference instance
        foreach ($this->getPropertiesFromReference($schema) as $schemaObject) {
            //Check for schema properties and recursively calling for instance of Schema
            if ($schemaObject instanceof Schema && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                $rootType[] = false;

                return $this->getReponseParametersForSchema($schemaObject, $rootType);
            }
            //Check for schema properties and recursively calling for instance of Reference
            if ($schemaObject instanceof Reference && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                $rootType[] = false;

                return $this->getReponseParametersForReference($schemaObject, $rootType);
            }

            //Continue, if schemaObject does not have items
            if (!isset($schemaObject->items)) {
                continue;
            }

            //Check for schema's items properties and recursively calling for instance of Schema
            if ($schemaObject->items instanceof Schema && isset($schemaObject->items->properties) && !empty($schemaObject->items->properties)) {
                $rootType[] = true;

                return $this->getReponseParametersForSchema($schemaObject->items, $rootType);
            }
            //Check for schema's items properties and recursively calling for instance of Reference
            if ($schemaObject->items instanceof Reference && isset($schemaObject->items->properties) && !empty($schemaObject->items->properties)) {
                $rootType[] = true;

                return $this->getReponseParametersForReference($schemaObject->items, $rootType);
            }
        }
        //Checks for, this method is calling first time or not
        if (current($rootType) === true) {
            //To set instances array to parsed data
            //Example: [ .. "AppApiResponses" => "AppApiResponse[]:AppApiResponse" .. ]
            return $this->setResponseParameterForReferenceClass($this->getClassNameFromReference($schema));
        }

        //To set array of key value pair to parsed data
        //Example: [ .. "id" => "string" .. ]

        return $this->setResponseParameterForKeyAndValue($this->getPropertiesFromReference($schema));
    }

    /**
     * To get array Reference classes
     *
     * @param string $className
     *
     * @return array
     */
    protected function setResponseParameterForReferenceClass(string $className): array
    {
        //Initialize array
        $response = [];
        //Replace 'Attributes' string with empty string
        $refClass = str_replace('Attributes', '', $className);
        //Pluralize and Assign reference class name to response
        //Example: [ .. AppApiResponses => AppApiResponse[]:AppApiResponse ..]
        $response[$this->inflector->pluralize($refClass)] = $refClass . '[]:' . $this->inflector->camelize($refClass);

        return $response;
    }

    /**
     * Set properties to parsed array of response
     *
     * @param iterable $properties
     *
     * @return array
     */
    protected function setResponseParameterForKeyAndValue(iterable $properties): array
    {
        //Initialize array
        $response = [];
        //Loop over properties
        foreach ($properties as $key => $schemaObject) {
            //If schema object is not type of array set simply key value pair
            //Example: [.. code => string ..]
            if (isset($schemaObject->type) && $schemaObject->type !== 'array') {
                $response[$key] = $schemaObject->type;

                continue;
            }

            //If schema object's items not exist then continue
            if (!isset($schemaObject->items)) {
                continue;
            }

            //If schema object items's type is set then define array of values
            //Example: [ .. "categories" => "array[]:string" ..]
            if (isset($schemaObject->items->type)) {
                $response[$key] = 'array[]:' . $schemaObject->items->type;

                continue;
            }

            //Example: [ .. "categories" => "array[]:string" ..]
            if (isset($schemaObject->items->properties) && isset($schemaObject->items->properties['type'])) {
                $response[$key] = 'array[]:' . $schemaObject->items->properties['type']->type;

                continue;
            }
        }

        //simply return array of parameters
        return $response;
    }

    /**
     * To get reference class name from document path from Schema instance
     *
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return string
     */
    protected function getClassNameFromSchema(Schema $schema): string
    {
        //Check Document path exist or not
        if ($schema->getDocumentPosition()) {
            //get complete Document path
            $referencePath = $schema->getDocumentPosition()->getPath();

            //return only specific class name
            return end($referencePath);
        }

        return ''; //empty string
    }

    /**
     * To get reference class name from document path from Reference instance
     *
     * @param \cebe\openapi\spec\Reference $reference
     *
     * @return string
     */
    protected function getClassNameFromReference(Reference $reference): string
    {
        //Check Document path exist or not
        if ($reference->getDocumentPosition()) {
            //get complete Document path
            $referencePath = $reference->getDocumentPosition()->getPath();

            //return only specific class name
            return end($referencePath);
        }

        return ''; //empty string
    }

    /**
     * Implode multiple properties to create command as key value pairing
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function preparePropertyNameForCommand(array $parameters): array
    {
        //Intialize array
        $parsedParameters = [];
        //Loop over parameters
        foreach ($parameters as $key => $value) {
            //Prepare format required in command for multiple parameters
            $parsedParameters[] = "{$key}:{$value}";
        }

        //return string
        return $parsedParameters;
    }

    /**
     * Generate Transfers classes
     * Step1 - Create array of commands
     * Step2 - Run Command
     *
     * @param string $organization
     * @param array $parseParameters
     *
     * @return void
     */
    protected function generateTransfers(string $organization, array $parseParameters): void
    {
        //Intialize array
        $commandLines = [];
        //Loop over parsed parameters for tranfers
        foreach ($parseParameters as $operations) {
            //Loop operations wise
            foreach ($operations as $data) {
                //Step1 - Create array of commands - for requestBody
                $this->createCommands($organization, ($data['requestBody'] ?? []), $data['moduleName'], $commandLines);
                //Step1 - Create array of commands - for responses
                $this->createCommands($organization, ($data['responses'] ?? []), $data['moduleName'], $commandLines);
            }
        }
        // Step2 - Run Command
        $this->runCommands(array_values($commandLines));
    }

    /**
     * Create array of commands
     *
     * @param string $organization
     * @param array $parseParameters
     * @param string $moduleName
     * @param array $commandLines
     *
     * @return void
     */
    protected function createCommands(string $organization, array $parseParameters, string $moduleName, array &$commandLines): void
    {
        //Loop over parsed parameters for tranfers to generate command
        foreach ($parseParameters as $command => $parameters) {
            //Create command
            $commandLines[$command] = $this->createCommand($organization, $parameters, $command, $moduleName);
        }
    }

    /**
     * Build Command based on given parameters
     *
     * @param string $organization
     * @param string $moduleName
     * @param string $command
     * @param string $propertyName
     * @param string|null $propertyType
     * @param string|null $singular
     *
     * @return array
     */
    protected function buildCommand(
        string $organization,
        string $moduleName,
        string $command,
        string $propertyName,
        ?string $propertyType,
        ?string $singular
    ): array {
        //Set required parameters
        $data = [
            'vendor/bin/spryk-run',
            'AddSharedTransferProperty',
            '--mode', $this->sprykMode,
            '--organization', $organization,
            '--module', $moduleName,
            '--name', $command,
            '--propertyName', $propertyName,
        ];
        //Check for optional parameter
        if (($propertyType !== null)) {
            $data[] = '--propertyType';
            $data[] = $propertyType;
        }
        //Check for optional parameter
        if (($singular !== null)) {
            $data[] = '--singular';
            $data[] = $singular;
        }

        $data[] = '-n';
        $data[] = '-v';

        //return array of options for creating command.
        return $data;
    }

    /**
     * Create Command
     * Handling following cases :
     * Case1 : Having single parameter and single property type
     * Case2 : Having single parameter
     * Case3 : Multiple parameters
     *
     * @param string $organization
     * @param array $parameters
     * @param string $command
     * @param string $moduleName
     *
     * @return array
     */
    protected function createCommand(string $organization, array $parameters, string $command, string $moduleName): array
    {
        if (count($parameters) === 1) {
            $propertyName = array_key_first($parameters);
            $propertyTypes = explode(':', $parameters[$propertyName]);
            //Case1 : Having single parameter and single property type
            if (count($propertyTypes) === 1) {
                return $this->buildCommand(
                    $organization,
                    $moduleName,
                    $command,
                    $propertyName,
                    current($propertyTypes),
                    null,
                );
            }

            //Case2 : Having single parameter
            return $this->buildCommand(
                $organization,
                $moduleName,
                $command,
                $propertyName,
                current($propertyTypes),
                end($propertyTypes),
            );
        }

        //Case3 : Multiple parameters
        return $this->buildCommand(
            $organization,
            $moduleName,
            $command,
            implode(',', $this->preparePropertyNameForCommand($parameters)),
            null,
            null,
        );
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array<array> $commands
     *
     * @return void
     */
    protected function runCommands(array $commands): void
    {
        //Loop over commands array
        foreach ($commands as $command) {
            $process = new Process($command, $this->config->getProjectRootPath());
            $process->run(function ($a, $buffer) {
                echo $buffer;
                // For debugging purposes, set a breakpoint here to see issues.
            });
        }
    }
}
