<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Acp\Validator\MessageBroker;

use SprykerSdk\Acp\Validator\Finder\FinderInterface;
use Symfony\Component\Finder\SplFileInfo;
use Transfer\MessageTransfer;
use Transfer\ValidateRequestTransfer;
use Transfer\ValidateResponseTransfer;

class ChannelNameValidator implements ChannelNameValidatorInterface
{
    /**
     * @var string
     */
    public const MESSAGE_TO_CHANNEL_MAP = 'MESSAGE_BROKER:MESSAGE_TO_CHANNEL_MAP';

    /**
     * @var string
     */
    public const CHANNEL_TO_TRANSPORT_MAP = 'MESSAGE_BROKER:SENDER_CHANNEL_TO_CLIENT_MAP';

    /**
     * @var string
     *
     * @phpstan-var non-empty-string
     */
    public const CHANNEL_NAME_SEPARATOR = '-';

    public static array $allowedSuffixes = [
        'commands',
        'events',
        'queries',
        'replies',
        'fails',
    ];

    /**
     * @var \SprykerSdk\Acp\Validator\Finder\FinderInterface
     */
    protected FinderInterface $finder;

    /**
     * @param \SprykerSdk\Acp\Validator\Finder\FinderInterface $finder
     */
    public function __construct(FinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     * @param \Transfer\ValidateResponseTransfer|null $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validate(
        ValidateRequestTransfer $validateRequestTransfer,
        ?ValidateResponseTransfer $validateResponseTransfer = null,
    ): ValidateResponseTransfer {
        $validateResponseTransfer ??= new ValidateResponseTransfer();

        if (!$this->finder->hasFile($validateRequestTransfer->getConfigurationFileOrFail())) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf('No "%s" file found.', basename($validateRequestTransfer->getConfigurationFileOrFail())));
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        /** @var \Symfony\Component\Finder\SplFileInfo $splFileInfo */
        $splFileInfo = $this->finder->getFile($validateRequestTransfer->getConfigurationFileOrFail());

        $config = $this->getConfig($splFileInfo);

        $validateResponseTransfer = $this->validateMessageToChannelMap($config, $validateResponseTransfer);
        $validateResponseTransfer = $this->validateChannelToTransportMap($config, $validateResponseTransfer);

        if ($validateResponseTransfer->getErrors()->count() === 0) {
            $validateResponseTransfer->addMessage((new MessageTransfer())->setMessage(sprintf('No errors found in "%s".', $validateRequestTransfer->getConfigurationFileOrFail())));
        }

        return $validateResponseTransfer;
    }

    /**
     * @param array $config
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    protected function validateMessageToChannelMap(array $config, ValidateResponseTransfer $validateResponseTransfer): ValidateResponseTransfer
    {
        if (!array_key_exists(static::MESSAGE_TO_CHANNEL_MAP, $config)) {
            $validateResponseTransfer->addError((new MessageTransfer())->setMessage(sprintf('"%s" not found in configuration file', static::MESSAGE_TO_CHANNEL_MAP)));

            return $validateResponseTransfer;
        }

        foreach ($config[static::MESSAGE_TO_CHANNEL_MAP] as $channel) {
            $validateResponseTransfer = $this->channelNameCompliesWithRFC($channel, $validateResponseTransfer);
        }

        return $validateResponseTransfer;
    }

    /**
     * @param string $channelName
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    protected function channelNameCompliesWithRFC(string $channelName, ValidateResponseTransfer $validateResponseTransfer): ValidateResponseTransfer
    {
        $channelNameParts = explode(static::CHANNEL_NAME_SEPARATOR, $channelName);

        if (count($channelNameParts) < 2) {
            $validateResponseTransfer->addError((new MessageTransfer())->setMessage(sprintf('Channel name "%s" must be camel-case format.', $channelName)));
        }

        $suffix = end($channelNameParts);

        if (!in_array($suffix, static::$allowedSuffixes)) {
            $validateResponseTransfer->addError((new MessageTransfer())->setMessage(sprintf('Channel name "%s" in invalid, suffix "%s" is not allowed.', $channelName, $suffix)));
        }

        return $validateResponseTransfer;
    }

    /**
     * @param array $config
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    protected function validateChannelToTransportMap(array $config, ValidateResponseTransfer $validateResponseTransfer): ValidateResponseTransfer
    {
        if (!array_key_exists(static::CHANNEL_TO_TRANSPORT_MAP, $config)) {
            $validateResponseTransfer->addError((new MessageTransfer())->setMessage(sprintf('"%s" not found in configuration file', static::CHANNEL_TO_TRANSPORT_MAP)));

            return $validateResponseTransfer;
        }

        $channelsInTransportMap = array_keys($config[static::CHANNEL_TO_TRANSPORT_MAP]);
        $channelsInMessageToChannelMap = array_unique($config[static::MESSAGE_TO_CHANNEL_MAP]);

        $channelsDiff = array_diff($channelsInMessageToChannelMap, $channelsInTransportMap);

        foreach ($channelsDiff as $missingChannel) {
            $validateResponseTransfer->addError((new MessageTransfer())->setMessage(sprintf('"%s" channel is missing in "%s"', $missingChannel, static::CHANNEL_TO_TRANSPORT_MAP)));
        }

        return $validateResponseTransfer;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return array
     */
    protected function getConfig(SplFileInfo $splFileInfo): array
    {
        $config = [];

        defined('APPLICATION_ROOT_DIR') || define('APPLICATION_ROOT_DIR', dirname(__DIR__, 2));
        defined('APPLICATION_SOURCE_DIR') || define('APPLICATION_SOURCE_DIR', dirname(__DIR__, 2));
        defined('APPLICATION_STORE') || define('APPLICATION_STORE', dirname(__DIR__, 2));

        require $splFileInfo->getPathname();

        return $config;
    }
}
