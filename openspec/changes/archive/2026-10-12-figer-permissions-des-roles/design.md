## Context

Voir `proposal.md` pour la motivation. Les maquettes sont statiques et leur catalogue est dupliqué dans `maquettes\manifest\screens.json` et `maquettes\manifest\screens.js`. Le workbench privilégie le JavaScript pour une ouverture locale sans serveur.

L'écran à retirer contient une colonne `Lecture seule`, des actions de duplication de rôle et d'enregistrement de matrice : il n'est pas une source fiable des droits cibles. Le listing `admin-utilisateurs` utilise déjà uniquement `Famille`, `Gestionnaire` et `Admin`, avec un historique de changement de rôle.

Le PRD conserve des exigences de gestion de groupes et de fonctions (`FR-025` à `FR-027`), reprises dans la matrice legacy. Le change ouvert `reprendre-administration-habilitations` ne contient qu'un objectif dans `.openspec.yaml` ; cet objectif mélange groupes, affectations et changement forcé de mot de passe. Le design est nécessaire pour traiter ces contradictions transverses sans retirer des fonctionnalités indépendantes.

## Goals / Non-Goals

**Goals:**
- Retirer la page source et synchroniser les deux catalogues pour éviter un écran orphelin ou une divergence entre consultations locale et distante.
- Rendre cohérents les contrats d'autorisation, le PRD, la couverture legacy et le cadrage ouvert.
- Préserver intégralement la maquette de changement de rôle utilisateur et les règles d'audit associées.

**Non-Goals:**
- Introduire une matrice en lecture seule ou un nouvel écran de présentation des droits.
- Implémenter un moteur d'autorisation, une API ou une migration des données de comptes.
- Réinterpréter les droits détaillés des rôles à partir des exemples de l'écran supprimé.
- Modifier `legacy` ou réécrire les archives OpenSpec.

## Decisions

### 1. Supprimer la page plutôt que désactiver ses boutons

Supprimer `maquettes\screens\administration\admin-roles-permissions.html` et ses entrées des deux manifests, puis retirer les liens actifs éventuels. Cela supprime aussi l'accès direct au fichier livré. Garder la page masquée ou convertir la matrice en lecture seule serait incompatible avec le retrait demandé et conserverait une présentation ambiguë.

Le README des maquettes indiquera que les trois rôles sont fixes et que seul leur rattachement aux utilisateurs est administrable. Aucun changement du mécanisme de navigation ou de publication n'est nécessaire.

### 2. Décrire un invariant produit, sans inventer de droits métier

Les deltas complètent `authorization-and-menu-resolution` et `frontend-reference-screen-flows`. Les contrôles d'accès fonctionnels et d'accès aux composants restent en vigueur ; les permissions proviennent désormais explicitement de définitions non administrables. Le menu ne propose plus de gestion de ces définitions.

`user-role-administration-and-audit` reste inchangé : l'affectation d'un rôle existant et sa trace ne constituent pas une édition de permissions. Le refus d'une requête de modification de permissions est un contrat de la cible, pas une demande d'ajouter une API au projet de maquettes.

### 3. Aligner le cadrage actif et conserver la traçabilité des arbitrages

Dans le PRD, reformuler `FR-020` pour les trois rôles prédéfinis, `FR-025` pour le référentiel fixe non administrable, `FR-026` pour l'affectation d'un rôle existant par un admin et `FR-027` pour l'absence d'administration des permissions/fonctions. Conserver les identifiants pour éviter de rompre les rattachements documentaires. Actualiser aussi les résumés et arbitrages qui promettent la gestion des permissions ou des groupes.

Dans la matrice, classer `UC-ADM-3` (gestion de groupes), `UC-ADM-5` (gestion de fonctions) et `UC-ADM-6` (gestion des autorisations par groupe) comme abandonnés avec justification des rôles fixes, sans référence à la page supprimée. Conserver `UC-ADM-4` comme reprise adaptée par l'affectation d'un rôle existant et le rattacher au listing utilisateurs ainsi qu'aux contrats correspondants. Préserver `UC-ADM-2` (changement forcé de mot de passe).

Recadrer l'objectif et les références actives de `reprendre-administration-habilitations` pour retirer la gestion de groupes et l'affectation legacy par groupes ; indiquer que l'affectation cible de rôles est déjà cadrée et garder l'objectif indépendant de mot de passe forcé. Ne pas archiver ce change comme terminé ni effacer son objectif restant.

L'alternative consistant à ne retirer que la maquette laisserait des exigences et un change ouvert susceptibles de réintroduire l'administration des permissions.

## Risks / Trade-offs

- [Divergence des manifests] → Comparer leurs contenus complets après suppression ; vérifier les sources et la navigation de tous les écrans conservés.
- [Confusion entre rôle fixe et utilisateur au rôle figé] → Vérifier que le listing utilisateurs, le choix des trois rôles et l'historique sont inchangés.
- [Droits métier déduits d'une ancienne matrice incohérente] → Ne pas recopier la matrice supprimée ; conserver les exigences métier existantes.
- [Réintroduction via le cadrage legacy] → Aligner PRD, matrice et objectif du change ouvert dans le même lot.
- [Ancien lien public] → La page supprimée devient indisponible après republication ; aucun écran de remplacement ni redirection n'est ajouté.

## Migration Plan

1. Retirer la source de l'écran et synchroniser les deux manifests.
2. Aligner la documentation et le change ouvert concerné ; les specs principales seront synchronisées selon le workflow OpenSpec.
3. Vérifier l'absence de références actives à la page, l'équivalence des manifests, l'existence des autres sources et les scénarios de non-régression du listing utilisateurs.
4. Exécuter `scripts\check-legacy-coverage.ps1` et la validation OpenSpec ; republier les maquettes avec le workflow existant.

En cas de retour arrière explicitement décidé, restaurer ce lot cohérent par une modification versionnée ciblée, sans toucher aux changements indépendants. Aucune migration de données n'est nécessaire.
