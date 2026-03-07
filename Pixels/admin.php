<?php
require_once __DIR__ . "/includes/app/boot.php";
require_once __DIR__ . "/includes/site-tools.php";

require_admin();

$pageTitle = "Admin dashboard";
$stats = wall_stats();
$users = users_all();
$donations = donations_all();
$webhooks = webhooks_all();
$pending = pending_donations();
$wallPixels = grid_pixels();
$progress = $stats["pixels"] > 0
    ? ($stats["pixels"] / 1000000) * 100
    : 0;

require_once __DIR__ . "/includes/header.php";
?>
<div class="min-h-screen flex flex-col bg-gray-50">
  <main class="flex-1 py-8 px-4">
    <div class="max-w-7xl mx-auto space-y-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin dashboard</h1>
        <p class="text-gray-600">Real data, webhooks, users, and donations.</p>
      </div>
      <div class="grid md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6"><p class="text-sm text-gray-600">Users</p><p class="text-3xl font-semibold"><?= $stats["users"] ?></p></div>
        <div class="bg-white rounded-lg shadow-md p-6"><p class="text-sm text-gray-600">Donors</p><p class="text-3xl font-semibold"><?= $stats["donors"] ?></p></div>
        <div class="bg-white rounded-lg shadow-md p-6"><p class="text-sm text-gray-600">Donations</p><p class="text-3xl font-semibold"><?= money($stats["amount"]) ?></p></div>
        <div class="bg-white rounded-lg shadow-md p-6"><p class="text-sm text-gray-600">Pixels</p><p class="text-3xl font-semibold"><?= $stats["pixels"] ?></p></div>
      </div>
      <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
        <h2 class="text-xl font-semibold">Admin tools</h2>
        <div class="flex flex-wrap gap-3">
          <form method="post" action="actions/admin.php"><input type="hidden" name="csrf" value="<?= csrf_token() ?>" /><input type="hidden" name="action" value="seed" /><button class="bg-orange-500 text-white rounded-lg px-4 py-2">Create 50 demo pixels</button></form>
          <form method="post" action="actions/admin.php"><input type="hidden" name="csrf" value="<?= csrf_token() ?>" /><input type="hidden" name="action" value="retry" /><button class="bg-blue-600 text-white rounded-lg px-4 py-2">Retry tree sync</button></form>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Pending donations</h2>
        <?php foreach ($pending as $item): ?>
          <form method="post" action="actions/admin.php" class="flex items-center justify-between border-b py-3 gap-4">
            <input type="hidden" name="csrf" value="<?= csrf_token() ?>" />
            <input type="hidden" name="action" value="confirm" />
            <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>" />
            <span><?= htmlspecialchars($item['userName']) ?> · <?= count(donation_pixels($item)) ?> px</span>
            <button class="text-blue-600">Confirm</button>
          </form>
        <?php endforeach; ?>
      </div>
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200"><h2 class="text-xl font-semibold">Latest donations</h2></div>
        <div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pixels</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Webhook</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trees</th></tr></thead><tbody class="bg-white divide-y divide-gray-200">
          <?php foreach (array_reverse($donations) as $item): ?>
            <tr><td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars(substr($item['createdAt'], 0, 16)) ?></td><td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($item['userName']) ?></td><td class="px-6 py-4 whitespace-nowrap"><?= money((int) $item['amount']) ?></td><td class="px-6 py-4 whitespace-nowrap"><?= count(donation_pixels($item)) ?></td><td class="px-6 py-4 whitespace-nowrap"><span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= webhook_badge($item['status']) ?>"><?= htmlspecialchars($item['status']) ?></span></td><td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($item['treeState'] ?? 'pending') ?></td></tr>
          <?php endforeach; ?>
        </tbody></table></div>
      </div>
      <div class="grid lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden"><div class="p-6 border-b"><h2 class="text-xl font-semibold">Users</h2></div><div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th></tr></thead><tbody class="divide-y"><?php foreach ($users as $item): ?><tr><td class="px-6 py-4"><?= htmlspecialchars($item['name']) ?></td><td class="px-6 py-4"><?= htmlspecialchars($item['email']) ?></td><td class="px-6 py-4"><?= htmlspecialchars($item['role']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
        <div class="bg-white rounded-lg shadow-md overflow-hidden"><div class="p-6 border-b"><h2 class="text-xl font-semibold">Webhooks</h2></div><div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Donation</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th></tr></thead><tbody class="divide-y"><?php foreach (array_reverse($webhooks) as $item): ?><tr><td class="px-6 py-4"><?= htmlspecialchars(substr($item['createdAt'], 0, 16)) ?></td><td class="px-6 py-4"><?= htmlspecialchars($item['event']) ?></td><td class="px-6 py-4"><?= htmlspecialchars($item['donationId']) ?></td><td class="px-6 py-4"><?= htmlspecialchars($item['status']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
      </div>
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Global grid view</h2>
        <div class="flex justify-center"><div id="admin-grid"></div></div>
        <div class="mt-6 text-center"><p class="text-gray-600">Progress: <span class="font-semibold text-gray-900"><?= $stats['pixels'] ?> / 1,000,000 pixels</span> (<?= number_format($progress, 3, ',', ' ') ?>%)</p></div>
      </div>
    </div>
  </main>
</div>
<script id="wall-data" type="application/json"><?= safe_json($wallPixels) ?></script>
<script type="module" src="assets/js/admin-page.js"></script>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
