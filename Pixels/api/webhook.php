<?php
require_once __DIR__ . "/../includes/app/boot.php";

allow_cors();

$secret = (string) (
    $_GET["token"]
    ?? $_GET["hash"]
    ?? $_SERVER["HTTP_X_WEBHOOK_SECRET"]
    ?? ""
);

if ($secret !== webhook_secret()) {
    json_out(["ok" => false], 403);
}

$body = file_get_contents("php://input") ?: "{}";
$data = json_decode($body, true);

if (!is_array($data)) {
    json_out(["ok" => false], 422);
}

$result = handle_webhook($data);
json_out($result, $result["ok"] ? 200 : 422);
