## ADDED Requirements

### Requirement: Stock management MUST support a simple annual operating model
La gestion de stock MUST rester volontairement simple, avec ajustement des quantités par article, une commande fournisseur annuelle et environ trois inventaires par an.

#### Scenario: Stock adjustment
- **WHEN** un utilisateur autorisé ajuste un stock article
- **THEN** la nouvelle quantité est enregistrée avec traçabilité

#### Scenario: Negative stock prevention
- **WHEN** une opération conduit à une quantité négative
- **THEN** le système refuse l’opération

### Requirement: Supplier consolidation MUST remain continuously viewable
La consolidation fournisseur MUST calculer les besoins par fournisseur et MUST rester consultable à tout moment, sans changement d’état ni workflow d’approbation.

#### Scenario: View consolidation at any time
- **WHEN** un utilisateur autorisé consulte la consolidation
- **THEN** le système retourne l’état de consolidation courant sans prérequis d’approbation

### Requirement: Consolidation MUST compute net units and lots consistently
La consolidation MUST calculer les unités nettes et le nombre de lots à commander par article, avec arrondi supérieur et plancher à zéro.

#### Scenario: Lot calculation
- **WHEN** unités nettes et taille de lot sont disponibles
- **THEN** le système calcule un nombre de lots entier conforme à la règle métier

