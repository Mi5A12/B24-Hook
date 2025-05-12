<?php
// Parse form-encoded input
parse_str(file_get_contents("php://input"), $parsed);

// Ensure 'data' is a string before decoding
$dataRaw = $parsed['data'] ?? '';
$data = is_string($dataRaw) ? json_decode($dataRaw, true) : [];

// Log full parsed result
error_log("=== Incoming Webhook ===");
error_log(print_r($data, true));

// Extract values
$message  = $data['PARAMS']['MESSAGE'] ?? '';
$dialogId = $data['PARAMS']['DIALOG_ID'] ?? '';
$userId   = $data['PARAMS']['FROM_USER_ID'] ?? '';

// Extract URLs
preg_match_all('/https?:\/\/[^\s]+/', $message, $matches);
$foundLinks = $matches[0];

// Log clean values
error_log("User ID: $userId");
error_log("Dialog ID: $dialogId");
error_log("Message: $message");
error_log("Links Found: " . implode(', ', $foundLinks));

// Respond to Bitrix
echo json_encode(["result" => "ok"]);
