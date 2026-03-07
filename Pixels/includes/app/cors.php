<?php
function allow_cors(): void
{
    header("Access-Control-Allow-Origin: " . cors_origin());
    header("Access-Control-Allow-Headers: Content-Type, X-Webhook-Secret");
    header("Access-Control-Allow-Methods: POST, OPTIONS");

    if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "OPTIONS") {
        exit;
    }
}
