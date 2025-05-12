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

    // Log everything
    error_log("User ID: $userId");
    error_log("Dialog ID: $dialogId");
    error_log("Message: $message");
    error_log("Links Found: " . implode(', ', $foundLinks));
} else {
    error_log("⚠️ Unexpected payload structure:");
    error_log(print_r($parsed, true));
}

// Always respond with 200 OK
echo json_encode(["result" => "ok"]);
