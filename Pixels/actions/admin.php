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

if ($action === "delete") {
    delete_donation((string) ($_POST["id"] ?? ""));
}

if ($action === "reset") {
    reset_donations();
}

if ($action === "test_webhook") {
    $result = fire_test_webhook((string) ($_POST["id"] ?? ""));
    set_flash($result["ok"] ? "success" : "error", $result["ok"] ? "Test webhook fired OK." : ("Test webhook failed: " . ($result["error"] ?? "unknown")));
    go("../admin.php");
}

set_flash("success", "Admin action complete.");
go("../admin.php");
