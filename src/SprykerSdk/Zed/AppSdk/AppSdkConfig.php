<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk;

use Exception;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AppSdkConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getDefaultManifestPath(): string
    {
        $pathFragments = [
            $this->getProjectRootPath(),
            'config',
            'app',
            'manifest',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathFragments) . DIRECTORY_SEPARATOR;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getRequiredManifestFieldNames(): array
    {
        return [
            'name',
            'provider',
            'description',
            'descriptionShort',
            'categories',
            'pages',
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getRequiredManifestPageBlockFields(): array
    {
        return [
            'title',
            'type',
            'data',
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getAllowedManifestPageTypes(): array
    {
        return [
            'text',
            'list',
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultConfigurationFile(): string
    {
        $pathFragments = [
            $this->getProjectRootPath(),
            'config',
            'app',
            'configuration',
            'configuration.json',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathFragments);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultTranslationFile(): string
    {
        $pathFragments = [
            $this->getProjectRootPath(),
            'config',
            'app',
            'translation',
            'translation.json',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathFragments);
    }

    /**
     * @api
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getDefaultAsyncApiFile(): string
    {
        $pathFragments = [
            $this->getProjectRootPath(),
            'config',
            'api',
            'asyncapi',
            'asyncapi.yml',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathFragments);
    }

    /**
     * @api
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getProjectRootPath(): string
    {
        $cwd = getcwd();

        // @codeCoverageIgnoreStart
        if (!$cwd) {
            throw new Exception('Could not get the current working directory.');
        }
        // @codeCoverageIgnoreEnd

        return $cwd;
    }

    /**
     * @api
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getPathToCheckRecipes(): string
    {
        $pathFragments = [
            $this->getRootPath(),
            'config',
            'CheckRecipes',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathFragments);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    protected function getRootPath(): string
    {
        return APP_SDK_ROOT_DIR;
    }

    /**
     * @api
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getDefaultOpenApiFile(): string
    {
        $pathFragments = [
            $this->getProjectRootPath(),
            'config',
            'api',
            'openapi',
            'openapi.yml',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathFragments);
    }

    /**
     * @api
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getDefaultAppTranslationFile(): string
    {
        $pathFragments = [
            $this->getProjectRootPath(),
            'config',
            'app',
            'translation.json',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathFragments);
    }
}
