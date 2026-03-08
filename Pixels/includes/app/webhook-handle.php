<?php
require_once __DIR__ . "/every.php";
require_once __DIR__ . "/donations-confirm.php";

function webhook_result(
    bool $ok,
    string $text,
    string $event,
    string $donationId,
    string $providerId
): array {
    return [
        "ok" => $ok,
        "text" => $text,
        "event" => $event,
        "donationId" => $donationId,
        "providerId" => $providerId,
    ];
}

function confirm_webhook_donation(
    array $item,
    string $event,
    string $providerId,
    array $extra = []
): array {
    $id = (string) ($item["id"] ?? "");

    if (($item["status"] ?? "") === "confirmed") {
        return webhook_result(
            true,
            "already done",
            $event,
            $id,
            $providerId
        );
    }

    if (!pixels_free(donation_pixels($item), $id)) {
        return webhook_result(
            false,
            "pixel locked",
            $event,
            $id,
            $providerId
        );
    }

    confirm_donation($item, $providerId, $extra);

    return webhook_result(
        true,
        "confirmed",
        $event,
        $id,
        $providerId
    );
}

function handle_every_partner_webhook(array $input): array
{
    $id = every_webhook_donation_id($input);
    $item = find_donation($id);
    $providerId = (string) ($input["chargeId"] ?? make_id("every"));
    $event = "every.partner";

    if (!$item) {
        return webhook_result(
            false,
            "donation not found",
            $event,
            $id,
            $providerId
        );
    }

    if (!every_webhook_ok($item, $input)) {
        return webhook_result(
            false,
            "invalid donation",
            $event,
            $id,
            $providerId
        );
    }

    return confirm_webhook_donation(
        $item,
        $event,
        $providerId,
        every_confirm_data($input)
    );
}

function handle_local_webhook(array $input): array
{
    $id = (string) ($input["donationId"] ?? "");
    $event = (string) ($input["event"] ?? "");
    $item = find_donation($id);
    $providerId = (string) ($input["providerId"] ?? make_id("evt"));

    if ($event !== "donation.confirmed") {
        return webhook_result(
            false,
            "bad event",
            $event,
            $id,
            $providerId
        );
    }

    if (!$item) {
        return webhook_result(
            false,
            "donation not found",
            $event,
            $id,
            $providerId
        );
    }

    return confirm_webhook_donation(
        $item,
        $event,
        $providerId
    );
}

function handle_webhook(array $input): array
{
    $result = is_every_webhook($input)
        ? handle_every_partner_webhook($input)
        : handle_local_webhook($input);

    log_webhook([
        "id" => make_id("wh"),
        "event" => (string) ($result["event"] ?? "webhook"),
        "donationId" => (string) ($result["donationId"] ?? ""),
        "providerId" => (string) ($result["providerId"] ?? ""),
        "status" => $result["ok"] ? "done" : "failed",
        "payload" => $input,
        "createdAt" => date(DATE_ATOM),
    ]);
    return $result;
}
