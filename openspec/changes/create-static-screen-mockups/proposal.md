## Why

La réécriture complète de l’application nécessite un cadrage visuel partagé avant tout développement fonctionnel.  
Des maquettes écrans statiques permettent de clarifier les parcours, réduire les ambiguïtés métier et disposer d’un référentiel UX de départ.

## What Changes

- Créer un espace de maquettes statiques dédié, séparé du code applicatif principal.
- Fournir une application de prévisualisation des maquettes avec navigation gauche (liste des écrans) et aperçu à droite.
- Définir un style visuel neutre, lisible et non rebutant pour l’ensemble des écrans de référence.
- Préparer un premier lot d’écrans couvrant les parcours principaux issus des spécifications OpenSpec.

## Capabilities

### New Capabilities
- `static-screen-mockup-workbench`: espace dédié de maquettes statiques avec liste des écrans, sélection, aperçu et base de style visuel unifiée.

### Modified Capabilities
- `frontend-shell-and-dynamic-components`: aligner le shell cible avec le référentiel visuel produit par les maquettes statiques (zones principales et principes de navigation).

## Impact

- Ajout d’un dossier dédié pour les maquettes (proposé: `maquettes/`) avec son propre point d’entrée.
- Ajout d’une structure de données/manifest des écrans à prévisualiser.
- Alignement des futurs développements frontend sur le référentiel de maquettes validé.
- Aucun changement d’API backend à ce stade (travail purement statique et exploratoire côté interface).
