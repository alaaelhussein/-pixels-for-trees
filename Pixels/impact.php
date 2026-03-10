<?php
require_once __DIR__ . "/includes/app/boot.php";
require_once __DIR__ . "/includes/site-tools.php";

$user = require_user();
$stats = user_stats($user);
$pageTitle = "My impact";

require_once __DIR__ . "/includes/header.php";
?>
<div class="min-h-screen flex flex-col bg-gray-50">
  <main class="flex-1 py-8 px-4">
    <div class="max-w-7xl mx-auto">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My impact</h1>
        <p class="text-gray-600">Real history for the current account.</p>
      </div>
      <div class="grid md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <p class="text-gray-600 text-sm">Funded pixels</p>
          <p class="text-3xl font-semibold text-gray-900 mt-2"><?= $stats["pixels"] ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <p class="text-gray-600 text-sm">Total amount</p>
          <p class="text-3xl font-semibold text-gray-900 mt-2"><?= money($stats["amount"]) ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <p class="text-gray-600 text-sm">Trees supported</p>
          <p class="text-3xl font-semibold text-gray-900 mt-2"><?= $stats["trees"] ?></p>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-900">My donations</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pixels</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php foreach ($stats["items"] as $item): ?>
                <tr class="hover:bg-gray-50 transition">
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900"><?= htmlspecialchars(format_date_fr(substr($item["createdAt"], 0, 10))) ?></td>
                  <td class="px-6 py-4 whitespace-nowrap"><?= count(donation_pixels($item)) ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-green-600"><?= money((int) $item["amount"]) ?></td>
                  <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($item["status"]) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">My pixels</h2>
        <div class="bg-gray-50 rounded-lg p-8 text-center">
          <div class="inline-flex gap-1 flex-wrap max-w-md justify-center">
            <?php foreach (array_slice($stats["wall"], 0, 120) as $pixel): ?>
              <div class="w-4 h-4 rounded border border-gray-300" style="background: <?= htmlspecialchars($pixel['color']) ?>"></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
