<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Acp\Configuration\SchemaGenerator;

use Codeception\Test\Unit;

class SchemaGeneratorTest extends Unit
{
    protected ?\SprykerSdkTest\Acp\Tester $tester = null;

    /**
     * @var string
     */
    protected const CONFIGURATION_FILE_PATH = 'schema/configuration.json';

    /**
     * @var string
     */
    protected const SCHEMA_OUTPUT_FILE_PATH = 'schema/openapi.yml';

    /**
     * @var string
     */
    protected const SCHEMA_EXPECTED_FILE_PATH = 'schema/expected.yml';

    /**
     * @return void
     */
    public function testConvertConfigurationToSchemaWorksCorrectlyWithSupportedTypes(): void
    {
        $isSuccessful = $this->tester->getFacade()->convertConfigurationToSchemaFile(
            codecept_data_dir(static::CONFIGURATION_FILE_PATH),
            codecept_output_dir(static::SCHEMA_OUTPUT_FILE_PATH),
        );

        $this->assertTrue($isSuccessful);

        $this->assertFileEquals(codecept_data_dir(static::SCHEMA_EXPECTED_FILE_PATH), codecept_output_dir(static::SCHEMA_OUTPUT_FILE_PATH));
    }
}
