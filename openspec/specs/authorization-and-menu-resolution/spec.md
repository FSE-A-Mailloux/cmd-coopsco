## Purpose

Définir les règles d’autorisation et le comportement de résolution de menu tenant compte des capacités.

## Requirements

### Requirement: Les actions accréditées MUST exiger une autorisation au niveau fonctionnel
Toute action sensible MUST être exécutée uniquement si l’utilisateur dispose de la permission fonctionnelle requise.

#### Scenario: Action accréditée autorisée
- **WHEN** un utilisateur possède la permission nécessaire pour une action sensible
- **THEN** le système autorise l’exécution de cette action

#### Scenario: Action accréditée non autorisée
- **WHEN** un utilisateur ne possède pas la permission nécessaire
- **THEN** le système refuse l’action avec une erreur de sécurité explicite

### Requirement: L’accès composant MUST être validé avant la résolution du menu
L’accès à un composant métier MUST être validé avant d’exposer son contenu ou sa navigation associée.

#### Scenario: Composant non autorisé
- **WHEN** un utilisateur tente d’accéder à un composant sans permission
- **THEN** le système refuse l’accès et ne retourne pas de contenu composant exploitable

### Requirement: Le menu retourné MUST refléter les permissions
Le menu affiché MUST être calculé selon les permissions effectives de l’utilisateur et ne présenter que les destinations autorisées.

#### Scenario: Construction du menu
- **WHEN** le système calcule le menu d’un utilisateur connecté
- **THEN** il retourne une structure de navigation ordonnée limitée aux entrées autorisées

