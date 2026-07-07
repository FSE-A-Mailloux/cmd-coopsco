## MODIFIED Requirements

### Requirement: User creation MUST enforce uniqueness and validation preconditions
La création de compte MUST garantir l’unicité des identifiants requis et appliquer les contrôles de validation prévus par la politique produit.

#### Scenario: Duplicate account on creation
- **WHEN** un compte est soumis avec un identifiant déjà existant
- **THEN** le système rejette la création avec une erreur fonctionnelle explicite

#### Scenario: Public self-registration with anti-abuse control
- **WHEN** une inscription publique est demandée
- **THEN** le système applique les contrôles anti-abus configurés avant création du compte

### Requirement: User lifecycle contexts MUST support activation and credential recovery
Le cycle de vie compte MUST couvrir activation, récupération d’accès, réinitialisation de mot de passe et changement de mot de passe authentifié.

#### Scenario: Password reset by recovery link
- **WHEN** un parcours de récupération valide est complété
- **THEN** le mot de passe est mis à jour et l’ancien secret devient invalide

#### Scenario: Invalid recovery or activation code
- **WHEN** un code de récupération/activation invalide est utilisé
- **THEN** le système refuse l’opération sans modification d’état

### Requirement: User deletion MUST enforce privilege checks and anonymization
La suppression de compte MUST respecter les règles de privilèges et MUST anonymiser les données historiques selon la politique réglementaire applicable.

#### Scenario: Unauthorized high-privilege deletion
- **WHEN** un utilisateur non autorisé tente de supprimer un compte protégé
- **THEN** le système refuse l’opération avec une erreur d’autorisation

#### Scenario: Successful deletion
- **WHEN** une suppression autorisée est exécutée
- **THEN** les données personnelles historisées sont anonymisées selon la politique définie

