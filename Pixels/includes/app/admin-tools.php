<?php
function seed_demo_pixels(): void
{
    $user = current_user();

    if (!$user) {
        return;
    }

    $pixels = [];
    $taken = wall_map();

    while (count($pixels) < 50) {
        $x = random_int(0, grid_size() - 1);
        $y = random_int(0, grid_size() - 1);
        $key = $x . "-" . $y;

        if (isset($taken[$key])) {
            continue;
        }

        $taken[$key] = true;
        $pixels[] = ["x" => $x, "y" => $y, "color" => "#22C55E"];
    }

    $donation = create_donation($user, $pixels, "demo");
    confirm_donation($donation, make_id("demo"));
}

function fire_test_webhook(string $donation_id): array
{
    $donation = find_donation($donation_id);

    if (!$donation) {
        return ["ok" => false, "error" => "Donation not found"];
    }

    $payload = [
        "event" => "donation.confirmed",
        "chargeId" => make_id("test"),
        "partnerDonationId" => $donation_id,
        "amount" => (string) ($donation["amount"] ?? 0),
        "currency" => "USD",
        "firstName" => "Test",
        "lastName" => "Donor",
        "email" => "test@pixels.test",
        "donationDate" => date(DATE_ATOM),
        "toNonprofit" => ["slug" => every_nonprofit(), "name" => "Plant With Purpose"],
    ];

    return handle_webhook($payload);
}
