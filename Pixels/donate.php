<?php
require_once __DIR__ . "/includes/app/boot.php";

$user = require_user();
$item = find_donation((string) ($_GET["id"] ?? ""));

if (!$item || ($item["userId"] ?? "") !== $user["id"]) {
    go("grid.php");
}

$done = isset($_GET["done"]);
$cancel = isset($_GET["cancel"]);
$confirmed = ($item["status"] ?? "") === "confirmed";
$reserveLeft = donation_reserve_left($item);
$expired = !$confirmed && $reserveLeft === 0;
$reserveText = $reserveLeft > 0
  ? (string) ceil($reserveLeft / 60) . " minute(s) left"
  : "Expired";
$everyLink = every_donate_url($item, $user);
$pageTitle = "Payment";
require_once __DIR__ . "/includes/header.php";
?>
<div class="min-h-screen bg-gray-50 py-12 px-4">
  <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold mb-4">Payment</h1>
    <p class="text-gray-600 mb-6">
      Donation of <?= (int) $item["amount"] ?> $ for
      <?= count(donation_pixels($item)) ?> pixels.
    </p>
    <div class="space-y-4 mb-6">
      <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
        <p class="font-semibold text-gray-900">Plant With Purpose</p>
        <p class="text-sm text-gray-600 mt-1">
          Every.org completes the payment and sends the donor back here.
          The partner webhook then confirms the pixels.
        </p>
        <p class="mt-2 text-sm text-blue-700">
          Reservation window: <?= htmlspecialchars($reserveText) ?>
        </p>
      </div>
      <?php if ($confirmed): ?>
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
          Donation confirmed. The pixels are now locked on the wall.
        </div>
      <?php elseif ($expired): ?>
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
          This reservation expired. Go back to the wall and pick free cells again.
        </div>
      <?php elseif ($done): ?>
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-blue-800">
          Every.org return received. If the wall is not updated yet,
          wait for the webhook or reload this page.
        </div>
      <?php elseif ($cancel): ?>
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-800">
          Payment cancelled. You can reopen Every.org whenever you want.
        </div>
      <?php endif; ?>
    </div>
    <?php if (!$confirmed && !$expired): ?>
      <a href="<?= htmlspecialchars($everyLink) ?>"
        class="block text-center bg-blue-600 text-white rounded-lg py-3 mb-4">
        Continue on Every.org
      </a>
      <a href="donate.php?id=<?= urlencode($item['id']) ?>&done=1"
        class="block text-center text-blue-600 py-2 mb-4">
        I finished on Every.org
      </a>
      <a href="grid.php"
        class="block text-center text-gray-600 py-2">
        Edit my selection
      </a>
    <?php elseif ($confirmed): ?>
      <a href="impact.php"
        class="block text-center bg-green-600 text-white rounded-lg py-3 mb-4">
        View my impact
      </a>
    <?php else: ?>
      <a href="grid.php"
        class="block text-center bg-slate-700 text-white rounded-lg py-3 mb-4">
        Back to the wall
      </a>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
