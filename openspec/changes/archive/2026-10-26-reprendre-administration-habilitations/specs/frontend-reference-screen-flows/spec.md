## ADDED Requirements

### Requirement: Le listing utilisateurs MUST permettre à un admin d'imposer un changement de mot de passe
La maquette d'administration des utilisateurs MUST représenter une action réservée au rôle `Admin` permettant d'imposer à un compte le changement de mot de passe à sa prochaine connexion. Le parcours MUST distinguer cette action d'une réinitialisation de mot de passe et MUST présenter une confirmation ou un retour explicite de résultat.

#### Scenario: Action disponible pour un admin
- **WHEN** un `Admin` consulte le listing utilisateurs
- **THEN** chaque compte présente une action permettant de demander le changement de mot de passe à sa prochaine connexion

#### Scenario: Confirmation de l'action
- **WHEN** un `Admin` déclenche l'action pour un compte
- **THEN** la maquette présente l'identité du compte ciblé et demande une confirmation avant d'imposer le changement

#### Scenario: Retour après confirmation
- **WHEN** l'administrateur confirme l'action
- **THEN** la maquette représente un retour de succès ou un message d'erreur explicite sans afficher ni demander le mot de passe du compte

#### Scenario: Action masquée pour un non-admin
- **WHEN** un utilisateur sans rôle `Admin` consulte le listing utilisateurs
- **THEN** la maquette ne présente pas l'action d'imposition de changement de mot de passe
