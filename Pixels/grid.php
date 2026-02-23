<?php
require_once __DIR__ . "/includes/mock-data.php";
$pageTitle = "Choisis tes pixels";
$isLoggedIn = true;
$username = $currentUser["name"];
require_once __DIR__ . "/includes/header.php";
?>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <main class="flex-1 py-8 px-4">
      <div class="max-w-7xl mx-auto">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Choisis tes pixels</h1>
          <p class="text-gray-600">
            Clique sur les pixels gris pour les sélectionner. Chaque pixel représente 1 arbre à planter (≈5€).
          </p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
          <div class="lg:col-span-2">
            <div id="grid-container"></div>
            <div class="mt-4 flex gap-6 text-sm">
              <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-gray-100 border border-gray-300"></div>
                <span>Libre</span>
              </div>
              <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-500"></div>
                <span>Financé</span>
              </div>
              <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-orange-400"></div>
                <span>Sélectionné</span>
              </div>
            </div>
          </div>

          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Ma sélection</h2>

              <div class="space-y-4 mb-6">
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                  <span class="text-gray-600">Pixels sélectionnés</span>
                  <span id="selected-count" class="text-2xl font-bold text-gray-900">0</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                  <span class="text-gray-600">Montant estimé</span>
                  <span id="total-amount" class="text-2xl font-bold text-green-600">0 €</span>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                  <p class="text-sm text-green-800">
                    ≈ <span id="total-trees" class="font-semibold">0</span> arbres seront plantés grâce à ta contribution !
                  </p>
                </div>
              </div>

              <button
                id="continue-button"
                disabled
                class="w-full bg-orange-500 hover:bg-orange-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2"
              >
                Continuer vers le don
                <?= icon_arrow_right("w-5 h-5") ?>
              </button>

              <p id="empty-hint" class="text-sm text-gray-500 text-center mt-3">
                Sélectionne au moins un pixel pour continuer
              </p>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script type="module" src="assets/js/grid-page.js"></script>
<?php
require_once __DIR__ . "/includes/footer.php";
?>
