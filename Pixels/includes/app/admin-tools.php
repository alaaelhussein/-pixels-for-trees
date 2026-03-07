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
