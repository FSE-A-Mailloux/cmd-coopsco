## ADDED Requirements

### Requirement: Legacy webservice entrypoint wiring MUST be treated as legacy-only
Le chaînage technique legacy de dispatch des actions (fichiers d’entrée et conventions de résolution historiques) MUST être considéré comme legacy-only.

#### Scenario: Technology-agnostic service orchestration
- **WHEN** la cible implémente le point d’entrée applicatif
- **THEN** elle respecte les contrats fonctionnels sans reproduire le câblage technique legacy

## MODIFIED Requirements

### Requirement: Webservice entrypoint MUST accept a normalized JSON contract
Le point d’entrée applicatif MUST accepter un contrat de requête normalisé incluant l’action demandée, le contexte de session et les paramètres, et MUST rejeter les charges invalides avec une erreur de sécurité explicite.

#### Scenario: Invalid payload structure
- **WHEN** un appel ne respecte pas le contrat d’entrée attendu
- **THEN** le système retourne une erreur de sécurité explicite

#### Scenario: Valid payload structure
- **WHEN** un appel respecte le contrat d’entrée attendu
- **THEN** le système exécute l’action métier demandée via le routage applicatif

### Requirement: Action execution MUST be transactional
Les opérations d’écriture critiques MUST être exécutées de manière atomique (succès complet ou annulation complète) afin de préserver l’intégrité métier.

#### Scenario: Handler success
- **WHEN** une action métier d’écriture se termine sans erreur
- **THEN** les changements sont validés de manière atomique

#### Scenario: Handler failure
- **WHEN** une action d’écriture échoue pendant le traitement
- **THEN** les changements partiels sont annulés et une erreur explicite est retournée

### Requirement: Error mapping MUST be stable and explicit
Le système MUST mapper les erreurs dans une taxonomie stable distinguant au minimum erreurs fonctionnelles, erreurs de sécurité, session expirée et erreurs techniques.

#### Scenario: Expired token
- **WHEN** une session expirée est utilisée pour une action protégée
- **THEN** le système retourne un statut explicite de session expirée

#### Scenario: Functional validation failure
- **WHEN** une règle métier est violée
- **THEN** le système retourne une erreur fonctionnelle avec message exploitable

