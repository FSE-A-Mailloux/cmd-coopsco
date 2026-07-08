## Purpose

Définir les règles métier cibles pour une gestion de stock annuelle simple et la consolidation fournisseur.

## Requirements

### Requirement: La gestion de stock MUST supporter un modèle d’exploitation annuel simple
La gestion de stock MUST rester simple, avec ajustements de quantités article, inventaires périodiques et préparation d’un cycle de commande fournisseur annuel.

#### Scenario: Ajustement de stock
- **WHEN** un utilisateur autorisé ajuste une quantité de stock
- **THEN** le système enregistre la nouvelle valeur avec traçabilité

#### Scenario: Prévention du stock négatif
- **WHEN** une opération conduirait à un stock négatif
- **THEN** le système rejette l’opération

### Requirement: Le suivi de stock article MUST exposer une evolution temporelle consultable
Le systeme MUST permettre de consulter, pour un article donne, l'evolution du niveau de stock basee sur les dates de modification de stock.

#### Scenario: Consultation de l'historique de stock d'un article
- **WHEN** un utilisateur autorise demande la visualisation de l'evolution de stock d'un article
- **THEN** le systeme retourne une representation temporelle du stock pour cet article, indexee par les dates de modification de stock

### Requirement: La mise a jour de stock article MUST porter sur la quantite disponible avec etat derive
La mise a jour du stock d'un article MUST permettre de definir la quantite disponible, avec tracabilite.
L'etat de stock MUST etre derive automatiquement de la quantite disponible: `Rupture` si la quantite disponible est egale a 0, sinon `Disponible`.

#### Scenario: Mise a jour complete d'un article de stock
- **WHEN** un utilisateur autorise modifie le stock d'un article
- **THEN** le systeme enregistre la quantite disponible de l'article avec tracabilite
- **AND** le systeme met a jour l'etat de stock derive en fonction de la quantite disponible

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
