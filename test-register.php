<?php
$clientId     = 'local.68209f93737861.69630009';
$clientSecret = 'le2Wpb7mRQ8pDRa1MmH0ZpkmSLyvrPz4sPD3rqJd28PsNhfF';
$domain       = 'cultiv.bitrix24.com';
$eventHandler = 'https://b24-hook.onrender.com/b24-hook.php';

$url = "https://$domain/rest/$clientId/$clientSecret/imbot.register.json";

$botData = [
  'CODE' => 'open_channel_inbox_bot',
  'TYPE' => 'B', // Bot
  'EVENT_HANDLER' => $eventHandler,
  'OPENLINE' => 'Y',
  'PROPERTIES' => [
    'NAME' => 'Inbox Bot',
    'COLOR' => 'GREEN'
  ]
];

$options = [
  'http' => [
    'method'  => 'POST',
    'header'  => 'Content-type: application/x-www-form-urlencoded',
    'content' => http_build_query($botData)
  ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
echo $response;
