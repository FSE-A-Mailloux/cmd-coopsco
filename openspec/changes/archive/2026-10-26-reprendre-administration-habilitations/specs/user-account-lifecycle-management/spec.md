## ADDED Requirements

### Requirement: Un administrateur MUST pouvoir imposer un changement de mot de passe à la prochaine connexion
Le système MUST permettre à un utilisateur ayant le rôle `Admin` d'imposer à un compte utilisateur un changement de mot de passe à sa prochaine connexion. Cette action MUST être autorisée côté serveur et MUST NOT révéler ni modifier le mot de passe du compte cible.

#### Scenario: Imposition par un administrateur
- **WHEN** un `Admin` demande d'imposer un changement de mot de passe à un compte existant
- **THEN** le système enregistre l'obligation pour la prochaine connexion de ce compte et confirme le résultat à l'administrateur

#### Scenario: Demande par un utilisateur non administrateur
- **WHEN** un utilisateur qui n'a pas le rôle `Admin` demande d'imposer un changement de mot de passe
- **THEN** le système refuse l'opération et ne modifie pas le compte cible

#### Scenario: Compte cible inexistant
- **WHEN** un `Admin` demande l'imposition pour un compte qui n'existe pas
- **THEN** le système retourne une erreur explicite et ne modifie aucun compte

### Requirement: Le compte concerné MUST changer son mot de passe avant de poursuivre après sa prochaine connexion
Lors de la prochaine connexion réussie d'un compte soumis à cette obligation, le système MUST exiger le changement de mot de passe avant de permettre l'accès aux autres fonctionnalités authentifiées. L'obligation MUST rester active tant qu'un changement valide n'est pas terminé.

#### Scenario: Connexion avec changement obligatoire
- **WHEN** un compte soumis à l'obligation se connecte avec des identifiants valides
- **THEN** le système dirige le titulaire vers le changement de mot de passe et bloque l'accès aux autres fonctionnalités authentifiées jusqu'à son achèvement

#### Scenario: Changement de mot de passe réussi
- **WHEN** le titulaire soumet un nouveau mot de passe valide dans le parcours obligatoire
- **THEN** le système met à jour le mot de passe, retire l'obligation et permet au titulaire de poursuivre sa session authentifiée

#### Scenario: Changement invalide ou abandonné
- **WHEN** le nouveau mot de passe est invalide ou que le titulaire abandonne le parcours obligatoire
- **THEN** le système conserve l'obligation et n'autorise pas l'accès aux autres fonctionnalités authentifiées
