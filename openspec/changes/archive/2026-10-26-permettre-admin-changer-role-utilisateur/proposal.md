## Why

Le changement de rôle utilisateur est déjà décrit dans `user-role-administration-and-audit` et représenté dans la maquette `admin-utilisateurs.html`, à la suite du changement archivé `2026-10-12-cadrer-changement-role-utilisateur` (commit `c3e2b81`).
Ce changement réconcilie les exigences initiales avec cet existant et complète uniquement les écarts documentaires, sans développement applicatif.

## What Changes

- Réutiliser les règles existantes : acteur `Admin`, rôles cibles `Famille`, `Gestionnaire` et `Admin`, journalisation atomique et historique consultable.
- Compléter la validation de l’existence de l’utilisateur cible dans `user-role-administration-and-audit`, sans dupliquer ces règles dans le cycle de vie des comptes.
- Spécifier la prise en compte des nouvelles permissions dès le premier contrôle d’accès suivant le changement et la cohérence du menu à la reconnexion.
- Compléter la description du parcours visuel existant : succès avec ancien et nouveau rôle, erreur de validation et refus d’autorisation.
- Conserver les rôles et permissions prédéfinis : affecter un rôle à un utilisateur ne permet pas d’en modifier la définition.

## Capabilities

### New Capabilities
- *(aucune)*

### Modified Capabilities
- `user-role-administration-and-audit`: complément de validation du compte cible, en réutilisant les exigences existantes d’autorisation, de rôle cible et d’audit.
- `authorization-and-menu-resolution`: ajout des exigences de cohérence des permissions et de navigation après changement de rôle.
- `frontend-reference-screen-flows`: complément des états de retour du changement de rôle intégré au listing utilisateurs existant, sans nouvel écran.

## Impact

- Artefacts OpenSpec du changement (`proposal.md`, `specs/**/spec.md`, `design.md`, `tasks.md`).
- Specs principales des trois capacités ci-dessus après synchronisation des deltas.
- Description des états du parcours visuel existant, sans modification du code de la maquette.
- Aucun changement de code applicatif attendu dans ce change.
