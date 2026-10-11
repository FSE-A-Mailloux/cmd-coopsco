## Why

Le besoin est de compléter la documentation cible (spécifications et maquettes), sans réaliser de développement applicatif.  
Le changement de rôle administrateur doit être cadré de façon homogène entre règles métier et parcours visuel pour éviter les divergences d’interprétation.

## What Changes

- Compléter les specs techniques sur le changement de rôle d’un utilisateur par un administrateur habilité.
- Formaliser les validations et résultats attendus (autorisation, rôle cible, utilisateur cible, traçabilité).
- Compléter les maquettes de référence du parcours d’administration des rôles (écrans, états, retours utilisateur).
- Aligner les exigences d’autorisation/menu avec les représentations visuelles associées.

## Capabilities

### New Capabilities
- *(aucune)*

### Modified Capabilities
- `user-account-lifecycle-management`: ajout des exigences de changement de rôle d’un utilisateur par un administrateur, avec validations et traçabilité.
- `authorization-and-menu-resolution`: ajout des exigences de cohérence des permissions et de navigation après changement de rôle.
- `frontend-reference-screen-flows`: ajout des exigences de maquettes pour le parcours d’administration des rôles et ses états de retour.

## Impact

- Artefacts OpenSpec du changement (`proposal.md`, `specs/**/spec.md`, `design.md`, `tasks.md`).
- Référentiel de maquettes et descriptions des parcours visuels administrateur.
- Aucun changement de code applicatif attendu dans ce change.
