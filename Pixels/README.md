
  # Maquettes plateforme Pixels for Trees

  Ce dépôt contient une version PHP/HTML/CSS/JS du site Pixels for Trees. L’interface et les contenus restent identiques aux maquettes.
  Projet Figma : https://www.figma.com/design/KFs6ilu9HAJETZRgLxGfVE/Maquettes-plateforme-Pixels-for-Trees.

  ## Lancer le site (PHP)

  Dans le dossier du projet :
  - `php -S localhost:8000`
  - Ouvrir `http://localhost:8000/index.php`

  ## Arborescence utile

  - `index.php` : page d’accueil (landing)
  - `grid.php` : sélection de pixels
  - `recap.php` : récapitulatif du don
  - `impact.php` : tableau d’impact utilisateur
  - `admin.php` : dashboard administrateur
  - `includes/header.php` : en-tête commun + navigation
  - `includes/footer.php` : pied de page commun
  - `includes/icons.php` : icônes SVG (remplace Lucide)
  - `includes/mock-data.php` : données statiques de démonstration
  - `assets/js/pixel-grid.js` : moteur de grille (rendu + interactions)
  - `assets/js/landing-page.js` : initialise la grille sur la landing
  - `assets/js/grid-page.js` : sélection de pixels + calculs
  - `assets/js/recap-page.js` : lecture de la sélection + récapitulatif
  - `assets/js/admin-page.js` : grille globale admin
  - `includes/header.php` : charge Tailwind via CDN pour les styles

  ## Fonctions principales (résumé)

  - `createPixelGrid(container, options)` : construit la grille, gère la sélection et les tooltips des pixels financés.
  - `generateMockPixels(count, gridSize)` : génère des pixels financés fictifs pour l’affichage.
  - `grid-page.js` : met à jour le compteur, le montant total et l’état du bouton “Continuer”.
  - `recap-page.js` : lit la sélection depuis `localStorage`, affiche le récapitulatif et le bouton de confirmation.
  - `header.php` / `footer.php` : assurent le layout commun et la navigation.

  ## Notes

  - Les données sont simulées pour l’instant (voir `includes/mock-data.php`).
  - La sélection de pixels passe par `localStorage` entre `grid.php` et `recap.php`.
  