## MODIFIED Requirements

### Requirement: La gestion de stock MUST supporter un modèle d’exploitation annuel simple
La gestion de stock MUST rester simple, avec ajustements de quantités article, inventaires périodiques et préparation d’un cycle de commande fournisseur annuel.

#### Scenario: Ajustement de stock
- **WHEN** un utilisateur autorisé ajuste une quantité de stock
- **THEN** le système enregistre la nouvelle valeur avec traçabilité

#### Scenario: Prévention du stock négatif
- **WHEN** une opération conduirait à un stock négatif
- **THEN** le système rejette l’opération

### Requirement: La consolidation fournisseur MUST rester consultable en continu
La consolidation fournisseur MUST rester accessible à tout moment, sans changement d’état ni étape d’approbation, pour visualiser les besoins d’achat.

#### Scenario: Consultation de la consolidation à tout moment
- **WHEN** un utilisateur autorisé consulte la consolidation
- **THEN** le système retourne la consolidation courante sans prérequis supplémentaire

### Requirement: La consolidation MUST calculer de manière cohérente les unités nettes et les lots
La consolidation MUST calculer les unités nettes et les lots à commander selon les règles métier de calcul, d’arrondi et de plancher.

#### Scenario: Calcul des lots
- **WHEN** les données nécessaires au calcul sont disponibles
- **THEN** le système calcule un nombre entier de lots conforme aux règles métier
