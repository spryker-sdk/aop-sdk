<?php

use SprykerSdk\Acp\Validator\MessageBroker\ChannelNameValidator;

$config = [];

$config[ChannelNameValidator::MESSAGE_TO_CHANNEL_MAP] = [
    'SomeMessageTransfer' => 'payment-method-commands',
];

$config[ChannelNameValidator::CHANNEL_TO_TRANSPORT_MAP] = [
    'payment-method-commands' => 'transport',
];
