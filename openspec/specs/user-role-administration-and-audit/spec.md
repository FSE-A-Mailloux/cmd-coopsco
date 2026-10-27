## Purpose

Définir les règles métier et techniques du changement de rôle utilisateur par un admin, avec contrôles d'habilitation stricts et traçabilité consultable.

## Requirements

### Requirement: Seuls les administrateurs MUST pouvoir modifier le rôle d'un utilisateur
Le système MUST autoriser l'opération de changement de rôle uniquement pour un utilisateur authentifié portant le rôle `Admin`.

#### Scenario: Changement de rôle autorisé pour un admin
- **WHEN** un utilisateur authentifié avec le rôle `Admin` demande un changement de rôle sur un compte cible
- **THEN** le système évalue la demande et peut exécuter le changement selon les autres règles de validation

#### Scenario: Changement de rôle refusé pour un non-admin
- **WHEN** un utilisateur sans rôle `Admin` demande un changement de rôle
- **THEN** le système refuse l'opération avec une erreur d'autorisation explicite

### Requirement: Le rôle cible MUST appartenir au référentiel autorisé
Le système MUST accepter uniquement les rôles cibles `Famille`, `Gestionnaire` et `Admin` pour une opération de changement de rôle.

#### Scenario: Rôle cible valide
- **WHEN** un admin soumet un changement vers `Famille`, `Gestionnaire` ou `Admin`
- **THEN** le système traite la demande comme valide sur le plan du référentiel de rôles

#### Scenario: Rôle cible hors référentiel
- **WHEN** un admin soumet un changement vers une valeur de rôle absente du référentiel autorisé
- **THEN** le système rejette la demande avec une erreur de validation explicite

### Requirement: Chaque changement effectif de rôle MUST être journalisé avec une trace complète
Le système MUST enregistrer une entrée de journal pour chaque changement effectif de rôle avec au minimum: identifiant de l'utilisateur cible, ancien rôle, nouveau rôle, identifiant de l'admin acteur et horodatage.

#### Scenario: Trace enregistrée après changement de rôle
- **WHEN** un admin valide un changement effectif de rôle sur un utilisateur
- **THEN** le système met à jour le rôle et enregistre une entrée de journal contenant les informations de trace attendues

#### Scenario: Échec de journalisation
- **WHEN** le système ne peut pas persister la trace d'audit d'un changement de rôle
- **THEN** le système annule l’opération et ne confirme pas de changement de rôle

### Requirement: L'historique des changements de rôle MUST être consultable ultérieurement
Le système MUST permettre la consultation de l'historique des changements de rôle, trié du plus récent au plus ancien, pour un utilisateur cible donné.

#### Scenario: Consultation de l'historique d'un utilisateur
- **WHEN** un admin consulte l'historique des changements de rôle d'un utilisateur cible
- **THEN** le système retourne les entrées de journal correspondantes triées par horodatage décroissant

#### Scenario: Consultation de l'historique par un non-admin
- **WHEN** un utilisateur sans rôle `Admin` tente de consulter l'historique des changements de rôle
- **THEN** le système refuse l'accès avec une erreur d'autorisation explicite

### Requirement: Le changement de rôle MUST valider l’existence du compte cible
En complément des contrôles d’autorisation de l’acteur `Admin` et du référentiel des rôles cibles, le système MUST vérifier que l’utilisateur cible existe avant tout changement de rôle. Une demande invalide MUST être refusée avec une erreur explicite, sans modifier de compte ni créer de trace de changement effectif.

#### Scenario: Utilisateur cible introuvable
- **WHEN** un acteur `Admin` demande un changement de rôle pour un utilisateur cible inexistant
- **THEN** le système refuse l’opération avec une erreur explicite, sans modifier de compte ni créer de trace de changement effectif

#### Scenario: Demande valide pour un compte existant
- **WHEN** un acteur `Admin` soumet un rôle cible autorisé pour un utilisateur cible existant
- **THEN** le système applique le changement selon les exigences existantes de journalisation complète et d’annulation en cas d’échec d’audit
