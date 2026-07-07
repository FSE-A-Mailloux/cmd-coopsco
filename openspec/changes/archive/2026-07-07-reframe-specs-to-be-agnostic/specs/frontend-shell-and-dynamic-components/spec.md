## ADDED Requirements

### Requirement: Legacy frontend implementation details MUST be treated as legacy-only
Les détails d’implémentation du shell frontend legacy (framework, fichiers et conventions historiques) MUST être considérés comme référence de migration uniquement et ne MUST pas contraindre l’implémentation cible.

#### Scenario: Rewrite without legacy framework coupling
- **WHEN** l’équipe implémente la nouvelle interface produit
- **THEN** elle respecte les comportements métier spécifiés sans obligation de reproduire la pile frontend legacy

## MODIFIED Requirements

### Requirement: Frontend shell MUST provide a stable application frame
Le produit MUST fournir un cadre applicatif stable avec zone d’authentification, navigation contextuelle, zone de contenu principal et feedback utilisateur, indépendamment de la technologie d’interface.

#### Scenario: Initial shell rendering
- **WHEN** l’utilisateur ouvre l’application
- **THEN** l’interface affiche les zones d’authentification, de navigation et de contenu dynamique

#### Scenario: Startup route resolution
- **WHEN** aucun contexte d’entrée explicite n’est fourni
- **THEN** l’application charge le composant métier par défaut

### Requirement: Component navigation MUST be stack-based
La navigation métier MUST conserver un historique de parcours permettant progression, retour arrière et retour ciblé vers un composant antérieur avec son contexte.

#### Scenario: Loading a component
- **WHEN** l’utilisateur ouvre un composant métier autorisé
- **THEN** l’application active le composant, met à jour le contexte et ajuste le menu affiché

#### Scenario: Returning to previous component
- **WHEN** l’utilisateur déclenche une action de retour
- **THEN** l’application restaure le composant précédent avec son contexte de navigation

### Requirement: Frontend state MUST expose loading and error feedback
L’interface MUST exposer un état de chargement pendant les opérations distantes et MUST afficher des messages d’erreur explicites lors d’échec de chargement ou d’exécution.

#### Scenario: Request lifecycle feedback
- **WHEN** une opération distante démarre puis se termine
- **THEN** l’état de chargement est visible pendant l’opération puis retiré à la fin

#### Scenario: Content loading failure
- **WHEN** un contenu métier ne peut pas être chargé
- **THEN** l’interface affiche une erreur explicite et évite d’afficher un état incohérent

