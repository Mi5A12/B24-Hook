<?php
file_put_contents("b24-log.txt", date("Y-m-d H:i:s") . "\n" . file_get_contents("php://input") . "\n\n", FILE_APPEND);
echo json_encode(["result" => "ok"]);