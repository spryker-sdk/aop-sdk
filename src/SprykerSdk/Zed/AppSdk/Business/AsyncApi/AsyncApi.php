<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\AsyncApi;

use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Channel\AsyncApiChannelCollectionInterface;
use SprykerSdk\Zed\AppSdk\Business\AsyncApi\Channel\AsyncApiChannelInterface;

class AsyncApi implements AsyncApiInterface
{
    /**
     * @var \SprykerSdk\Zed\AppSdk\Business\AsyncApi\Channel\AsyncApiChannelCollectionInterface
     */
    protected AsyncApiChannelCollectionInterface $channels;

    /**
     * @param \SprykerSdk\Zed\AppSdk\Business\AsyncApi\Channel\AsyncApiChannelCollectionInterface $channels
     */
    public function __construct(AsyncApiChannelCollectionInterface $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @return iterable<\SprykerSdk\Zed\AppSdk\Business\AsyncApi\Channel\AsyncApiChannelInterface>
     */
    public function getChannels(): iterable
    {
        return $this->channels->getChannels();
    }

    /**
     * @param string $channelName
     *
     * @return \SprykerSdk\Zed\AppSdk\Business\AsyncApi\Channel\AsyncApiChannelInterface|null
     */
    public function getChannel(string $channelName): ?AsyncApiChannelInterface
    {
        return $this->channels->getChannel($channelName);
    }
}
