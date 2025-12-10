<?php
parse_str(file_get_contents("php://input"), $parsed);

// Confirm structure is what we expect
$data = $parsed['data'] ?? [];

if (is_array($data)) {
    // No need to decode, it's already an array
    $message  = $data['PARAMS']['MESSAGE'] ?? '';
    $dialogId = $data['PARAMS']['DIALOG_ID'] ?? '';
    $userId   = $data['PARAMS']['FROM_USER_ID'] ?? '';

    // Extract links
    preg_match_all('/https?:\/\/[^\s]+/', $message, $matches);
    $foundLinks = $matches[0];

    // Extract deal ID from CHAT_ENTITY_DATA_1
    $entityData1 = $data['PARAMS']['CHAT_ENTITY_DATA_1'] ?? '';
    $dealId = null;
    if (preg_match('/DEAL\|(\d+)/', $entityData1, $match)) {
        $dealId = $match[1];
    }

    // Extract access token from BOT section
    $botData = $data['BOT'] ?? [];
    $accessToken = '';
    foreach ($botData as $bot) {
        if (isset($bot['access_token'])) {
            $accessToken = $bot['access_token'];
            break;
        }
    }

    // Log everything
    error_log("User ID: $userId");
    error_log("Dialog ID: $dialogId");
    error_log("Message: $message");
    error_log("Links Found: " . implode(', ', $foundLinks));
    error_log("Deal ID: $dealId");
    error_log("Access Token: $accessToken");

    // SEND RESPONSE MESSAGE BACK TO DIALOG
    if (!empty($accessToken) && !empty($dialogId)) {
        sendMessageToDialog($dialogId, $accessToken, $message, $foundLinks, $dealId);
    } else {
        error_log("⚠️ Cannot send response: missing accessToken or dialogId");
    }
} else {
    error_log("⚠️ Unexpected payload structure:");
    error_log(print_r($parsed, true));
}

/**
 * Send a message back to the Bitrix24 chat dialog
 *
 * @param string $dialogId      Dialog/Chat ID
 * @param string $accessToken   Bot access token
 * @param string $userMessage   Original message from user
 * @param array  $links         Extracted links from message
 * @param string $dealId        Deal ID if available
 */
function sendMessageToDialog($dialogId, $accessToken, $userMessage, $links, $dealId) {
    // Build response message
    $responseMessage = "✅ Message received!\n";
    $responseMessage .= "Original message: " . substr($userMessage, 0, 100);
    
    if (count($links) > 0) {
        $responseMessage .= "\n\n🔗 Found " . count($links) . " link(s):\n";
        foreach ($links as $link) {
            $responseMessage .= "• " . $link . "\n";
        }
    }
    
    if ($dealId) {
        $responseMessage .= "\n💼 Deal ID: " . $dealId;
    }

    // Bitrix24 API endpoint for sending messages
    $url = "https://cultiv.bitrix24.com/rest/im.message.add.json?auth=" . $accessToken;

    // Message payload
    $messageData = [
        'DIALOG_ID' => $dialogId,
        'MESSAGE' => $responseMessage,
        'SYSTEM' => 'N'
    ];

    // Send POST request
    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($messageData)
        ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        error_log("❌ Failed to send response message to dialog $dialogId");
    } else {
        error_log("✅ Response message sent to dialog $dialogId");
        error_log("Response: $response");
    }
}

// Always respond with 200 OK
echo json_encode(["result" => "ok"]);
