<?php
require_once __DIR__ . "/../includes/app/boot.php";

post_only();
$user = require_user();
$data = json_decode($_POST["pixels"] ?? "[]", true);
$message = trim(strip_tags($_POST["message"] ?? ""));

if (!csrf_ok($_POST["csrf"] ?? null) || !is_array($data)) {
    set_flash("error", "Invalid request.");
    go("../grid.php");
}

$pixels = [];

foreach ($data as $item) {
    $x = (int) ($item["x"] ?? -1);
    $y = (int) ($item["y"] ?? -1);
    $color = strtoupper((string) ($item["color"] ?? ""));
    if (!valid_pixel($x, $y) || !valid_color($color)) {
        set_flash("error", "Invalid pixels.");
        go("../grid.php");
    }
    $pixels[] = ["x" => $x, "y" => $y, "color" => $color];
}

if (!$pixels || !pixels_free($pixels)) {
    set_flash("error", "Pixels are unavailable.");
    go("../grid.php");
}

$donation = create_donation($user, $pixels, $message);
go("../donate.php?id=" . urlencode($donation["id"]));
