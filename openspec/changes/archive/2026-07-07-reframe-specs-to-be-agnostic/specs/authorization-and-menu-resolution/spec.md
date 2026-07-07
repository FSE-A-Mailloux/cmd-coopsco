## MODIFIED Requirements

### Requirement: Accredited actions MUST require function-level authorization
Toute action sensible MUST être autorisée par un contrôle de permissions explicites lié au rôle actif de l’utilisateur.

#### Scenario: Authorized accredited action
- **WHEN** un utilisateur possède la permission requise
- **THEN** l’action sensible est exécutée

#### Scenario: Unauthorized accredited action
- **WHEN** un utilisateur ne possède pas la permission requise
- **THEN** l’action est refusée avec une erreur de sécurité explicite

### Requirement: Component access MUST be validated before menu resolution
L’accès à un composant métier MUST être validé avant exposition de son contenu ou de sa navigation associée.

#### Scenario: Unauthorized component
- **WHEN** un utilisateur tente d’accéder à un composant non autorisé
- **THEN** le système refuse l’accès et ne retourne aucun contenu exploitable

### Requirement: Returned menu MUST be permission-aware
Le menu affiché MUST être calculé dynamiquement selon les permissions effectives du rôle actif et ne présenter que les destinations autorisées.

#### Scenario: Menu construction
- **WHEN** le système calcule le menu pour un utilisateur connecté
- **THEN** il retourne une structure ordonnée limitée aux entrées autorisées

