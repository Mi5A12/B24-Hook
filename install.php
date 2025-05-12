<?php
// Read incoming payload from Bitrix (usually when the app is opened)
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Extract AUTH_ID from the payload
$authId = $data['auth']['access_token'] ?? null;

if (!$authId) {
    error_log("❌ No AUTH_ID received.");
    exit("AUTH_ID missing");
}

// Set bot event handler (where Bitrix will send messages)
$eventHandler = 'https://b24-hook.onrender.com/b24-hook.php';

// Bitrix API endpoint with auth
$url = "https://cultiv.bitrix24.com/rest/imbot.register.json?auth=" . $authId;

// Bot registration data
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

// Prepare POST options
$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($botData)
    ]
];

// Send the request
$context  = stream_context_create($options);

// Log outgoing request
error_log("🔄 Sending bot registration request to Bitrix24...");

// Execute request and capture response
$response = file_get_contents($url, false, $context);

if ($response === false) {
    $error = error_get_last();
    error_log("❌ Bot registration failed: " . $error['message']);
    echo json_encode(["error" => "Bot registration failed"]);
    exit;
}

// Log success response
error_log("✅ Bot registration response: $response");

// Echo it for debug
echo $response;
