## Purpose

Definir les regles metier et techniques du changement de role utilisateur par un admin, avec controles d'habilitation stricts et tracabilite consultable.

## Requirements

### Requirement: Seuls les administrateurs MUST pouvoir modifier le role d'un utilisateur
Le systeme MUST autoriser l'operation de changement de role uniquement pour un utilisateur authentifie portant le role `Admin`.

#### Scenario: Changement de role autorise pour un admin
- **WHEN** un utilisateur authentifie avec le role `Admin` demande un changement de role sur un compte cible
- **THEN** le systeme evalue la demande et peut executer le changement selon les autres regles de validation

#### Scenario: Changement de role refuse pour un non-admin
- **WHEN** un utilisateur sans role `Admin` demande un changement de role
- **THEN** le systeme refuse l'operation avec une erreur d'autorisation explicite

### Requirement: Le role cible MUST appartenir au referentiel autorise
Le systeme MUST accepter uniquement les roles cibles `Famille`, `Gestionnaire` et `Admin` pour une operation de changement de role.

#### Scenario: Role cible valide
- **WHEN** un admin soumet un changement vers `Famille`, `Gestionnaire` ou `Admin`
- **THEN** le systeme traite la demande comme valide sur le plan du referentiel de roles

#### Scenario: Role cible hors referentiel
- **WHEN** un admin soumet un changement vers une valeur de role absente du referentiel autorise
- **THEN** le systeme rejette la demande avec une erreur de validation explicite

### Requirement: Chaque changement effectif de role MUST etre journalise avec une trace complete
Le systeme MUST enregistrer une entree de journal pour chaque changement effectif de role avec au minimum: identifiant de l'utilisateur cible, ancien role, nouveau role, identifiant de l'admin acteur et horodatage.

#### Scenario: Trace enregistree apres changement de role
- **WHEN** un admin valide un changement effectif de role sur un utilisateur
- **THEN** le systeme met a jour le role et enregistre une entree de journal contenant les informations de trace attendues

#### Scenario: Echec de journalisation
- **WHEN** le systeme ne peut pas persister la trace d'audit d'un changement de role
- **THEN** le systeme annule l'operation et ne confirme pas de changement de role

### Requirement: L'historique des changements de role MUST etre consultable ulterieurement
Le systeme MUST permettre la consultation de l'historique des changements de role, trie du plus recent au plus ancien, pour un utilisateur cible donne.

#### Scenario: Consultation de l'historique d'un utilisateur
- **WHEN** un admin consulte l'historique des changements de role d'un utilisateur cible
- **THEN** le systeme retourne les entrees de journal correspondantes triees par horodatage decroissant

#### Scenario: Consultation de l'historique par un non-admin
- **WHEN** un utilisateur sans role `Admin` tente de consulter l'historique des changements de role
- **THEN** le systeme refuse l'acces avec une erreur d'autorisation explicite

### Requirement: Le changement de rôle MUST valider l’existence du compte cible
En complément des contrôles d’autorisation de l’acteur `Admin` et du référentiel des rôles cibles, le système MUST vérifier que l’utilisateur cible existe avant tout changement de rôle. Une demande invalide MUST être refusée avec une erreur explicite, sans modifier de compte ni créer de trace de changement effectif.

#### Scenario: Utilisateur cible introuvable
- **WHEN** un acteur `Admin` demande un changement de rôle pour un utilisateur cible inexistant
- **THEN** le système refuse l’opération avec une erreur explicite, sans modifier de compte ni créer de trace de changement effectif

#### Scenario: Demande valide pour un compte existant
- **WHEN** un acteur `Admin` soumet un rôle cible autorisé pour un utilisateur cible existant
- **THEN** le système applique le changement selon les exigences existantes de journalisation complète et d’annulation en cas d’échec d’audit
