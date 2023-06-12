<?php

use SprykerSdk\Acp\Validator\MessageBroker\ChannelNameValidator;

$config = [];

$config[ChannelNameValidator::MESSAGE_TO_CHANNEL_MAP] = [
    'SomeMessageTransfer' => 'payment-events',
    'SecondMessageTransfer' => 'payment-events',
    'ThirdMessageTransfer' => 'merchant-commands',
];

$config[ChannelNameValidator::CHANNEL_TO_TRANSPORT_MAP] = [
    'payment-events' => 'transport',
];
