parse_str(file_get_contents("php://input"), $parsed);

// Confirm structure is what we expect
$data = $parsed['data'] ?? [];

if (is_array($data)) {
    // Extract core info
    $message  = $data['PARAMS']['MESSAGE'] ?? '';
    $dialogId = $data['PARAMS']['DIALOG_ID'] ?? '';
    $userId   = $data['PARAMS']['FROM_USER_ID'] ?? '';

    // Extract Deal ID from CHAT_ENTITY_DATA_2
    $entityData2 = $data['PARAMS']['CHAT_ENTITY_DATA_2'] ?? '';
    preg_match('/DEAL\|(\d+)/', $entityData2, $dealMatch);
    $dealId = $dealMatch[1] ?? '';

    // Extract links
    preg_match_all('/https?:\/\/[^\s]+/', $message, $matches);
    $foundLinks = $matches[0];

    // Extract Access Token from BOT Auth if available
    $botArray = $data['BOT'] ?? [];
    $botAuthToken = '';
    foreach ($botArray as $botData) {
        if (isset($botData['access_token'])) {
            $botAuthToken = $botData['access_token'];
            break;
        }
    }

    // Log output
    error_log("User ID: $userId");
    error_log("Dialog ID: $dialogId");
    error_log("Message: $message");
    error_log("Links Found: " . implode(', ', $foundLinks));
    error_log("Deal ID: $dealId");
    error_log("Access Token: $botAuthToken");
} else {
    error_log("⚠️ Unexpected payload structure:");
    error_log(print_r($parsed, true));
}

// Always respond
echo json_encode(["result" => "ok"]);
