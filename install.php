<?php
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$authId = $data['auth']['access_token'] ?? null;
if (!$authId) {
    error_log("❌ No AUTH_ID received.");
    exit("AUTH_ID missing");
}

$eventHandler = 'https://b24-hook.onrender.com/b24-hook.php';
$url = "https://cultiv.bitrix24.com/rest/imbot.register.json?auth=" . $authId;

$botData = [
    'CODE' => 'inbox_bot',
    'TYPE' => 'B',
    'OPENLINE' => 'Y',
    'EVENT_HANDLER' => $eventHandler,
    'PROPERTIES' => [
        'NAME' => 'Inbox Bot',
        'COLOR' => 'GREEN'
    ]
];

$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($botData)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
error_log("🟢 Bot registration response: $response");
echo $response;
