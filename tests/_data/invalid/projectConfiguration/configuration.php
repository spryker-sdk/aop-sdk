<?php

use SprykerSdk\Acp\Validator\MessageBroker\ChannelNameValidator;

$config = [];

$config[ChannelNameValidator::MESSAGE_TO_CHANNEL_MAP] = [
    'Transfer\MessageTransfer' => 'payment-method',
    'Transfer\AnotherTransfer' => 'payment',
];

$config[ChannelNameValidator::CHANNEL_TO_TRANSPORT_MAP] = [
    'payment-method' => 'transport',
    'payment' => 'transport',
];
