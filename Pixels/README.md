
  # Pixels for Trees

  Ce dépôt contient une version simple en PHP, HTML, CSS et JavaScript.

  ## Lancer le site

  Depuis la racine du dépôt :
  - `php -S localhost:8000 -t ./Pixels`
  - Ouvrir `http://localhost:8000/index.php`

  ## Pages utiles

  - `index.php` : accueil avec stats réelles du mur
  - `register.php` / `login.php` : création de compte et connexion
  - `grid.php` : sélection protégée des pixels
  - `recap.php` : récapitulatif avant création du don
  - `donate.php` : confirmation du don
  - `impact.php` : historique personnel
  - `admin.php` : écran admin

  ## Dossiers utiles

  - `includes/app/` : logique PHP simple
  - `includes/` : layout partagé
  - `actions/` : traitements POST
  - `api/webhook.php` : webhook de confirmation
  - `assets/js/` : scripts navigateur
  - `data/` : stockage JSON local

  ## Notes

  - Les pixels payés sont déduits des dons confirmés.
  - La sélection temporaire passe par `localStorage` entre `grid.php` et `recap.php`.
  - Le premier compte créé devient admin.
  