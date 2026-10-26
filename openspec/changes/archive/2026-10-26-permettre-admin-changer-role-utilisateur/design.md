## Context

Ce changement est exclusivement documentaire. Le commit `c3e2b81` et le dossier archivé `2026-10-12-cadrer-changement-role-utilisateur` ont déjà introduit `user-role-administration-and-audit`, le parcours frontend et la maquette `maquettes/screens/administration/admin-utilisateurs.html`.
Les specs principales et la maquette constituent les preuves de couverture documentaire ; les cases cochées de l’ancien dossier ne prouvent pas une réalisation serveur.
Le périmètre réconcilié couvre `user-role-administration-and-audit`, `authorization-and-menu-resolution` et `frontend-reference-screen-flows`.

## Goals / Non-Goals

**Goals:**
- Définir des exigences testables sur le changement de rôle administrateur dans les specs techniques.
- Décrire les écrans de référence et les états visuels du parcours de changement de rôle.
- Garantir l’alignement entre règles fonctionnelles (specs) et rendu attendu (maquettes).
- Synchroniser les compléments dans les specs principales, puis archiver un dossier sans doublons, sans prétendre livrer une implémentation applicative.

**Non-Goals:**
- Développer ou modifier le code applicatif backend/frontend.
- Introduire de nouveaux mécanismes techniques d’authentification/session.
- Étendre le périmètre à d’autres domaines métiers non liés aux habilitations.

## Decisions

### 1) Réutiliser la capacité métier existante
L’acteur est un utilisateur authentifié de rôle `Admin`. La cible est le compte dont le rôle est modifié. Le rôle cible est une valeur du référentiel fixe `Famille`, `Gestionnaire`, `Admin` ; les permissions sont les droits prédéfinis associés à ce rôle.

Les exigences existantes de `user-role-administration-and-audit` couvrent déjà l’autorisation, le référentiel des rôles, la trace complète, l’annulation en cas d’échec d’audit et l’historique. Elles restent inchangées. Seule la validation explicite de l’existence du compte cible est ajoutée.
Le delta initial de `user-account-lifecycle-management` est remplacé par ce complément dans la capacité dédiée, sans suppression d’exigence dans les specs principales.

### 2) Expliciter les moments de prise en compte des droits
Après un changement validé, le premier contrôle d’accès suivant utilise les nouvelles permissions, y compris pour une session déjà ouverte. Une reconnexion ne doit pas être nécessaire pour retirer ou accorder un droit lors de ce contrôle.
Le menu retourné à la reconnexion reflète les nouvelles permissions. Ce cadrage reprend le scénario initial sans imposer de rafraîchissement visuel automatique d’une page déjà ouverte.
Le choix du mécanisme technique reste hors périmètre ; aucune modification de la définition des rôles, des sessions ou des jetons n’est prescrite.

### 3) Compléter le parcours existant plutôt que créer un écran
Le listing utilisateurs reste le point d’entrée avec rôle courant, sélection d’un rôle autorisé, confirmation et accès à l’historique. Son exigence de résultat est modifiée sans retirer ses scénarios existants.
La description des états est complétée dans `frontend-reference-screen-flows` ; le code de la maquette n’est pas modifié.

| État visuel | Règle métier de référence | Complément documentaire |
| --- | --- | --- |
| Succès avec ancien et nouveau rôle | Trace complète dans `user-role-administration-and-audit` | Rappel explicite des deux rôles après confirmation |
| Erreur de rôle cible | Référentiel autorisé dans `user-role-administration-and-audit` | Message explicite, rôle courant inchangé |
| Compte cible introuvable | Validation du compte cible ajoutée dans `user-role-administration-and-audit` | Aucun succès ni modification affichée |
| Refus d’autorisation | Changement réservé à `Admin` dans `user-role-administration-and-audit` | Refus explicite, aucune confirmation de modification |
| Perte ou gain de droits | Nouveaux contrôles d’accès et menu dans `authorization-and-menu-resolution` | Scénarios de restriction et d’autorisation, menu à la reconnexion |

L’affectation d’un rôle reste distincte de sa définition, conformément aux specs principales. Aucun écran de gestion des permissions n’est réintroduit.

## Risks / Trade-offs

- **[Risque]** Glissement vers des détails d’implémentation dans les artefacts.  
  **→ Mitigation**: relire chaque section contre le critère « observable et testable ».
- **[Risque]** Maquettes et specs évoluent de manière asynchrone.  
  **→ Mitigation**: vérifier explicitement les correspondances scénario métier ↔ état visuel.
- **[Trade-off]** Un cadrage documentaire précis demande plus d’effort initial mais réduit les ambiguïtés en phase de réalisation.

## Migration Plan

1. Rapprocher les exigences initiales des specs principales et de la maquette existante.
2. Conserver uniquement les compléments dans les trois deltas, en préservant les scénarios existants.
3. Vérifier la traçabilité ci-dessus et valider le changement documentaire.
4. Synchroniser les deltas dans les specs principales, vérifier leur correspondance, puis archiver.

**Rollback**
- Revenir aux versions précédentes des artefacts OpenSpec si le cadrage est rejeté en revue.
