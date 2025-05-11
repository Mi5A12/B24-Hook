<?php
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Extract auth token from Bitrix payload
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

error_log("=== Triggering bot registration with AUTH_ID: $authId ===");

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($botData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true); // get response headers
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

if ($response === false) {
    error_log("❌ cURL error during bot registration: $error");
    echo "Failed";
} else {
    error_log("📡 HTTP Code: $httpCode");
    error_log("✅ Raw response: $response");
    echo $response;
}
