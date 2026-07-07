## ADDED Requirements

### Requirement: Period management MUST support campaign lifecycle controls
Le système MUST gérer des périodes de commande avec libellé, préfixe, date butoir et états métier (en cours, ouverte/fermée, verrouillée), avec unicité de la période "en cours".

#### Scenario: Mark active period
- **WHEN** une période est activée comme "en cours"
- **THEN** le système désactive ce statut pour toutes les autres périodes

#### Scenario: Open/close period
- **WHEN** une période est ouverte ou fermée
- **THEN** son état d’ouverture est mis à jour sans changer son identité métier

### Requirement: Article catalog MUST support governed lifecycle
Le référentiel articles MUST permettre création, modification et suppression contrôlées des articles avec attributs métier, rattachement fournisseur et règles de cohérence des dépendances.

#### Scenario: Create or update article
- **WHEN** un administrateur autorisé enregistre un article valide
- **THEN** l’article est disponible pour les usages autorisés (commande, stock, consolidation)

#### Scenario: Delete article with dependencies
- **WHEN** un article est supprimé
- **THEN** le système applique la politique métier de neutralisation/suppression des dépendances (prix périodiques, stock)

### Requirement: Pricing by period MUST support full duplication and unit updates
Le système MUST gérer les prix article par période (prix famille, prix fournisseur, lot fournisseur), permettre la duplication complète inter-périodes, puis la modification unitaire des prix dupliqués.

#### Scenario: Duplicate prices to target period
- **WHEN** une duplication complète des prix est demandée vers une période cible non verrouillée
- **THEN** le système copie l’ensemble des prix de la période source après confirmation d’écrasement

#### Scenario: Modify duplicated unit price
- **WHEN** un prix d’article dupliqué est ajusté sur la période cible
- **THEN** la valeur unitaire est mise à jour avec traçabilité d’audit

### Requirement: Locked period pricing MUST be immutable
Les prix d’une période verrouillée MUST être non modifiables.

#### Scenario: Attempt update on locked period
- **WHEN** une modification de prix est demandée sur une période verrouillée
- **THEN** le système refuse l’opération avec une erreur fonctionnelle explicite

