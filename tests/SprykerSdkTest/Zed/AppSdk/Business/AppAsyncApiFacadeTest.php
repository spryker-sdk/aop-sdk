<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Business;

use Codeception\Test\Unit;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AppSdk
 * @group Business
 * @group AppAsyncApiFacadeTest
 */
class AppAsyncApiFacadeTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\BusinesssTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddAsyncApiAddsANewAsyncApiFile(): void
    {
        // Arrange
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequest();

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApi(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->assertFileExists($asyncApiRequestTransfer->getTargetFile());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiUpdatesTheVersionOfAnExistingAsyncApiFile(): void
    {
        // Arrange
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequest();
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApi(
            $asyncApiRequestTransfer,
        );

        // Act
        $asyncApiRequestTransfer->setVersion('1.0.0');
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApi(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);

        // Load the file again, parse the YML to array and assert for the new version.
        $this->assertSame('1.0.0', $asyncApiRequestTransfer->getVersion(), sprintf('Expected to have version "1.0.0" but got "%s".', $asyncApiRequestTransfer->getVersion()));
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageReturnsFailedResponseWhenAsyncApiFileDoesNotExists(): void
    {
        // Arrange
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequest();
        $asyncApiRequestTransfer->setTargetFile('not existing file');

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $asyncApiResponseTransfer->getErrors());
        $this->assertSame('File "not existing file" does not exists. Please create one to continue.', $asyncApiResponseTransfer->getErrors()[0]->getMessage());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageFromTransferObjectAddsAPublishMessageToTheAsyncApiFile(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata();
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApi();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiBuilderTest', $asyncApiMessageTransfer->getChannel()->getName());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageFromTransferObjectAddsASubscribeMessageToTheAsyncApiFile(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->haveSubscribeMessageWithMetadata();
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApi();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertAsyncApiHasSubscribeMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiBuilderTest', $asyncApiMessageTransfer->getChannel()->getName());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageFromTransferObjectAddsAnAdditionalPublishMessageWhenChannelHasAlreadyAMessage(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata();
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApi();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);
        $this->tester->getFacade()->addAsyncApiMessage($asyncApiRequestTransfer);
        $asyncApiRequestTransfer->getAsyncApiMesssage()->setName('AdditionalMessage');

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiBuilderTest', $asyncApiMessageTransfer->getChannel()->getName());
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AdditionalMessage', $asyncApiMessageTransfer->getChannel()->getName());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageFromTransferObjectAddsAnAdditionalPublishMessageWhenChannelHasAlreadyMessages(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata();
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApi();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);
        // Add additional method that creates `oneOf`
        $this->tester->getFacade()->addAsyncApiMessage($asyncApiRequestTransfer);

        // Add another additional method that adds to `oneOf`
        $asyncApiRequestTransfer->getAsyncApiMesssage()->setName('AsyncApiBuilderTest2');
        $this->tester->getFacade()->addAsyncApiMessage($asyncApiRequestTransfer);

        $asyncApiRequestTransfer->getAsyncApiMesssage()->setName('AsyncApiBuilderTest3');

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiBuilderTest', $asyncApiMessageTransfer->getChannel()->getName());
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiBuilderTest2', $asyncApiMessageTransfer->getChannel()->getName());
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiBuilderTest3', $asyncApiMessageTransfer->getChannel()->getName());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageFromTransferObjectWithSpecifiedMessageNameAddsAPublishMessageToTheAsyncApiFileWithTheSpecifiedName(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata();
        $asyncApiMessageTransfer->setName('FooBar');

        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApi();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'FooBar', $asyncApiMessageTransfer->getChannel()->getName());
    }
}
