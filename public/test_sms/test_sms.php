<?php
require_once('./vendor/autoload.php');

use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;

// Configure client
$config = Configuration::getDefaultConfiguration();
$config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTY1MjU3NDA5NywiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjk0NTQwLCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.oqRE1--5HGVLCBlHwZW8ikb0XYDszCsWP49Zf4M9CAI');
$apiClient = new ApiClient($config);
$messageClient = new MessageApi($apiClient);

// Sending a SMS Message
$sendMessageRequest1 = new SendMessageRequest([
    'phoneNumber' => '0624863948',
    'message' => 'test1',
    'deviceId' => 128317
]);
$sendMessages = $messageClient->sendMessages([
    $sendMessageRequest1
]);
print_r($sendMessages);
?> 