<?php
require_once __DIR__ . "/../includes/app/boot.php";

$user = require_user();
$id   = (string) ($_GET["id"] ?? "");
$item = find_donation($id);

if (!$item || ($item["userId"] ?? "") !== $user["id"]) {
    json_out(["confirmed" => false], 404);
}

json_out(["confirmed" => ($item["status"] ?? "") === "confirmed"]);
