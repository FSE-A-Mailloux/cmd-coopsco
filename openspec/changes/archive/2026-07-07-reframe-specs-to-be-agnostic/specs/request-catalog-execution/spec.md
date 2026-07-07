## ADDED Requirements

### Requirement: Legacy request catalog mechanism MUST be treated as legacy-only
Le mécanisme legacy de résolution de requêtes par catalogue SQL et codes techniques MUST être considéré comme legacy-only et ne MUST pas être imposé à la cible.

#### Scenario: To-be service contract without legacy SQL catalog
- **WHEN** la cible expose des données métier de lecture
- **THEN** elle respecte des contrats de requêtes gouvernés sans dépendre du modèle technique legacy

## MODIFIED Requirements

### Requirement: Cataloged query execution MUST resolve by request code
Les consultations métier MUST être exposées par des requêtes identifiées et gouvernées, chacune avec un contrat d’entrée/sortie explicite et stable pour les composants consommateurs.

#### Scenario: Existing request code
- **WHEN** un composant appelle une requête métier connue avec des paramètres valides
- **THEN** le système retourne des données structurées conformes au contrat attendu

#### Scenario: Unknown request code
- **WHEN** une requête inconnue est demandée
- **THEN** le système retourne une erreur fonctionnelle explicite sans exécution partielle

### Requirement: SQL bind parameters MUST be restricted to referenced placeholders
Le système MUST appliquer une liste blanche des paramètres autorisés par requête et ignorer/refuser tout paramètre inattendu afin de garantir cohérence et sécurité.

#### Scenario: Placeholder filtering
- **WHEN** un appel contient des paramètres additionnels non autorisés
- **THEN** seuls les paramètres définis par le contrat sont pris en compte

### Requirement: Frontend components MUST consume catalog results through shared service calls
Les composants frontend MUST consommer les données métier via un service applicatif partagé et non via des accès ad hoc, afin d’assurer cohérence des formats, gestion d’erreurs et observabilité.

#### Scenario: Shared data access
- **WHEN** un composant a besoin d’une liste ou d’un détail métier
- **THEN** il appelle le service applicatif partagé et reçoit un format de réponse homogène

