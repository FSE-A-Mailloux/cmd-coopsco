## Why

`docs/legacy-use-cases.md` cartographie 57 use-cases legacy (`UC-<DOMAINE>-<n>`), mais aucun identifiant `UC-*` n'apparaît dans `openspec/prd-rewrite-agnostique.md`, dans `openspec/specs/**` ni dans `maquettes/**`. Le seul lien existant est `docs/legacy-migration-backlog.md`, qui ne référence que 11 use-cases sur 57.

Rien ne permet aujourd'hui de distinguer un use-case **repris**, un use-case **volontairement abandonné** et un use-case **oublié**. Une revue manuelle croisant les quatre artefacts fait apparaître au moins 11 use-cases sans aucune trace (dont la gestion du référentiel fournisseurs et l'intégralité du domaine « Éditions & exports »), 6 use-cases décidés au PRD mais jamais descendus en spécification ni en maquette, et 3 divergences sémantiques où un même libellé recouvre deux comportements différents.

Sans mécanisme de contrôle, ces écarts se découvriront en recette ou en production, et chaque nouvelle spécification rédigée aggrave la dérive.

## What Changes

- Introduire une **matrice de couverture legacy** (`docs/legacy-coverage-matrix.md`) : une ligne par use-case legacy, portant une décision explicite (`REPRIS`, `ABANDONNÉ`, `À TRANCHER`) et les rattachements vers le PRD, les spécifications et les maquettes.
- Imposer que **tout use-case legacy porte une décision explicite**, et qu'un abandon soit justifié plutôt que silencieux.
- Fournir un **contrôle automatisable** de cohérence de la matrice, exécutable en local et intégrable à la CI, qui échoue sur les incohérences structurelles (use-case absent de la matrice, décision manquante, `REPRIS` sans rattachement, référence pointant un artefact inexistant).
- Formaliser la **chaîne de traçabilité à quatre niveaux** `use-case legacy → PRD (FR) → spécification OpenSpec → maquette`, en complément de la section 13 du PRD qui ne couvre aujourd'hui que le lien spécification → FR.
- Ouvrir une **revue d'arbitrage** sur les écarts identifiés, afin de convertir les `À TRANCHER` en décisions et de tracer les divergences sémantiques comme écarts assumés.

## Capabilities

### New Capabilities
- `legacy-coverage-traceability` : gouvernance et vérification de la couverture des use-cases legacy par les artefacts de refonte.

### Modified Capabilities
- `legacy-refactor-context-curation` : la traçabilité, aujourd'hui limitée au sens « tâche de refonte → fichiers legacy source », est étendue au sens inverse « use-case legacy → artefacts cibles », afin de garantir la complétude et non plus seulement la justification.

## Impact

- **Documentation**: création de `docs/legacy-coverage-matrix.md`; `docs/legacy-use-cases.md` devient la source d'entrée du contrôle; `docs/legacy-migration-backlog.md` s'aligne sur les décisions de la matrice.
- **PRD**: `openspec/prd-rewrite-agnostique.md` est susceptible d'évoluer si la revue d'arbitrage confirme des manques (référentiel fournisseurs, annulation de règlement, éditions et exports, consultation des historiques d'audit et d'anomalies).
- **Spécifications**: les use-cases décidés au PRD mais non descendus devront faire l'objet de changements OpenSpec ultérieurs; ce changement les identifie, il ne les rédige pas.
- **Outillage**: ajout d'un script de vérification de la matrice, sans dépendance externe, dans la continuité de la commande de contrôle déjà présente dans `docs/legacy-use-cases.md`.
- **Non concerné**: aucune modification du code legacy, aucune implémentation de fonctionnalité métier.
