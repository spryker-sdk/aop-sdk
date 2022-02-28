<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Generated\Shared\Transfer\AsyncApiBuilderTestTransfer;
use Generated\Shared\Transfer\AsyncApiChannelTransfer;
use Generated\Shared\Transfer\AsyncApiMessageTransfer;
use Generated\Shared\Transfer\AsyncApiRequestTransfer;
use Generated\Shared\Transfer\AsyncApiResponseTransfer;
use Generated\Shared\Transfer\AsyncApiTransfer;
use org\bovigo\vfs\vfsStream;
use SprykerSdk\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Builder\AsyncApiCodeBuilder;
use SprykerSdk\Zed\AppSdk\Communication\Console\BuildCodeFromAsyncApiConsole;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use Symfony\Component\Yaml\Yaml;

class AsyncApiHelper extends Module
{
    use BusinessHelperTrait;
    use ConfigHelperTrait;

    /**
     * @var string
     */
    public const CHANNEL_NAME = 'schemas';

    /**
     * @var string|null
     */
    protected ?string $rootUrl = null;

    /**
     * @return \Generated\Shared\Transfer\AsyncApiRequestTransfer
     */
    public function haveAsyncApiAddRequest(): AsyncApiRequestTransfer
    {
        $rootUrl = $this->getRootUrl();

//        $root = vfsStream::setup('root', null, [
//            'config' => [
//                'app' => [
//                    'api' => [
//                        'asyncapi',
//                    ],
//                ],
//            ],
//        ]);

//        $this->getValidatorHelper()->mockRoot($root->url());
        $this->getValidatorHelper()->mockRoot($this->getRootUrl());

        $config = $this->getValidatorHelper()->getConfig() ?? new AppSdkConfig();

        $asyncApiTransfer = new AsyncApiTransfer();
        $asyncApiTransfer
            ->setTitle('Test title')
            ->setVersion('0.1.0');

        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setTargetFile($config->getDefaultAsyncApiFile())
            ->setAsyncApi($asyncApiTransfer);

        return $asyncApiRequestTransfer;
    }

    /**
     * We assume that an AsyncApi file with version 0.1.0 exists when `\SprykerSdkTest\Helper\AsyncApiHelper::haveAsyncApiFile()`
     * was called before `\SprykerSdk\Zed\AppSdk\Business\AppSdkFacadeInterface::addAsyncApi()` is executed.
     *
<<<<<<< HEAD
     * @return \Generated\Shared\Transfer\AsyncApiRequestTransfer
     */
    public function haveAsyncApiUpdateVersionRequest(): AsyncApiRequestTransfer
    {
        $this->getValidatorHelper()->mockRoot($this->getRootUrl());

        $config = $this->getValidatorHelper()->getConfig() ?? new AppSdkConfig();

        $asyncApiTransfer = new AsyncApiTransfer();
        $asyncApiTransfer
            ->setTitle('Test title')
            ->setVersion('1.0.0');

        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setTargetFile($config->getDefaultAsyncApiFile())
            ->setAsyncApi($asyncApiTransfer);

        return $asyncApiRequestTransfer;
    }

    /**
     * @return string
     */
    protected function getRootUrl(): string
    {
        if (!$this->rootUrl) {
            $this->rootUrl = vfsStream::setup('root')->url();
        }

        return $this->rootUrl;
    }

    /**
=======
>>>>>>> db1500d03f3301694c4c13037050afc75eae923e
     * @return \Generated\Shared\Transfer\AsyncApiRequestTransfer
     */
    public function haveAsyncApiUpdateVersionRequest(): AsyncApiRequestTransfer
    {
<<<<<<< HEAD
        $this->haveAsyncApiFile();
       //dd($this->haveAsyncApiFile());
=======
        $this->getValidatorHelper()->mockRoot($this->getRootUrl());

>>>>>>> db1500d03f3301694c4c13037050afc75eae923e
        $config = $this->getValidatorHelper()->getConfig() ?? new AppSdkConfig();

        $asyncApiTransfer = new AsyncApiTransfer();
        $asyncApiTransfer
            ->setTitle('Test title')
<<<<<<< HEAD
            ->setVersion('0.1.0');
=======
            ->setVersion('1.0.0');

>>>>>>> db1500d03f3301694c4c13037050afc75eae923e
        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setTargetFile($config->getDefaultAsyncApiFile())
            ->setAsyncApi($asyncApiTransfer);

        return $asyncApiRequestTransfer;
    }

