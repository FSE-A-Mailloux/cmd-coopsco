## Purpose

Définir le comportement attendu d’authentification et le cycle de vie de session basé sur jeton.

## Requirements

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

### Requirement: Un utilisateur MUST pouvoir demander le renvoi d'un code de validation avant l'activation de son compte
Le système MUST permettre au titulaire d'un compte en attente d'activation de demander un nouveau code de validation si le courriel initial n'a pas été reçu. Le nouveau code MUST être envoyé à l'adresse courriel associée au compte et invalider le code précédent seulement lorsque l'envoi du nouveau code a réussi. Le renvoi MUST NOT activer le compte.

#### Scenario: Renvoi demandé pour un compte en attente d'activation
- **WHEN** le titulaire demande le renvoi de son code de validation depuis le parcours d'activation
- **THEN** le système envoie un nouveau code à l'adresse courriel associée au compte et rend le code précédent inutilisable
- **AND** le compte reste en attente d'activation

#### Scenario: Échec de l'envoi du nouveau code
- **WHEN** le système ne parvient pas à envoyer le nouveau code
- **THEN** il signale l'échec du renvoi sans confirmer l'envoi
- **AND** le code précédemment émis reste utilisable

#### Scenario: Renvoi demandé pour un compte déjà activé
- **WHEN** le titulaire demande un code de validation pour un compte déjà activé
- **THEN** le système n'émet ni n'envoie de nouveau code et ne modifie pas l'état du compte

### Requirement: Le cycle de vie de session MUST appliquer l’expiration des jetons
Le cycle de session MUST invalider les sessions expirées et maintenir actives les sessions valides selon la politique temporelle définie.

#### Scenario: Jeton valide
- **WHEN** une action protégée est demandée avec une session valide
- **THEN** le système autorise l’action et maintient l’état de session

#### Scenario: Jeton expiré
- **WHEN** une action protégée est demandée avec une session expirée
- **THEN** le système refuse l’action avec un statut explicite de session expirée
