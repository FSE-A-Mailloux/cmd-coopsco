## Why

La refonte s'appuie sur du code legacy volumineux et hétérogène, ce qui dilue le contexte utile fourni à GitHub Copilot pendant l'analyse et la migration. Définir un périmètre legacy métier explicite, avec exclusions stables, réduit le bruit et accélère les décisions de refonte.

## What Changes

- Définir un corpus legacy de référence pour la refonte, centré sur le code métier réellement exploitable.
- Formaliser une politique d'exclusion pour les dépendances tierces et assets non pertinents (`ws/vendor`, `libs`, assets).
- Introduire une règle de traçabilité migration: chaque tâche de refonte référence les fichiers legacy source.
- Cadrer la gouvernance d'actualisation du corpus (ajouts, retraits, validation des exclusions).

## Capabilities

### New Capabilities
- `legacy-refactor-context-curation`: gouvernance du périmètre legacy de référence utilisé pendant la refonte et les travaux assistés par Copilot.

### Modified Capabilities
- Aucun.

## Impact

- **Code concerné**: `legacy/app/**`, `legacy/ws/**` (hors `legacy/ws/vendor/**`), exclusion des bibliothèques et assets legacy (`legacy/libs/**`, `legacy/imgs/**`, `legacy/assets/**` si présent).
- **Outillage et conventions**: règles de filtrage du corpus, documentation de migration, discipline de traçabilité des sources legacy.
- **Organisation de la refonte**: lotissement des tickets par flux métier avec références explicites vers le corpus legacy retenu.
