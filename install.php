<?php
$clientId = "YOUR_CLIENT_ID";        // From Bitrix24 app settings
$clientSecret = "YOUR_CLIENT_SECRET";
$domain = "YOUR_DOMAIN";             // e.g., cultiv.bitrix24.com
$webhookUrl = "https://your-server.com/b24-hook.php";  // Must match app handler

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