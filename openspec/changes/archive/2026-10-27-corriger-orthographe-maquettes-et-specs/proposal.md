## Why

Les maquettes générées et plusieurs specs OpenSpec contiennent des fautes d'orthographe sur les mots français nécessitant des accents ou une cédille (`ç`).  
Ces erreurs dégradent la lisibilité, introduisent des ambiguïtés fonctionnelles sur les libellés, et doivent être corrigées avant les prochaines revues.

## What Changes

- Relire et corriger les libellés visibles de l'ensemble des maquettes statiques (`maquettes/`) pour rétablir l'orthographe française correcte (accents, cédille, apostrophes, traits d'union et accords).
- Aligner les textes d'accompagnement liés aux maquettes (catalogue et documentation de maquettes) avec les libellés corrigés.
- Effectuer une passe orthographique équivalente sur les specs OpenSpec (`openspec/specs/` et deltas de changement concernés), sans modifier le périmètre fonctionnel attendu.
- Introduire une exigence explicite de qualité rédactionnelle des libellés d'interface pour éviter la réapparition d'écritures non accentuées dans les maquettes.

## Capabilities

### New Capabilities
- Aucune.

### Modified Capabilities
- `frontend-reference-screen-flows`: ajout d'une exigence de qualité orthographique des libellés affichés dans les maquettes de référence.

## Impact

- Fichiers `maquettes/**/*.html`, `maquettes/**/*.md` et manifestes associés contenant du texte visible.
- Fichiers `openspec/specs/**/*.md` (et deltas OpenSpec liés) pour harmoniser l'orthographe française sans changer les comportements métier.
- Aucun impact API, dépendance, modèle de données ou exécution backend.
- Aucun changement dans `legacy/` (lecture seule).
