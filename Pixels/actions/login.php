<?php
require_once __DIR__ . "/../includes/app/boot.php";
require_once __DIR__ . "/../includes/app/http.php";

post_only();

$email = strtolower(trim($_POST["email"] ?? ""));
$password = (string) ($_POST["password"] ?? "");
$next = safe_next_path($_POST["next"] ?? "grid.php");
$user = find_user_by_email($email);

if (!csrf_ok($_POST["csrf"] ?? null)) {
    set_flash("error", "Invalid session.");
    go("../login.php?next=" . urlencode($next));
}

if (!$user || !password_verify($password, $user["password"] ?? "")) {
    set_flash("error", "Invalid credentials.");
    go("../login.php?next=" . urlencode($next));
}

login_user($user);
set_flash("success", "Logged in.");
go("../" . $next);
