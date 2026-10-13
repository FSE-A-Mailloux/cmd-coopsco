## Why

La maquette `admin-roles-permissions` suggère que les rôles et leurs permissions sont administrables, contrairement au fonctionnement souhaité. Les trois rôles `Famille`, `Gestionnaire` et `Admin` doivent disposer de permissions fixes, sans possibilité de modifier leur définition depuis l'application.

## What Changes

- Retirer la page `admin-roles-permissions`, ses entrées dans les deux manifests et toute référence active dans les parcours de maquettes, sans créer de page de remplacement.
- Fixer le référentiel aux trois rôles `Famille`, `Gestionnaire` et `Admin` : aucune création, duplication, suppression ou modification des rôles ni de leurs permissions n'est proposée aux utilisateurs, y compris aux admins.
- Conserver le changement de rôle d'un utilisateur par un admin et son historique : affecter un rôle existant ne modifie pas les permissions de ce rôle.
- **BREAKING** : abandonner le cadrage produit prévoyant l'administration des groupes porteurs de droits et des fonctions applicatives ; aligner le PRD et la matrice de couverture legacy sur les rôles fixes.
- Maintenir les contrôles d'autorisation et le menu calculé à partir des permissions prédéfinies, sans redéfinir ici les droits métier de chaque rôle.

## Capabilities

### New Capabilities

Aucune.

### Modified Capabilities

- `authorization-and-menu-resolution` : imposer un référentiel de rôles et de permissions non administrable tout en conservant les contrôles fonctionnels et la résolution du menu.
- `frontend-reference-screen-flows` : exclure l'écran de gestion des rôles et permissions des maquettes, tout en préservant le parcours d'affectation d'un rôle existant à un utilisateur.

## Impact

La mise en œuvre concerne `maquettes\screens\administration\admin-roles-permissions.html`, `maquettes\manifest\screens.json`, `maquettes\manifest\screens.js`, la documentation des maquettes, `openspec\prd-rewrite-agnostique.md` et `docs\legacy-coverage-matrix.md`. Le change ouvert `reprendre-administration-habilitations` devra être recadré pour ne pas réintroduire des groupes ou habilitations administrables, sans perdre son objectif distinct de changement forcé de mot de passe.

Aucune dépendance nouvelle, aucune modification du dossier `legacy` et aucune implémentation backend ne sont prévues dans ce changement centré sur le cadrage et les maquettes.

## Non-goals

- Redéfinir la matrice détaillée des droits métier ou déduire des droits de la maquette retirée.
- Supprimer le changement de rôle utilisateur, son contrôle réservé aux admins ou son audit.
- Modifier les parcours de connexion, de session ou de bascule de rôle actif déjà cadrés.
