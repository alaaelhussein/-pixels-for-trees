<?php
$pageTitle = "Pixels for Trees";
$isLoggedIn = false;
require_once __DIR__ . "/includes/header.php";
?>
  <div class="min-h-screen flex flex-col bg-gradient-to-b from-blue-50 to-white">
    <section class="py-20 px-4">
      <div class="max-w-7xl mx-auto text-center">
        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">Pixels for Trees</h1>
        <p class="text-2xl text-gray-700 mb-4">1 pixel = 1 tree donated</p>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
          Allume un pixel, finance un arbre, visualise ton impact.
        </p>
        <a
          href="grid.php"
          class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-lg text-lg font-semibold transition shadow-lg hover:shadow-xl"
        >
          Je participe
        </a>
      </div>
    </section>

    <section class="py-16 px-4 bg-white">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-center text-3xl font-bold text-gray-900 mb-12">
          La grille mondiale de pixels
        </h2>
        <div class="flex flex-col lg:flex-row gap-12 items-center justify-center">
          <div class="flex-shrink-0">
            <div id="landing-grid"></div>
            <div class="mt-4 flex gap-6 text-sm">
              <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-gray-100 border border-gray-300"></div>
                <span>Libre</span>
              </div>
              <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-500"></div>
                <span>Financé</span>
              </div>
            </div>
          </div>
          <div class="max-w-md space-y-4 text-gray-700">
            <p class="text-lg">
              Chaque pixel de cette grille de 1000×1000 représente un arbre à planter.
            </p>
            <p>
              Lorsque tu finances un pixel, il s'allume en vert et ton don est reversé
              à <span class="font-semibold">Plant With Purpose</span> via
              <span class="font-semibold">Every.org</span>.
            </p>
            <p>
              Survole les pixels verts pour découvrir qui a contribué et leurs messages !
            </p>
          </div>
        </div>
      </div>
    </section>

    <section id="how-it-works" class="py-16 px-4 bg-gray-50">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-center text-3xl font-bold text-gray-900 mb-12">
          Comment ça marche ?
        </h2>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <?= icon_pointer("w-8 h-8 text-green-600") ?>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Je choisis mes pixels</h3>
            <p class="text-gray-600">
              Sélectionne un ou plusieurs pixels sur la grille interactive.
            </p>
          </div>

          <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <?= icon_dollar("w-8 h-8 text-orange-600") ?>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Je fais un don via Every.org</h3>
            <p class="text-gray-600">
              Effectue ton don sécurisé via notre partenaire de confiance.
            </p>
          </div>

          <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <?= icon_sparkles("w-8 h-8 text-blue-600") ?>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">3. Mon pixel s'allume</h3>
            <p class="text-gray-600">
              Ton pixel s'allume et finance un arbre planté par Plant With Purpose.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="py-16 px-4 bg-green-600 text-white">
      <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-3 gap-8 text-center">
          <div>
            <div class="flex items-center justify-center mb-2">
              <?= icon_tree_pine("w-12 h-12") ?>
            </div>
            <p class="text-4xl font-bold mb-2">12,547</p>
            <p class="text-green-100">Arbres financés</p>
          </div>
          <div>
            <div class="flex items-center justify-center mb-2">
              <?= icon_sparkles("w-12 h-12") ?>
            </div>
            <p class="text-4xl font-bold mb-2">12,547</p>
            <p class="text-green-100">Pixels allumés</p>
          </div>
          <div>
            <div class="flex items-center justify-center mb-2">
              <?= icon_dollar("w-12 h-12") ?>
            </div>
            <p class="text-4xl font-bold mb-2">62,735 €</p>
            <p class="text-green-100">Dons collectés</p>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script type="module" src="assets/js/landing-page.js"></script>
<?php
require_once __DIR__ . "/includes/footer.php";
?>
