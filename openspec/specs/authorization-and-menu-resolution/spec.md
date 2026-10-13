## Purpose

Définir les règles d’autorisation et le comportement de résolution de menu tenant compte des capacités.

## Requirements

### Requirement: Les actions accréditées MUST exiger une autorisation au niveau fonctionnel
Toute action sensible MUST être exécutée uniquement si l’utilisateur dispose de la permission fonctionnelle requise.

#### Scenario: Action accréditée autorisée
- **WHEN** un utilisateur possède la permission nécessaire pour une action sensible
- **THEN** le système autorise l’exécution de cette action

#### Scenario: Action accréditée non autorisée
- **WHEN** un utilisateur ne possède pas la permission nécessaire
- **THEN** le système refuse l’action avec une erreur de sécurité explicite

### Requirement: L’accès composant MUST être validé avant la résolution du menu
L’accès à un composant métier MUST être validé avant d’exposer son contenu ou sa navigation associée.

#### Scenario: Composant non autorisé
- **WHEN** un utilisateur tente d’accéder à un composant sans permission
- **THEN** le système refuse l’accès et ne retourne pas de contenu composant exploitable

### Requirement: Le menu retourné MUST refléter les permissions
Le menu affiché MUST être calculé selon les permissions effectives de l'utilisateur issues des rôles prédéfinis et ne présenter que les destinations autorisées. Il MUST NOT exposer de destination permettant d'administrer les rôles ou leurs permissions.

#### Scenario: Construction du menu
- **WHEN** le système calcule le menu d'un utilisateur connecté
- **THEN** il retourne une structure de navigation ordonnée limitée aux entrées autorisées

#### Scenario: Absence de gestion des permissions dans le menu admin
- **WHEN** le système calcule le menu d'un utilisateur `Admin`
- **THEN** aucune destination de création, duplication ou modification des rôles et de leurs permissions n'est proposée

### Requirement: Le référentiel des rôles et de leurs permissions MUST être fixe
Le système MUST limiter les rôles aux valeurs `Famille`, `Gestionnaire` et `Admin`, dont les permissions sont prédéfinies par l'application. Le système MUST NOT permettre à un utilisateur, y compris un `Admin`, de créer, dupliquer, renommer ou supprimer un rôle, ni de modifier ses permissions ou les fonctions applicatives qui les définissent. Les groupes administrables MUST NOT constituer un moyen alternatif d'accorder ou de modifier des droits.

#### Scenario: Consultation de l'administration par un admin
- **WHEN** un utilisateur `Admin` consulte les fonctionnalités d'administration
- **THEN** aucune fonctionnalité de gestion de la définition des rôles, des groupes porteurs de droits ou des permissions n'est disponible

#### Scenario: Tentative de modification des permissions hors interface
- **WHEN** un utilisateur tente de modifier la définition d'un rôle ou ses permissions par une requête directe
- **THEN** le système ne réalise aucune modification et retourne un refus explicite

#### Scenario: Permissions identiques pour un même rôle
- **WHEN** deux utilisateurs exercent le même rôle dans le même contexte métier
- **THEN** leurs permissions fonctionnelles de rôle proviennent de la même définition prédéfinie, sans personnalisation administrative
- **AND** les restrictions métier existantes, notamment l'accès d'une famille à ses propres commandes, restent applicables

### Requirement: L'affectation d'un rôle MUST rester distincte de sa définition
Le système MUST conserver la possibilité pour un `Admin` d'affecter un rôle existant à un utilisateur, selon les règles d'autorisation, de validation et d'audit de `user-role-administration-and-audit`. Cette affectation MUST NOT modifier la définition des permissions du rôle ni celle des autres rôles.

#### Scenario: Affectation d'un rôle existant
- **WHEN** un admin change le rôle d'un utilisateur de `Famille` vers `Gestionnaire`
- **THEN** le rôle est changé et journalisé selon les règles existantes
- **AND** les permissions prédéfinies de `Famille`, `Gestionnaire` et `Admin` restent inchangées
