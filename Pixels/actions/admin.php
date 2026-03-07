<?php
require_once __DIR__ . "/../includes/app/boot.php";

post_only();
require_admin();
$action = (string) ($_POST["action"] ?? "");

if (!csrf_ok($_POST["csrf"] ?? null)) {
    set_flash("error", "Invalid session.");
    go("../admin.php");
}

if ($action === "seed") {
    seed_demo_pixels();
}

if ($action === "retry") {
    retry_tree_sync();
}

if ($action === "confirm") {
    handle_webhook([
        "event" => "donation.confirmed",
        "donationId" => (string) ($_POST["id"] ?? ""),
        "providerId" => make_id("admin"),
    ]);
}

set_flash("success", "Admin action complete.");
go("../admin.php");
