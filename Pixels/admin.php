<?php
require_once __DIR__ . "/includes/mock-data.php";
$pageTitle = "Dashboard administrateur";
$isLoggedIn = true;
$username = "Admin";
require_once __DIR__ . "/includes/header.php";

$totalAmount = array_reduce($mockDonations, function ($sum, $donation) {
    return $sum + $donation["amount"];
}, 0);

$totalPixels = array_reduce($mockDonations, function ($sum, $donation) {
    return $sum + $donation["pixels"];
}, 0);

$uniqueDonors = count(array_unique(array_map(function ($donation) {
    return $donation["username"];
}, $mockDonations)));

$progressPercent = $totalPixels > 0 ? ($totalPixels / 1000000) * 100 : 0;
?>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <main class="flex-1 py-8 px-4">
      <div class="max-w-7xl mx-auto">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard administrateur</h1>
          <p class="text-gray-600">Vue d'ensemble de la plateforme et des contributions</p>
        </div>

        <div class="grid md:grid-cols-4 gap-6 mb-12">
          <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm">Montant total des dons</p>
                <p class="text-3xl font-semibold text-gray-900 mt-2"><?= number_format($totalAmount, 0, ",", " ") ?> €</p>
              </div>
              <div class="text-green-600 bg-gray-50 p-3 rounded-full">
                <?= icon_dollar("w-8 h-8") ?>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm">Pixels financés</p>
                <p class="text-3xl font-semibold text-gray-900 mt-2"><?= number_format($totalPixels, 0, ",", " ") ?></p>
              </div>
              <div class="text-orange-600 bg-gray-50 p-3 rounded-full">
                <?= icon_sparkles("w-8 h-8") ?>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm">Nombre de donateurs</p>
                <p class="text-3xl font-semibold text-gray-900 mt-2"><?= $uniqueDonors ?></p>
              </div>
              <div class="text-blue-600 bg-gray-50 p-3 rounded-full">
                <?= icon_users("w-8 h-8") ?>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm">Organisations</p>
                <p class="text-3xl font-semibold text-gray-900 mt-2">2</p>
              </div>
              <div class="text-purple-600 bg-gray-50 p-3 rounded-full">
                <?= icon_building("w-8 h-8") ?>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Derniers dons</h2>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pixels</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut Webhook</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($mockDonations as $donation): ?>
                  <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($donation["date"]) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-xs font-medium text-gray-600">
                          <?php
                          $initial = function_exists("mb_substr")
                              ? mb_substr($donation["username"], 0, 1)
                              : substr($donation["username"], 0, 1);
                          ?>
                          <?= htmlspecialchars($initial) ?>
                        </div>
                        <span class="font-medium text-gray-900"><?= htmlspecialchars($donation["username"]) ?></span>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="font-semibold text-green-600"><?= $donation["amount"] ?> €</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="font-medium text-gray-900"><?= $donation["pixels"] ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <?php
                      $statusClass = $donation["webhookStatus"] === "Validé"
                          ? "bg-green-100 text-green-800"
                          : "bg-yellow-100 text-yellow-800";
                      ?>
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $statusClass ?>">
                        <?= htmlspecialchars($donation["webhookStatus"]) ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-6">Vue globale de la grille</h2>
          <div class="flex justify-center">
            <div id="admin-grid"></div>
          </div>
          <div class="mt-6 text-center">
            <p class="text-gray-600">
              Progression actuelle :
              <span class="font-semibold text-gray-900">
                <?= number_format($totalPixels, 0, ",", " ") ?> / 1,000,000 pixels
              </span>
              (<?= number_format($progressPercent, 2, ",", " ") ?>%)
            </p>
            <div class="w-full bg-gray-200 rounded-full h-3 mt-3 max-w-md mx-auto">
              <div
                class="bg-green-600 h-3 rounded-full transition-all"
                style="width: <?= $progressPercent ?>%"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script type="module" src="assets/js/admin-page.js"></script>
<?php
require_once __DIR__ . "/includes/footer.php";
?>
