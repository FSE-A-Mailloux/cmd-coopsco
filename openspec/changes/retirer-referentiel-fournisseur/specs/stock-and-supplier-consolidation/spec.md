# Spec Delta

## Purpose

Décrire la suppression du référentiel fournisseur dans la consolidation et la
conservation du prix fournisseur HT comme qualification du prix d'achat, sans
identité fournisseur gérée.

## MODIFIED Requirements

### Requirement: La consolidation MUST utiliser un prix d'achat sans identité fournisseur

La consolidation MUST utiliser, pour chaque article et période concernés, une
valeur `prix fournisseur HT` comme prix d'achat. Cette valeur MUST être exploitable
sans créer, sélectionner ou maintenir une entité fournisseur.

#### Scenario: Calcul de consolidation avec prix d'achat

- **GIVEN** un article dispose d'un prix fournisseur HT pour la période concernée
- **WHEN** un utilisateur autorisé consulte ou calcule la consolidation
- **THEN** le système utilise ce prix comme prix d'achat de l'article
- **AND** aucun fournisseur identifiable n'est requis pour produire le résultat

#### Scenario: Prix d'achat manquant

- **GIVEN** le prix fournisseur HT nécessaire à un article n'est pas renseigné
- **WHEN** le système calcule les indicateurs financiers de la consolidation
- **THEN** il conserve les quantités calculables
- **AND** il signale explicitement que l'estimation financière est partielle

### Requirement: La consolidation MUST NOT gérer de référentiel fournisseur

La solution cible MUST NOT exposer de création, modification, suppression,
consultation ou rattachement d'une entité fournisseur. Les données et calculs de
consolidation MUST rester centrés sur l'article, la période, le stock, le besoin
FSE, les quantités et le prix d'achat.

#### Scenario: Accès à l'administration des articles

- **WHEN** un utilisateur autorisé administre les données d'un article
- **THEN** il peut renseigner le prix fournisseur HT si cette donnée est requise
- **AND** il ne peut pas rattacher l'article à un fournisseur

#### Scenario: Recherche d'une capacité abandonnée

- **WHEN** un utilisateur consulte les menus ou capacités disponibles
- **THEN** aucun écran ou action de gestion du référentiel fournisseur n'est proposé

## REMOVED Requirements

### Requirement: Le référentiel fournisseur MUST être géré comme une entité autonome

Cette capacité est retirée du périmètre cible. Les règles qui imposeraient une
identité fournisseur, un CRUD fournisseur ou un rattachement fournisseur à un
article ne sont plus applicables.