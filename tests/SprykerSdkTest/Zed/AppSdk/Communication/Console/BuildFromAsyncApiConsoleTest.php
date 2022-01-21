<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AppSdk\Communication\Console;

use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Builder\AsyncApiCodeBuilder;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdk\Zed\AppSdk\Communication\Console\AbstractConsole;
use SprykerSdk\Zed\AppSdk\Communication\Console\BuildFromAsyncApiConsole;

/**
 * @group SprykerSdkTest
 * @group Zed
 * @group AppSdk
 * @group Communication
 * @group Console
 * @group BuildFromAsyncApiConsoleTest
 */
class BuildFromAsyncApiConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\AppSdk\CommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreatesMessageBrokerRelevantCode(): void
    {
        // Arrange
        $this->tester->mockRootPath();

        $asyncApiCodeBuilderStub = Stub::construct(AsyncApiCodeBuilder::class, [$this->tester->getConfig(), new AsyncApiLoader()], [
            'runCommandLines' => Expected::atLeastOnce(),
        ]);
        $this->tester->mockFactoryMethod('createAsyncApiCodeBuilder', $asyncApiCodeBuilderStub);
        $facade = $this->tester->getFacade();
        $buildFromAsyncApiConsole = new BuildFromAsyncApiConsole();
        $buildFromAsyncApiConsole->setFacade($facade);

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsole);

        // Act
        $commandTester->execute(
            [
                '--' . BuildFromAsyncApiConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/asyncapi/builder/asyncapi.yml'),
            ],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
