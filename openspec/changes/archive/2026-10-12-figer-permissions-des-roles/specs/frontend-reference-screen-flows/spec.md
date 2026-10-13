## ADDED Requirements

### Requirement: Le référentiel de maquettes MUST exclure l'administration des rôles et permissions
Le référentiel de maquettes MUST NOT contenir l'écran `admin-roles-permissions`, une entrée de navigation vers cet écran ou un écran de remplacement dédié à la gestion des rôles et permissions. Les parcours de maquette MUST NOT proposer de création, duplication ou modification de la définition des rôles ou de leurs permissions.

#### Scenario: Consultation du catalogue de maquettes
- **WHEN** un utilisateur consulte le catalogue de maquettes en mode local ou à distance
- **THEN** l'écran `admin-roles-permissions` n'apparaît ni dans la navigation ni dans les aperçus disponibles

#### Scenario: Accès à l'ancienne page
- **WHEN** un utilisateur tente d'ouvrir directement l'ancienne page `admin-roles-permissions`
- **THEN** cette page n'est plus fournie par le référentiel de maquettes

#### Scenario: Préservation de l'administration des utilisateurs
- **WHEN** un admin consulte le listing utilisateurs après le retrait de l'écran
- **THEN** le changement de rôle parmi `Famille`, `Gestionnaire` et `Admin` et la consultation de son historique restent disponibles
- **AND** ces actions ne permettent pas de modifier les permissions des rôles
