## MODIFIED Requirements

### Requirement: L'authentification MUST émettre un nouveau jeton de session en cas de connexion réussie
Le système MUST authentifier les identifiants utilisateur, ouvrir une session active et retourner le contexte d’identité nécessaire à l’usage de l’application.

#### Scenario: Connexion réussie avec identifiants valides
- **WHEN** un utilisateur fournit des identifiants valides
- **THEN** le système ouvre une session et retourne les informations d’identité et de contexte attendues

#### Scenario: Connexion refusée avec identifiants invalides
- **WHEN** un utilisateur fournit des identifiants invalides
- **THEN** le système refuse la connexion avec une erreur fonctionnelle explicite

### Requirement: Les liens d’activation MUST prendre en charge le parcours de validation de compte
Le système MUST permettre la validation d’un compte via un mécanisme d’activation sécurisé et exploitable par l’utilisateur.

#### Scenario: Activation par code de lien
- **WHEN** un code d’activation valide est utilisé
- **THEN** le système valide le compte et autorise la poursuite du parcours d’authentification

### Requirement: Le cycle de vie de session MUST appliquer l’expiration des jetons
Le cycle de session MUST invalider les sessions expirées et maintenir actives les sessions valides selon la politique temporelle définie.

#### Scenario: Jeton valide
- **WHEN** une action protégée est demandée avec une session valide
- **THEN** le système autorise l’action et maintient l’état de session

#### Scenario: Jeton expiré
- **WHEN** une action protégée est demandée avec une session expirée
- **THEN** le système refuse l’action avec un statut explicite de session expirée