    /**
     * @return string
     */
    protected function getRootUrl(): string
    {
        if (!$this->rootUrl) {
            $this->rootUrl = vfsStream::setup('root')->url();
        }

        return $this->rootUrl;
    }

    /**
     * @return \Generated\Shared\Transfer\AsyncApiRequestTransfer
     */
    public function haveAsyncApiAddRequestWithExistingAsyncApi(): AsyncApiRequestTransfer
    {
        $this->haveAsyncApiFile();
       //dd($this->haveAsyncApiFile());
        $config = $this->getValidatorHelper()->getConfig() ?? new AppSdkConfig();

        $asyncApiTransfer = new AsyncApiTransfer();
        $asyncApiTransfer
            ->setTitle('Test title')
            ->setVersion('0.1.0');
        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setTargetFile($config->getDefaultAsyncApiFile())
            ->setAsyncApi($asyncApiTransfer);
        return $asyncApiRequestTransfer;
    }

    /**
     * return void
     *
     * @return void
     */
    public function haveAsyncApiFile(): void
    {
        $this->prepareAsyncApiFile(codecept_data_dir('api/asyncapi/valid/base_asyncapi.schema.yml'));
    }

    /**
     * @param string $pathToAsyncApi
     *
     * @return void
     */
    protected function prepareAsyncApiFile(string $pathToAsyncApi): void
    {
        $filePath = sprintf('%s/config/api/asyncapi/asyncapi.yml', $this->getRootUrl());

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0770, true);
        }
        file_put_contents($filePath, file_get_contents($pathToAsyncApi));

        $this->getValidatorHelper()->mockRoot($this->getRootUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     *
     * @return array
     */
    public function getMessagesFromAsyncApiResponseTransfer(AsyncApiResponseTransfer $asyncApiResponseTransfer): array
    {
        $messages = [];

        foreach ($asyncApiResponseTransfer->getErrors() as $messageTransfer) {
            $messages[] = $messageTransfer->getMessage();
        }

        return $messages;
    }

    /**
     * @return void
     */
    public function mockRootPath(): void
    {
        $root = vfsStream::setup('root');
        $this->getValidatorHelper()->mockRoot($root->url());
    }

    /**
     * @return \SprykerSdkTest\Helper\ValidatorHelper
     */
    protected function getValidatorHelper(): ValidatorHelper
    {
        return $this->getModule('\\' . ValidatorHelper::class);
    }

    /**
     * @param string $targetFile
     * @param string $messageName
     * @param string $channelName
     *
     * @return void
     */
    public function assertAsyncApiHasPublishMessageInChannel(string $targetFile, string $messageName, string $channelName): void
    {
        $asyncApi = Yaml::parseFile($targetFile);
        $channelType = 'publish';

        $this->assertMessageInChannelType($asyncApi, $messageName, $channelName, $channelType);
    }

    /**
     * @param string $targetFile
     * @param string $messageName
     * @param string $channelName
     *
     * @return void
     */
    public function assertAsyncApiHasSubscribeMessageInChannel(string $targetFile, string $messageName, string $channelName): void
    {
        $asyncApi = Yaml::parseFile($targetFile);
        $channelType = 'subscribe';

        $this->assertMessageInChannelType($asyncApi, $messageName, $channelName, $channelType);
    }

    /**
     * @param string $targetFile
     * @param string $expectedVersion
     *
     * @return void
     */
    public function assertAsyncApiVersionIsUpdated(string $targetFile, string $expectedVersion): void
    {
        $asyncApi = Yaml::parseFile($targetFile);
        $message = sprintf('Expected to have version "%s" but got "%s".', $expectedVersion, $asyncApi['info']['version']);

        $this->assertSame($asyncApi['info']['version'], $expectedVersion, $message);
    }

    /**
     * @param array $asyncApi
     * @param string $messageName
     * @param string $channelName
     * @param string $channelType
     *
     * @return void
     */
    protected function assertMessageInChannelType(array $asyncApi, string $messageName, string $channelName, string $channelType): void
    {
        $this->assertChannelType($asyncApi, $channelName, $channelType);

        $expectedMessageReference = $this->getExpectedMessageReference($asyncApi, $messageName, $channelName, $channelType);

        $this->assertNotNull($expectedMessageReference, sprintf(
            'Expected to have a "%s" message "%s" in the channel "%s" but it does not exist.',
            $channelType,
            $messageName,
            $channelName,
        ));
    }

    /**
     * @param array $asyncApi
     * @param string $messageName
     * @param string $channelName
     * @param string $channelType
     *
     * @return string|null
     * 
     */
    protected function getExpectedMessageReference(array $asyncApi, string $messageName, string $channelName, string $channelType): ?string
    {
        $messages = $asyncApi['channels'][$channelName][$channelType]['message'];

        $expectedMessageReference = sprintf('#/components/messages/%s', $messageName);

        foreach ($messages as $key => $messageReference) {
            if ($key === 'oneOf') {
                return $this->getExpectedMessageReferenceFromOneOf($messageReference, $expectedMessageReference);
            }

            if ($messageReference === $expectedMessageReference) {
                return $messageReference;
            }
        }

        return null;
    }

    /**
     * @param array $messages
     * @param string $expectedMessageReference
     *
     * @return string|null
     */
    protected function getExpectedMessageReferenceFromOneOf(array $messages, string $expectedMessageReference): ?string
    {
        foreach ($messages as $message) {
            if ($message['$ref'] === $expectedMessageReference) {
                return $message['$ref'];
            }
        }

        return null;
    }

    /**
     * @param array $asyncApi
     * @param string $channelName
     * @param string $channelType
     *
     * @return void
     */
    protected function assertChannelType(array $asyncApi, string $channelName, string $channelType): void
    {
        $this->assertIsArray($asyncApi['channels'][$channelName], sprintf(
            'Expected to have a channel "%s" but it does not exist.',
            $channelName,
        ));

        $this->assertIsArray($asyncApi['channels'][$channelName][$channelType], sprintf(
            'Expected to have a "%s" element in the channel "%s" but it does not exist.',
            $channelType,
            $channelName,
        ));

        $this->assertIsArray($asyncApi['channels'][$channelName][$channelType]['message'], sprintf(
            'Expected to have a "message" element in the channel "%s" but it does not exist.',
            $channelName,
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     *
     * @return void
     */
    public function assertAsyncApiResponseHasNoErrors(AsyncApiResponseTransfer $asyncApiResponseTransfer): void
    {
        $this->assertCount(0, $asyncApiResponseTransfer->getErrors(), sprintf(
            'Expected that no errors given but there are errors. Errors: "%s"',
            implode(', ', $this->getMessagesFromAsyncApiResponseTransfer($asyncApiResponseTransfer)),
        ));
    }

    /**
     * @return \Generated\Shared\Transfer\AsyncApiMessageTransfer
     */
    public function havePublishMessageWithMetadata(): AsyncApiMessageTransfer
    {
        return $this->createMessage(true, 'publish');
    }

    /**
     * @return \Generated\Shared\Transfer\AsyncApiMessageTransfer
     */
    public function haveSubscribeMessageWithMetadata(): AsyncApiMessageTransfer
    {
        return $this->createMessage(true, 'subscribe');
    }

    /**
     * @param bool $withMetadata
     * @param string $channelType
     *
     * @return \Generated\Shared\Transfer\AsyncApiMessageTransfer
     */
    protected function createMessage(bool $withMetadata, string $channelType): AsyncApiMessageTransfer
    {
        $asyncApiChannelTransfer = new AsyncApiChannelTransfer();
        $asyncApiChannelTransfer->setName(static::CHANNEL_NAME);

        $asyncApiMessageTransfer = new AsyncApiMessageTransfer();
        $asyncApiMessageTransfer
            ->setChannel($asyncApiChannelTransfer)
            ->setAddMetadata($withMetadata)
            ->setPayloadTransferObjectName(AsyncApiBuilderTestTransfer::class);

        if ($channelType === 'publish') {
            $asyncApiMessageTransfer->setIsPublish(true);
        }
        if ($channelType === 'subscribe') {
            $asyncApiMessageTransfer->setIsSubscribe(true);
        }

        return $asyncApiMessageTransfer;
    }

    /**
     * @return \SprykerSdk\Zed\AppSdk\Communication\Console\BuildCodeFromAsyncApiConsole
     */
    public function getAsyncApiBuilderConsoleMock(): BuildCodeFromAsyncApiConsole
    {
        $asyncApiCodeBuilderStub = Stub::construct(AsyncApiCodeBuilder::class, [$this->getConfigHelper()->getModuleConfig(), new AsyncApiLoader()], [
            'runCommandLines' => Expected::atLeastOnce(),
        ]);
        $this->getBusinessHelper()->mockFactoryMethod('createAsyncApiCodeBuilder', $asyncApiCodeBuilderStub);
        $facade = $this->getBusinessHelper()->getFacade();
        $buildFromAsyncApiConsole = new BuildCodeFromAsyncApiConsole();
        $buildFromAsyncApiConsole->setFacade($facade);

        return $buildFromAsyncApiConsole;
    }

<<<<<<< HEAD
    /**
=======

    /**
     *
>>>>>>> db1500d03f3301694c4c13037050afc75eae923e
     * @return \Generated\Shared\Transfer\AsyncApiMessageTransfer
     */
    public function haveAsyncApiMessagePropertyRequest(): AsyncApiMessageTransfer
    {
        $asyncApiChannelTransfer = new AsyncApiChannelTransfer();
        $asyncApiChannelTransfer->setName(static::CHANNEL_NAME);

        $asyncApiMessageTransfer = new AsyncApiMessageTransfer();
        $asyncApiMessageTransfer
            ->setChannel($asyncApiChannelTransfer)
<<<<<<< HEAD
            ->setName('message')
            ->setProperty(['firstName:string:required', 'lastName:string:optional'])
            // ->setPayloadTransferObjectName(AsyncApiMessageTransfer::class)
            ->setContentType('object');
=======
            ->setProperty(['firstName:string:required','lastName:string:optional'])
            ->setPayloadTransferObjectName(AsyncApiMessageTransfer::class)
            ->setContentType('object');
    ;
>>>>>>> db1500d03f3301694c4c13037050afc75eae923e

        return $asyncApiMessageTransfer;
    }

    /**
     * @param string $targetFile
     * @param string $messageName
     * @param string $channelName
     *
     * @return void
     */
    public function assertAsyncApiHasPropertyInMessage(string $targetFile, string $messageName, string $channelName): void
    {
        $asyncApi = Yaml::parseFile($targetFile);
        $channelType = 'publish';

        $this->assertMessageInChannelType($asyncApi, $messageName, $channelName, $channelType);
    }

    /**
<<<<<<< HEAD
     * @param string $channelName
     * @param string $messageName
     * @param array $property
     * @param string $targetFile
     *
     * @return void
     */
    public function assertMessageInChannelHasPropertyOrPayload(string $channelName, string $messageName, array $property, string $targetFile): void
    {
        $asyncApi = Yaml::parseFile($targetFile);
        $this->assertChannelProperty($asyncApi, $channelName, $messageName, $property);
=======
     * @param string $targetFile
     * @param string $messageName
     * @param string $channelName
     * @param array $property
     *
     * @return void
     */
    public function assertAsyncApiMessagePropertyInChannel(string $targetFile, string $messageName, string $channelName, array $property, string $contentType): void
    {
        $asyncApi = Yaml::parseFile($targetFile);
        $this->assertMessagePropertyInChannel($asyncApi, $messageName, $channelName, $property, $contentType);
>>>>>>> db1500d03f3301694c4c13037050afc75eae923e
    }

    /**
     * @param array $asyncApi
<<<<<<< HEAD
     * @param string $channelName
     * @param string $messageName
=======
     * @param string $messageName
     * @param string $channelName
     * @param string $channelType
>>>>>>> db1500d03f3301694c4c13037050afc75eae923e
     * @param array $property
     *
     * @return void
     */
<<<<<<< HEAD
    protected function assertChannelProperty(array $asyncApi, string $channelName, string $messageName, array $property): void
    {
        $this->assertIsArray($asyncApi['components'][$channelName], sprintf(
            'Expected to have a channel name "%s" but it does not exist.',
            $channelName,
        ));

        $this->assertIsArray($asyncApi['components'][$channelName][$messageName], sprintf(
            'Expected to have a "%s" message name in the channel "%s" but it does not exist.',
            $channelName,
            $messageName,
        ));

        $this->assertIsArray($asyncApi['components'][$channelName][$messageName]['properties'][$property[0]], sprintf(
            'Expected to have a property type "%s" in the message name "%s" but it does not exist.',
            $property[0],
            $messageName,
        ));

        $this->assertSame($asyncApi['components'][$channelName][$messageName]['properties'][$property[0]]['type'], $property[1], sprintf(
            'Expected to have a "%s" property type in message "%s" but it does not exist.',
            $property[1],
            $messageName,
        ));

        // if($property[2] === true) {
        $this->assertContains($property[0], $asyncApi['components'][$channelName][$messageName]['required'], sprintf(
            'Expected to have a "%s" property type required but it does not exist.',
            'firstName',
        ));
        // }
    }
}
=======
    protected function assertMessagePropertyInChannel(array $asyncApi, string $messageName, string $channelName, array $property, string $contentType): void
    {
        $messageProperties = [];
        $requiredFields = [];
        
        foreach ($property as $propertyDefinition) {
            $input = explode(':', $propertyDefinition);

            $messageProperties[$input[0]] = ['type' => $input[1]];

            if (in_array('required', $input)) {
                $requiredFields[] = $input[0];
            }
        }
        
        $this->assertChannelProperty($asyncApi, $channelName, $messageProperties, $requiredFields, $messageName, $contentType);
    }

    /**
     * @param array $asyncApi
     * @param array $messageProperty
     * @param array $requiredFields
     * @param string $messageName
     * @param string $messageType
     * @return void
     */
    protected function assertChannelProperty(array $asyncApi, string $channelName, array $messageProperties, array $requiredFields, string $messageType): void
    {
        if(empty($channelName)){
            $this->assertNotNull($channelName, sprintf(
                'Expected to have a channel name "%s" but it does not exist.',
                $channelName,
            ));
        } else {
            $this->assertIsArray($asyncApi['components'][$channelName], sprintf(
                'Expected to have a message name "%s" but it does not exist.',
                $channelName,
            ));
        }
        
        $this->assertIsArray($asyncApi['components'][$channelName]['message'][$messageProperties], sprintf(
            'Expected to have a "%s" message property in the channel "%s" but it does not exist.',
            $channelName,
            $messageProperties,
        ));
    }


}
>>>>>>> db1500d03f3301694c4c13037050afc75eae923e
