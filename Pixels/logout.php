<?php
require_once __DIR__ . "/includes/app/boot.php";

logout_user();
set_flash("success", "Logged out.");
go("index.php");
