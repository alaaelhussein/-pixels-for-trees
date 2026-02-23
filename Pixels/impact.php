<?php
require_once __DIR__ . "/includes/mock-data.php";
$pageTitle = "Mon impact";
$isLoggedIn = true;
$username = $currentUser["name"];
require_once __DIR__ . "/includes/header.php";

function format_date_fr(string $dateString): string {
    $date = new DateTime($dateString);
    if (class_exists("IntlDateFormatter")) {
        $formatter = new IntlDateFormatter(
            "fr_FR",
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );
        return $formatter->format($date);
    }
    return $date->format("d/m/Y");
}
?>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <main class="flex-1 py-8 px-4">
      <div class="max-w-7xl mx-auto">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Mon impact</h1>
          <p class="text-gray-600">
            Retrouve ici toutes tes contributions et l'impact que tu as généré.
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-12">
          <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm">Pixels financés</p>
                <p class="text-3xl font-semibold text-gray-900 mt-2"><?= $currentUser["totalPixels"] ?></p>
              </div>
              <div class="text-orange-600 bg-gray-50 p-3 rounded-full">
                <?= icon_sparkles("w-8 h-8") ?>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm">Montant total donné</p>
                <p class="text-3xl font-semibold text-gray-900 mt-2"><?= $currentUser["totalAmount"] ?> €</p>
              </div>
              <div class="text-green-600 bg-gray-50 p-3 rounded-full">
                <?= icon_dollar("w-8 h-8") ?>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-600 text-sm">Arbres financés (estimés)</p>
                <p class="text-3xl font-semibold text-gray-900 mt-2"><?= $currentUser["totalTrees"] ?></p>
              </div>
              <div class="text-green-700 bg-gray-50 p-3 rounded-full">
                <?= icon_tree_pine("w-8 h-8") ?>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow-lg p-8 mb-12">
          <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
              <?= icon_tree_pine("w-8 h-8") ?>
            </div>
            <div>
              <h2 class="text-2xl font-bold">Merci pour ton engagement !</h2>
              <p class="text-green-100">
                Grâce à toi, <?= $currentUser["totalTrees"] ?> arbres vont être plantés par Plant With Purpose
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Historique de mes contributions</h2>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pixels</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($mockContributions as $contribution): ?>
                  <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-2 text-gray-900">
                        <?= icon_calendar("w-4 h-4 text-gray-400") ?>
                        <?= htmlspecialchars(format_date_fr($contribution["date"])) ?>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-2">
                        <?= icon_sparkles("w-4 h-4 text-orange-500") ?>
                        <span class="font-medium text-gray-900"><?= $contribution["pixels"] ?></span>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="font-semibold text-green-600"><?= $contribution["amount"] ?> €</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <?= htmlspecialchars($contribution["status"]) ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <?php if (count($mockContributions) === 0): ?>
            <div class="p-12 text-center text-gray-500">
              <?= icon_tree_pine("w-12 h-12 mx-auto mb-4 text-gray-400") ?>
              <p>Aucune contribution pour le moment</p>
            </div>
          <?php endif; ?>
        </div>

        <div class="mt-12 bg-white rounded-lg shadow-md p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Ta contribution sur la grille</h2>
          <div class="bg-gray-50 rounded-lg p-8 text-center">
            <p class="text-gray-600 mb-4">
              Tes <?= $currentUser["totalPixels"] ?> pixels sont dispersés sur la grille mondiale
            </p>
            <div class="inline-flex gap-1 flex-wrap max-w-md justify-center">
              <?php
              $maxPixels = min($currentUser["totalPixels"], 100);
              for ($i = 0; $i < $maxPixels; $i += 1):
              ?>
                <div class="w-4 h-4 bg-green-500 rounded border border-green-600"></div>
              <?php endfor; ?>
              <?php if ($currentUser["totalPixels"] > 100): ?>
                <div class="flex items-center justify-center text-sm text-gray-600 ml-2">
                  +<?= $currentUser["totalPixels"] - 100 ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
<?php
require_once __DIR__ . "/includes/footer.php";
?>
