<?php
require_once __DIR__ . "/icons.php";
?>
  <footer class="bg-gray-900 text-gray-300 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h3 class="text-white font-semibold mb-4">Pixels for Trees</h3>
          <p class="text-sm">
            Allume un pixel, finance un arbre, visualise ton impact.
          </p>
        </div>

        <div>
          <h3 class="text-white font-semibold mb-4">Partenaires</h3>
          <div class="space-y-2 text-sm">
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 bg-green-600 rounded"></div>
              <span>Every.org - Plateforme de dons</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 bg-green-700 rounded"></div>
              <span>Plant With Purpose - Organisation</span>
            </div>
          </div>
        </div>

        <div>
          <h3 class="text-white font-semibold mb-4">Suivez-nous</h3>
          <div class="flex gap-4">
            <a href="#" class="hover:text-white transition" aria-label="Facebook">
              <?= icon_facebook("w-5 h-5") ?>
            </a>
            <a href="#" class="hover:text-white transition" aria-label="Twitter">
              <?= icon_twitter("w-5 h-5") ?>
            </a>
            <a href="#" class="hover:text-white transition" aria-label="Instagram">
              <?= icon_instagram("w-5 h-5") ?>
            </a>
          </div>
        </div>
      </div>

      <div class="border-t border-gray-800 mt-8 pt-8 text-sm text-center">
        <p>&copy; 2026 Pixels for Trees. Tous droits réservés. | <a href="#" class="hover:text-white">Mentions légales</a></p>
      </div>
    </div>
  </footer>
</body>
</html>
