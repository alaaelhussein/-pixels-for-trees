# Pixels for Trees - Mises a jour Canvas et UX (readme_max.md)

Ce document recapitule les modifications majeures apportees au projet pour transformer la grille de pixels en une experience interactive haute performance.

## Refonte Technique : HTML5 Canvas
Nous sommes passes d'un rendu base sur le DOM (10 000 elements div) a un moteur de rendu HTML5 Canvas.
- Performance : Rendu fluide a 60 FPS, meme sur mobile.
- Scalabilite : Capacite de gerer des milliers de pixels sans aucune latence.
- Rendu Optimise : Utilisation de requestAnimationFrame pour des animations constantes.

## Navigation Interactive
Le nouveau moteur permet une exploration intuitive de la foret :
- Zoom Fluide : Utilisation de la molette de la souris ou du pincement (pinch-to-zoom) sur mobile.
- Panoramique (Pan) : Deplacement libre de la vue en glissant la souris ou le doigt.
- Mini-carte : Un overlay en bas a droite permet de visualiser la zone affichee par rapport a l'ensemble de la grille.
- Reset de Vue : Un bouton "Reinitialiser la vue" permet de revenir instantanement au cadrage initial.

## Ameliorations UX et Visuelles
- Systeme de Particules : Lors de la selection d'un pixel, une micro-explosion de particules orange confirme l'action de l'utilisateur.
- Tooltips Dynamiques : Au survol des pixels finances, une info-bulle affiche le nom du donateur, le montant et son message.
- Densite de la Foret : Ajustement a une grille de 100x100 avec 500 pixels pre-finances pour un rendu visuel plus riche.

## Cross-Device et Responsive
- Support Tactile complet : Gestes multi-touch actives pour le zoom et le deplacement.
- Rendu Retina/HD : Adaptation automatique a la densite de pixels de l'ecran (window.devicePixelRatio).

## Fichiers impactes
- assets/js/pixel-grid.js : Migration vers le moteur Canvas (Zoom, Pan, particules, mini-carte).
- assets/js/grid-page.js : Pilotage du moteur Canvas et fonction de reinitialisation de la vue.
- assets/js/landing-page.js et admin-page.js : Ajustements de densite de la grille.
- grid.php : Instructions utilisateur et bouton de reset.
