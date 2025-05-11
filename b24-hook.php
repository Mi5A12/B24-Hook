<?php
// Receive JSON payload
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Log raw payload (optional: for testing)
file_put_contents("b24-log.txt", date("Y-m-d H:i:s") . "\n" . $input . "\n\n", FILE_APPEND);

// Extract message text
$message = $data['data']['PARAMS']['MESSAGE'] ?? '';
$dialogId = $data['data']['PARAMS']['DIALOG_ID'] ?? '';
$userId   = $data['data']['PARAMS']['USER_ID'] ?? '';

// Extract URLs from the message using regex
preg_match_all('/https?:\/\/[^\s]+/', $message, $matches);
$foundLinks = $matches[0];

// Debug output
file_put_contents("b24-log.txt", "User ID: $userId\nDialog: $dialogId\nMessage: $message\nLinks: " . implode(", ", $foundLinks) . "\n\n", FILE_APPEND);

// Respond to Bitrix
echo json_encode(["result" => "ok"]);
