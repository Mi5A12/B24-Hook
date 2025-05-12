<?php
// Receive incoming JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Log raw payload
error_log("=== Incoming Webhook ===");
error_log($input);

// Extract useful info
$message  = $data['data']['PARAMS']['MESSAGE'] ?? '';
$dialogId = $data['data']['PARAMS']['DIALOG_ID'] ?? '';
$userId   = $data['data']['PARAMS']['USER_ID'] ?? '';
$authToken = $data['auth']['access_token'] ?? null;

// Extract URLs using regex
preg_match_all('/https?:\/\/[^\s]+/', $message, $matches);
$foundLinks = $matches[0];

// Log parsed values
error_log("User ID: $userId");
error_log("Dialog ID: $dialogId");
error_log("Message: $message");
error_log("Links Found: " . implode(', ', $foundLinks));

// Auto-reply if we have enough info
if ($dialogId && $message && $authToken) {
    $replyUrl = "https://cultiv.bitrix24.com/rest/imopenlines.message.add.json?auth=$authToken";

    $replyData = [
        "DIALOG_ID" => $dialogId,
        "MESSAGE"   => "📩 Thanks for your message! We'll get back to you shortly."
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($replyData)
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($replyUrl, false, $context);

    error_log("📨 Sent auto-reply: $response");
}

// Respond to Bitrix
echo json_encode(["result" => "ok"]);
