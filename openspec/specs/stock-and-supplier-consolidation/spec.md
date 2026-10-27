## Purpose

Définir les règles métier cibles pour une gestion de stock annuelle simple et la consolidation des besoins d'achat.

## Requirements

### Requirement: La gestion de stock MUST supporter un modèle d’exploitation annuel simple
La gestion de stock MUST rester simple, avec ajustements de quantités article, inventaires périodiques et préparation d’un cycle annuel d'achat.

#### Scenario: Ajustement de stock
- **WHEN** un utilisateur autorisé ajuste une quantité de stock
- **THEN** le système enregistre la nouvelle valeur avec traçabilité

#### Scenario: Prévention du stock négatif
- **WHEN** une opération conduirait à un stock négatif
- **THEN** le système rejette l’opération

### Requirement: Le suivi de stock article MUST exposer une évolution temporelle consultable
Le système MUST permettre de consulter, pour un article donné, l'évolution du niveau de stock basée sur les dates de modification de stock.

#### Scenario: Consultation de l'historique de stock d'un article
- **WHEN** un utilisateur autorisé demande la visualisation de l'évolution de stock d'un article
- **THEN** le système retourne une représentation temporelle du stock pour cet article, indexée par les dates de modification de stock

### Requirement: La mise à jour de stock article MUST porter sur la quantité disponible avec état dérivé
La mise à jour du stock d'un article MUST permettre de définir la quantité disponible, avec traçabilité.
L'état de stock MUST être dérivé automatiquement de la quantité disponible: `Rupture` si la quantité disponible est égale à 0, sinon `Disponible`.

#### Scenario: Mise a jour complète d'un article de stock
- **WHEN** un utilisateur autorisé modifie le stock d'un article
- **THEN** le système enregistre la quantité disponible de l'article avec traçabilité
- **AND** le système met à jour l'état de stock dérivé en fonction de la quantité disponible

### Requirement: La consolidation des besoins d'achat MUST rester consultable en continu
La consolidation des besoins d'achat MUST permettre de consulter l'état courant de travail de la période en cours, sans choix manuel de période, et MUST permettre de consulter les consolidations enregistrées avec horodatage.

#### Scenario: Consultation de la consolidation courante
- **WHEN** un utilisateur autorisé consulte la consolidation courante
- **THEN** le système retourne la consolidation courante de la période en cours

#### Scenario: Consultation d'une consolidation enregistrée
- **WHEN** un utilisateur autorisé consulte l'historique de consolidation
- **THEN** le système retourne les consolidations enregistrées avec leur horodatage et leur période associée

### Requirement: La consolidation MUST calculer la quantité à commander par article
La consolidation MUST calculer, pour chaque article, la quantité à commander avec la règle `max(0, quantité commandée familles + besoin FSE - stock disponible)`.

#### Scenario: Calcul unitaire de quantité d'achat
- **WHEN** les valeurs stock, quantité commandée familles et besoin FSE sont connues pour un article
- **THEN** le système calcule la quantité à commander pour cet article avec un plancher à 0

### Requirement: La consolidation MUST utiliser un prix d'achat sans identité fournisseur
La consolidation MUST utiliser, pour chaque article et période concernés, une valeur `prix fournisseur HT` comme prix d'achat. Cette valeur MUST être exploitable sans créer, sélectionner ou maintenir une entité fournisseur.

#### Scenario: Calcul de consolidation avec prix d'achat
- **GIVEN** un article dispose d'un prix fournisseur HT pour la période concernée
- **WHEN** un utilisateur autorisé consulte ou calcule la consolidation
- **THEN** le système utilise ce prix comme prix d'achat de l'article
- **AND** aucun fournisseur identifiable n'est requis pour produire le résultat

### Requirement: La consolidation MUST supporter la saisie du besoin FSE par article
Le système MUST permettre la saisie et la persistance du besoin FSE (stock à conserver) pour chaque article dans le contexte d'une consolidation.

#### Scenario: Saisie du besoin FSE
- **WHEN** un utilisateur autorisé saisit le besoin FSE d'un article dans la consolidation
- **THEN** le système enregistre cette valeur pour la consolidation ciblée

### Requirement: La consolidation MUST NOT gérer de référentiel fournisseur
La solution cible MUST NOT exposer de création, modification, suppression, consultation ou rattachement d'une entité fournisseur. Les données et calculs de consolidation MUST rester centrés sur l'article, la période, le stock, le besoin FSE, les quantités et le prix d'achat.

#### Scenario: Accès à l'administration des articles
- **WHEN** un utilisateur autorisé administre les données d'un article
- **THEN** il peut renseigner le prix fournisseur HT si cette donnée est requise
- **AND** il ne peut pas rattacher l'article à un fournisseur

#### Scenario: Recherche d'une capacité abandonnée
- **WHEN** un utilisateur consulte les menus ou capacités disponibles
- **THEN** aucun écran ou action de gestion du référentiel fournisseur n'est proposé

### Requirement: La consolidation MUST exposer les indicateurs financiers d'estimation conditionnels
Le système MUST calculer et exposer `CA estimé`, `Marge estimée` et `Coût du stock prévu (besoin FSE)` pour la période en cours lorsque les prix d'achat nécessaires sont renseignés; sinon le système MUST signaler une estimation partielle.

#### Scenario: Estimation complète disponible
- **WHEN** tous les prix fournisseur HT nécessaires sont renseignés pour la période en cours
- **THEN** le système retourne les trois indicateurs financiers d'estimation

#### Scenario: Estimation partielle
- **WHEN** au moins un prix fournisseur HT nécessaire est absent
- **THEN** le système retourne les indicateurs disponibles et signale explicitement que l'estimation est partielle
