## ADDED Requirements

### Requirement: Le changement de rôle MUST valider l’existence du compte cible
En complément des contrôles d’autorisation de l’acteur `Admin` et du référentiel des rôles cibles, le système MUST vérifier que l’utilisateur cible existe avant tout changement de rôle. Une demande invalide MUST être refusée avec une erreur explicite, sans modifier de compte ni créer de trace de changement effectif.

#### Scenario: Utilisateur cible introuvable
- **WHEN** un acteur `Admin` demande un changement de rôle pour un utilisateur cible inexistant
- **THEN** le système refuse l’opération avec une erreur explicite, sans modifier de compte ni créer de trace de changement effectif

#### Scenario: Demande valide pour un compte existant
- **WHEN** un acteur `Admin` soumet un rôle cible autorisé pour un utilisateur cible existant
- **THEN** le système applique le changement selon les exigences existantes de journalisation complète et d’annulation en cas d’échec d’audit
