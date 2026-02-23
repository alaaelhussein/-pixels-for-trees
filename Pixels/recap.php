<?php
require_once __DIR__ . "/includes/mock-data.php";
$pageTitle = "Récapitulatif";
$isLoggedIn = true;
$username = $currentUser["name"];
require_once __DIR__ . "/includes/header.php";
?>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <main class="flex-1 py-12 px-4">
      <div class="max-w-2xl mx-auto">
        <a
          href="grid.php"
          class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 transition"
        >
          <?= icon_arrow_left("w-5 h-5") ?>
          Retour à la grille
        </a>

        <div class="bg-white rounded-lg shadow-md p-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-6">Récapitulatif de ta contribution</h1>

          <div class="space-y-4 mb-8">
            <div class="bg-gray-50 rounded-lg p-6">
              <div class="grid grid-cols-2 gap-6">
                <div>
                  <p class="text-gray-600 text-sm mb-1">Nombre de pixels</p>
                  <p id="pixel-count" class="text-3xl font-bold text-gray-900">0</p>
                </div>
                <div>
                  <p class="text-gray-600 text-sm mb-1">Montant total</p>
                  <p id="total-amount" class="text-3xl font-bold text-green-600">0 €</p>
                </div>
              </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
              <div class="flex gap-3">
                <?= icon_check_circle("w-6 h-6 text-green-600 flex-shrink-0 mt-0.5") ?>
                <div>
                  <p class="font-semibold text-green-900 mb-1">Impact estimé</p>
                  <p class="text-green-800">
                    Environ <span id="impact-count" class="font-semibold">0</span> arbres seront plantés grâce à ta générosité !
                  </p>
                </div>
              </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
              <div class="flex gap-3">
                <?= icon_shield("w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5") ?>
                <div>
                  <p class="font-semibold text-blue-900 mb-1">Paiement sécurisé</p>
                  <p class="text-blue-800 text-sm">
                    Les dons sont traités de manière sécurisée via
                    <span class="font-semibold">Every.org</span> et reversés
                    intégralement à <span class="font-semibold">Plant With Purpose</span>.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="mb-8 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600 mb-2">Aperçu de ta sélection :</p>
            <div id="pixel-preview" class="flex gap-1 flex-wrap"></div>
          </div>

          <div class="space-y-3">
            <button
              id="confirm-button"
              class="w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-lg font-semibold text-lg transition shadow-md hover:shadow-lg"
            >
              Je confirme et je donne
            </button>
            <a
              href="grid.php"
              class="block w-full text-center text-gray-600 hover:text-gray-900 py-2 transition"
            >
              Modifier ma sélection
            </a>
          </div>
        </div>

        <div class="mt-8 text-center text-sm text-gray-600">
          <p>
            En confirmant, tu seras redirigé vers Every.org pour finaliser ton don de manière sécurisée.
          </p>
        </div>
      </div>
    </main>
  </div>

  <script type="module" src="assets/js/recap-page.js"></script>
<?php
require_once __DIR__ . "/includes/footer.php";
?>
