<?php
// Receive incoming JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Log raw payload for debugging
error_log("=== Incoming Webhook ===");
error_log($input);

// Extract useful info
$message = $data['data']['PARAMS']['MESSAGE'] ?? '';
$dialogId = $data['data']['PARAMS']['DIALOG_ID'] ?? '';
$userId   = $data['data']['PARAMS']['USER_ID'] ?? '';

// Extract URLs using regex
preg_match_all('/https?:\/\/[^\s]+/', $message, $matches);
$foundLinks = $matches[0];

// Log parsed values
error_log("User ID: $userId");
error_log("Dialog ID: $dialogId");
error_log("Message: $message");
error_log("Links Found: " . implode(', ', $foundLinks));

// Respond to Bitrix
echo json_encode(["result" => "ok"]);
