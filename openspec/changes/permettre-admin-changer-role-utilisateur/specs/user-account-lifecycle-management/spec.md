## ADDED Requirements

### Requirement: Le changement de rôle MUST être réservé aux administrateurs habilités
Le système MUST autoriser la modification du rôle d’un utilisateur uniquement si l’acteur courant dispose d’une habilitation d’administration des rôles.

#### Scenario: Administrateur habilité
- **WHEN** un administrateur habilité demande le changement de rôle d’un utilisateur existant
- **THEN** le système accepte la demande de mise à jour de rôle

#### Scenario: Utilisateur non habilité
- **WHEN** un utilisateur sans habilitation d’administration des rôles demande un changement de rôle
- **THEN** le système refuse l’opération avec une erreur d’autorisation explicite

### Requirement: Le changement de rôle MUST valider la demande et tracer l’opération
Le système MUST vérifier que l’utilisateur cible existe et que le rôle cible appartient au référentiel autorisé. Pour chaque changement accepté, il MUST consigner l’identifiant de l’acteur, l’identifiant de l’utilisateur cible, l’ancien rôle, le nouveau rôle et l’horodatage de l’action.

#### Scenario: Rôle cible invalide
- **WHEN** un administrateur habilité soumet un rôle cible absent du référentiel autorisé
- **THEN** le système refuse la modification avec une erreur fonctionnelle explicite

#### Scenario: Utilisateur cible introuvable
- **WHEN** un administrateur habilité demande un changement de rôle pour un utilisateur inexistant
- **THEN** le système refuse l’opération sans modifier de compte

#### Scenario: Changement de rôle validé
- **WHEN** un administrateur habilité soumet une demande valide pour un utilisateur existant
- **THEN** le système met à jour le rôle et enregistre une trace d’audit complète de l’opération
