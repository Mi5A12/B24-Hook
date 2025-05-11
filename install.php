<?php
$clientId = "local.68209f97337681.69630009";        // From Bitrix24 app settings
$clientSecret = "le2Wpb7mRQ8pDrRA1mmH0zpkmSLyvrPz4SxPD3rqJd28psNHrF";
$domain = "https://cultiv.bitrix24.com";             // e.g., cultiv.bitrix24.com
$webhookUrl = "https://b24-hook.onrender.com/b24-hook.php";  // Must match app handler

// Prepare API call to register the bot
$url = "https://{$domain}/rest/{$clientId}/{$clientSecret}/imbot.register.json";

$botData = [
    'CODE' => 'open_channel_inbox_bot',
    'TYPE' => 'B', // B = Bot
    'EVENT_HANDLER' => $webhookUrl,
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

// Optional: log response
file_put_contents("b24-install-log.txt", $response);

echo "Bot registration complete.";
