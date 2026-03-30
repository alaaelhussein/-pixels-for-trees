<?php
require_once __DIR__ . "/../includes/app/boot.php";

allow_cors();

$secret = (string) (
    $_GET["token"]
    ?? $_GET["hash"]
    ?? $_SERVER["HTTP_X_WEBHOOK_SECRET"]
    ?? ""
);

// use every.org partner token, or fall back to the internal secret
$expected = every_webhook_token() ?: webhook_secret();
if ($secret !== $expected) {
    json_out(["ok" => false], 403);
}

$body = file_get_contents("php://input") ?: "{}";
$data = json_decode($body, true);

if (!is_array($data)) {
    json_out(["ok" => false], 422);
}

$result = handle_webhook($data);
json_out($result, $result["ok"] ? 200 : 422);
