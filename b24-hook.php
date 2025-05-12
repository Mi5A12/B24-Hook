<?php
$raw = file_get_contents("php://input");

// TEMP DEBUG — log raw incoming body
error_log("=== RAW INPUT ===");
error_log($raw);

// Also dump parsed form keys
parse_str($raw, $parsed);
error_log("=== Parsed Keys ===");
error_log(print_r($parsed, true));

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
