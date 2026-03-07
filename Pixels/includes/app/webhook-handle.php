<?php
require_once __DIR__ . "/every.php";
require_once __DIR__ . "/donations-confirm.php";

function handle_every_partner_webhook(array $input): array
{
    $id = every_webhook_donation_id($input);
    $item = find_donation($id);
    $providerId = (string) ($input["chargeId"] ?? make_id("every"));
    $result = [
        "ok" => false,
        "text" => "donation not found",
        "event" => "every.partner",
        "donationId" => $id,
        "providerId" => $providerId,
    ];

    if (!$item) {
        return $result;
    }

    if (!every_webhook_ok($item, $input)) {
        $result["text"] = "invalid donation";
        return $result;
    }

    $result["ok"] = true;
    $result["text"] = "already done";

    if (($item["status"] ?? "") === "confirmed") {
        return $result;
    }

    if (!pixels_free(donation_pixels($item), (string) $item["id"])) {
        $result["ok"] = false;
        $result["text"] = "pixel locked";
        return $result;
    }

    confirm_donation(
        $item,
        $providerId,
        every_confirm_data($input)
    );
    $result["text"] = "confirmed";
    return $result;
}

function handle_local_webhook(array $input): array
{
    $id = (string) ($input["donationId"] ?? "");
    $event = (string) ($input["event"] ?? "");
    $item = find_donation($id);
    $result = [
        "ok" => false,
        "text" => "bad event",
        "event" => $event,
        "donationId" => $id,
        "providerId" => (string) ($input["providerId"] ?? ""),
    ];

    if ($item && $event === "donation.confirmed") {
        $result["ok"] = true;
        $result["text"] = "already done";

        if (($item["status"] ?? "") !== "confirmed") {
            $result["ok"] = pixels_free(
                donation_pixels($item),
                (string) $item["id"]
            );
            $result["text"] = $result["ok"]
                ? "confirmed"
                : "pixel locked";

            if ($result["ok"]) {
                confirm_donation(
                    $item,
                    (string) ($input["providerId"] ?? make_id("evt"))
                );
            }
        }
    }

    return $result;
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
