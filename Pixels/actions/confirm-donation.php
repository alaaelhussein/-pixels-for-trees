<?php
require_once __DIR__ . "/../includes/app/boot.php";

post_only();
$user = require_user();
$id = (string) ($_POST["id"] ?? "");
$item = find_donation($id);

if (!csrf_ok($_POST["csrf"] ?? null) || !$item) {
    set_flash("error", "Invalid donation.");
    go("../grid.php");
}

$owner = ($item["userId"] ?? "") === $user["id"];
$admin = ($user["role"] ?? "user") === "admin";

if (!$owner && !$admin) {
    go("../index.php");
}

$result = handle_webhook([
    "event" => "donation.confirmed",
    "donationId" => $id,
    "providerId" => make_id("every"),
]);

set_flash($result["ok"] ? "success" : "error", $result["text"]);
go($admin ? "../admin.php" : "../impact.php");
