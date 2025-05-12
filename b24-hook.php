<?php
// Parse Bitrix webhook POST body
parse_str(file_get_contents("php://input"), $parsed);

// Safely decode the `data` key (if it exists)
$data = isset($parsed['data']) ? json_decode($parsed['data'], true) : [];

// Log full payload
error_log("=== Incoming Webhook ===");
error_log(print_r($data, true));

// Extract useful info
$message  = $data['PARAMS']['MESSAGE'] ?? '';
$dialogId = $data['PARAMS']['DIALOG_ID'] ?? '';
$userId   = $data['PARAMS']['FROM_USER_ID'] ?? '';

// Extract any links
preg_match_all('/https?:\/\/[^\s]+/', $message, $matches);
$foundLinks = $matches[0];

// Log result
error_log("User ID: $userId");
error_log("Dialog ID: $dialogId");
error_log("Message: $message");
error_log("Links Found: " . implode(', ', $foundLinks));

// Reply OK to Bitrix
echo json_encode(["result" => "ok"]);
