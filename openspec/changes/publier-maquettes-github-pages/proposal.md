## Why

Les maquettes statiques sont aujourd'hui consultables localement, ce qui limite leur partage avec les parties prenantes non techniques.
Publier ce référentiel via GitHub Pages permet de disposer d'une URL stable pour les ateliers, les validations et les retours asynchrones.

## What Changes

- Ajouter un workflow GitHub Actions dédié au déploiement du dossier `maquettes/` sur GitHub Pages.
- Définir un déclenchement automatique du déploiement lors des changements sur les maquettes et un déclenchement manuel.
- Formaliser la publication comme capacité de consultation distante du workbench maquettes.
- Conserver une publication purement statique, sans backend ni build applicatif supplémentaire.

## Capabilities

### New Capabilities
- `github-pages-mockup-publication`: publication continue du référentiel de maquettes statiques via GitHub Pages.

### Modified Capabilities
- `frontend-reference-screen-flows`: ajout de la contrainte de consultation distante des maquettes via une URL GitHub Pages maintenue à jour.

## Impact

- Ajout du workflow `.github/workflows/static.yml`.
- Exposition publique (ou interne selon la visibilité du dépôt) du contenu statique `maquettes/`.
- Aucun impact sur les API backend, le modèle de données, ni les parcours métier dynamiques.
