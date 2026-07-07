## MODIFIED Requirements

### Requirement: Authentication MUST issue a new session token on successful login
Une authentification réussie MUST ouvrir une session active et retourner un contexte utilisateur exploitable (identité, rôle actif, permissions calculées).

#### Scenario: Successful credential login
- **WHEN** un utilisateur fournit des identifiants valides
- **THEN** le système ouvre une session et retourne le contexte d’usage nécessaire

#### Scenario: Invalid credential login
- **WHEN** un utilisateur fournit des identifiants invalides
- **THEN** le système rejette la connexion avec une erreur fonctionnelle explicite

### Requirement: Activation links MUST support account validation flow
Le cycle de compte MUST permettre l’activation sécurisée d’un compte non activé via un mécanisme de preuve contrôlé.

#### Scenario: Activation by link code
- **WHEN** un code/lien d’activation valide est utilisé
- **THEN** le compte est marqué comme activé et le parcours de connexion devient possible

### Requirement: Session lifecycle MUST enforce token expiry
Le cycle de session MUST invalider les sessions expirées et MUST maintenir les sessions valides selon la politique temporelle définie.

#### Scenario: Token is valid
- **WHEN** une action protégée est appelée avec une session valide
- **THEN** l’action est autorisée et l’état de session est maintenu

#### Scenario: Token is expired
- **WHEN** une action protégée est appelée avec une session expirée
- **THEN** l’appel est refusé avec un statut explicite de session expirée

