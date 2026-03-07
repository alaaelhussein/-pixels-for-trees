<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/wall.php";

function every_name_parts(string $name): array
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    $first = trim((string) array_shift($parts));
    $last = trim(implode(" ", $parts));
    return [$first, $last];
}

function every_metadata(array $donation): string
{
    $data = [
        "donationId" => $donation["id"],
        "userId" => $donation["userId"],
        "pixels" => count(donation_pixels($donation)),
    ];
    return base64_encode(json_encode($data) ?: "{}");
}

function every_done_url(array $donation, string $state): string
{
    $join = $state === "cancel" ? "cancel=1" : "done=1";
    return app_url() . "/donate.php?id=" . urlencode($donation["id"]) . "&" . $join;
}

function every_donate_url(array $donation, array $user): string
{
    [$first, $last] = every_name_parts((string) ($user["name"] ?? ""));
    $params = [
        "amount" => (string) ($donation["amount"] ?? 0),
        "min_value" => (string) price_per_pixel(),
        "frequency" => "ONCE",
        "email" => (string) ($user["email"] ?? ""),
        "first_name" => $first,
        "last_name" => $last,
        "description" => "Pixels for Trees · Plant With Purpose",
        "success_url" => every_done_url($donation, "done"),
        "exit_url" => every_done_url($donation, "cancel"),
        "partner_donation_id" => (string) $donation["id"],
        "partner_metadata" => every_metadata($donation),
        "require_share_info" => "true",
        "share_info" => "true",
        "designation" => every_designation(),
        "theme_color" => every_theme_color(),
        "method" => every_methods(),
    ];

    if (every_webhook_token() !== "") {
        $params["webhook_token"] = every_webhook_token();
    }

    $params = array_filter($params,
        fn($value) => $value !== "");
    return rtrim(every_url(), "/") . "/" . rawurlencode(every_nonprofit())
        . "?" . http_build_query($params) . "#donate";
}

function is_every_webhook(array $input): bool
{
    return isset($input["chargeId"])
        || isset($input["partnerDonationId"])
        || isset($input["toNonprofit"]);
}

function every_webhook_donation_id(array $input): string
{
    $id = trim((string) ($input["partnerDonationId"] ?? ""));

    if ($id !== "") {
        return $id;
    }

    $meta = $input["partnerMetadata"] ?? [];

    if (is_array($meta)) {
        return trim((string) ($meta["donationId"] ?? ""));
    }

    if (!is_string($meta) || $meta === "") {
        return "";
    }

    $data = json_decode($meta, true);

    if (!is_array($data)) {
        return "";
    }

    return trim((string) ($data["donationId"] ?? ""));
}

function every_webhook_ok(array $donation, array $input): bool
{
    $slug = strtolower((string) ($input["toNonprofit"]["slug"] ?? ""));
    $amount = round((float) ($input["amount"] ?? 0), 2);
    return $slug === "" || $slug === strtolower(every_nonprofit())
        ? abs($amount - (float) ($donation["amount"] ?? 0)) < 0.01
        : false;
}

function every_confirm_data(array $input): array
{
    $first = trim((string) ($input["firstName"] ?? ""));
    $last = trim((string) ($input["lastName"] ?? ""));
    $name = trim($first . " " . $last);
    return [
        "provider" => "every.org",
        "currency" => (string) ($input["currency"] ?? "USD"),
        "paymentMethod" => (string) ($input["paymentMethod"] ?? ""),
        "providerAmount" => round((float) ($input["amount"] ?? 0), 2),
        "nonprofitSlug" => (string) ($input["toNonprofit"]["slug"] ?? every_nonprofit()),
        "nonprofitName" => (string) ($input["toNonprofit"]["name"] ?? "Plant With Purpose"),
        "sharedEmail" => (string) ($input["email"] ?? ""),
        "sharedName" => $name,
        "publicMessage" => trim((string) ($input["publicTestimony"] ?? "")),
        "privateNote" => trim((string) ($input["privateNote"] ?? "")),
        "donationDate" => (string) ($input["donationDate"] ?? date(DATE_ATOM)),
    ];
}