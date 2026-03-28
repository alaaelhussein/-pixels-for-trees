<?php
// Charge le socle applicatif et les utilitaires HTTP (redirection/navigation).
require_once __DIR__ . "/includes/app/boot.php";
require_once __DIR__ . "/includes/app/http.php";

// Calcule l'URL de retour autorisee apres authentification.
$next = safe_next_path($_GET["next"] ?? "grid.php");

// Evite d'afficher la page de connexion si l'utilisateur est deja authentifie.
if (current_user()) {
  go($next);
}

// Definit le titre et affiche l'en-tete global.
$pageTitle = "Log in";
require_once __DIR__ . "/includes/header.php";
?>
<div class="min-h-screen bg-gray-50 py-12 px-4">
  <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold mb-6">Log in</h1>
    <!-- Formulaire de connexion avec protection CSRF et redirection post-login. -->
    <form method="post" action="actions/login.php" class="space-y-4">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>" />
      <input type="hidden" name="next" value="<?= htmlspecialchars($next) ?>" />
      <input name="email" type="email" placeholder="Email"
        class="w-full border rounded-lg px-4 py-3" required />
      <input name="password" type="password"
        placeholder="Password"
        class="w-full border rounded-lg px-4 py-3" required />
      <button class="w-full bg-orange-500 text-white rounded-lg py-3">
        Log in
      </button>
    </form>
    <!-- Lien de bascule vers la creation de compte en conservant la destination. -->
    <p class="text-sm mt-4 text-center">
      <a href="register.php?next=<?= urlencode($next) ?>" class="text-blue-600">Create an account</a>
    </p>
  </div>
</div>
<?php // Affiche le pied de page global. ?>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
