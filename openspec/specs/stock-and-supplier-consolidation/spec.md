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

### Requirement: La consolidation des besoins d'achat MUST rester consultable en continu
La consolidation des besoins d'achat MUST permettre de consulter l'etat courant de travail de la periode en cours, sans choix manuel de periode, et MUST permettre de consulter les consolidations enregistrees avec horodatage.

#### Scenario: Consultation de la consolidation courante
- **WHEN** un utilisateur autorise consulte la consolidation courante
- **THEN** le systeme retourne la consolidation courante de la periode en cours

#### Scenario: Consultation d'une consolidation enregistree
- **WHEN** un utilisateur autorise consulte l'historique de consolidation
- **THEN** le systeme retourne les consolidations enregistrees avec leur horodatage et leur periode associee

### Requirement: La consolidation MUST calculer la quantite a commander par article
La consolidation MUST calculer, pour chaque article, la quantite a commander avec la regle `max(0, quantite commandee familles + besoin FSE - stock disponible)`.

#### Scenario: Calcul unitaire de quantité d'achat
- **WHEN** les valeurs stock, quantite commandee familles et besoin FSE sont connues pour un article
- **THEN** le systeme calcule la quantite a commander pour cet article avec un plancher a 0

### Requirement: La consolidation MUST utiliser un prix d'achat sans identité fournisseur
La consolidation MUST utiliser, pour chaque article et periode concernés, une valeur `prix fournisseur HT` comme prix d'achat. Cette valeur MUST etre exploitable sans creer, selectionner ou maintenir une entite fournisseur.

#### Scenario: Calcul de consolidation avec prix d'achat
- **GIVEN** un article dispose d'un prix fournisseur HT pour la periode concernee
- **WHEN** un utilisateur autorise consulte ou calcule la consolidation
- **THEN** le systeme utilise ce prix comme prix d'achat de l'article
- **AND** aucun fournisseur identifiable n'est requis pour produire le resultat

### Requirement: La consolidation MUST supporter la saisie du besoin FSE par article
Le systeme MUST permettre la saisie et la persistence du besoin FSE (stock a conserver) pour chaque article dans le contexte d'une consolidation.

#### Scenario: Saisie du besoin FSE
- **WHEN** un utilisateur autorise saisit le besoin FSE d'un article dans la consolidation
- **THEN** le systeme enregistre cette valeur pour la consolidation ciblee

### Requirement: La consolidation MUST NOT gérer de référentiel fournisseur
La solution cible MUST NOT exposer de creation, modification, suppression, consultation ou rattachement d'une entite fournisseur. Les donnees et calculs de consolidation MUST rester centres sur l'article, la periode, le stock, le besoin FSE, les quantites et le prix d'achat.

#### Scenario: Accès à l'administration des articles
- **WHEN** un utilisateur autorise administre les donnees d'un article
- **THEN** il peut renseigner le prix fournisseur HT si cette donnee est requise
- **AND** il ne peut pas rattacher l'article a un fournisseur

#### Scenario: Recherche d'une capacité abandonnée
- **WHEN** un utilisateur consulte les menus ou capacites disponibles
- **THEN** aucun ecran ou action de gestion du referentiel fournisseur n'est propose

### Requirement: La consolidation MUST exposer les indicateurs financiers d'estimation conditionnels
Le systeme MUST calculer et exposer `CA estime`, `Marge estimee` et `Cout du stock prevu (besoin FSE)` pour la periode en cours lorsque les prix d'achat necessaires sont renseignes; sinon le systeme MUST signaler une estimation partielle.

#### Scenario: Estimation complete disponible
- **WHEN** tous les prix fournisseur HT necessaires sont renseignes pour la periode en cours
- **THEN** le systeme retourne les trois indicateurs financiers d'estimation

#### Scenario: Estimation partielle
- **WHEN** au moins un prix fournisseur HT necessaire est absent
- **THEN** le systeme retourne les indicateurs disponibles et signale explicitement que l'estimation est partielle
