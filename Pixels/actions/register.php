<?php
require_once __DIR__ . "/../includes/app/boot.php";
require_once __DIR__ . "/../includes/app/http.php";

post_only();

$name = clean_name($_POST["name"] ?? "");
$email = strtolower(trim($_POST["email"] ?? ""));
$password = (string) ($_POST["password"] ?? "");
$next = safe_next_path($_POST["next"] ?? "grid.php");

if (!csrf_ok($_POST["csrf"] ?? null)) {
    set_flash("error", "Invalid session.");
    go("../register.php?next=" . urlencode($next));
}

if ($name === "" || !valid_email($email) || !valid_password($password)) {
    set_flash("error", "Invalid form.");
    go("../register.php?next=" . urlencode($next));
}

if (find_user_by_email($email)) {
    set_flash("error", "Email already in use.");
    go("../register.php?next=" . urlencode($next));
}

$user = create_user($name, $email, $password);
login_user($user);
set_flash("success", "Account created.");
go("../" . $next);
