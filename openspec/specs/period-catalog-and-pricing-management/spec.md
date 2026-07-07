## Purpose

Définir les règles métier cibles pour les périodes de campagne, le cycle de vie du catalogue articles et la tarification par période.

## Requirements

### Requirement: La gestion des périodes MUST supporter les contrôles du cycle de campagne
Le système MUST gérer les périodes de commande avec leurs états métier (active, ouverte/fermée, verrouillée) et MUST garantir l’unicité de la période active.

#### Scenario: Définition d’une période active
- **WHEN** une période est activée
- **THEN** le système retire le statut actif des autres périodes

#### Scenario: Ouverture/fermeture de période
- **WHEN** le statut d’ouverture d’une période est modifié
- **THEN** l’état de la période est mis à jour sans changer son identité métier

### Requirement: Le catalogue articles MUST supporter un cycle de vie gouverné
Le catalogue articles MUST permettre la création, la mise à jour et la suppression contrôlées, avec cohérence des dépendances métier.

#### Scenario: Création ou mise à jour d’un article
- **WHEN** un article valide est enregistré par un utilisateur autorisé
- **THEN** il devient disponible pour les usages métier autorisés

#### Scenario: Suppression d’un article avec dépendances
- **WHEN** un article est supprimé
- **THEN** le système applique la politique métier prévue sur les données dépendantes

### Requirement: La tarification par période MUST supporter la duplication complète et les mises à jour unitaires
Le système MUST gérer la tarification article par période, permettre la duplication complète inter-périodes, puis autoriser des ajustements unitaires sur la période cible.

#### Scenario: Duplication des prix vers une période cible
- **WHEN** une duplication complète est demandée vers une période non verrouillée
- **THEN** le système copie les prix source après confirmation d’écrasement

#### Scenario: Modification d’un prix unitaire dupliqué
- **WHEN** un prix dupliqué est ajusté
- **THEN** le système enregistre la nouvelle valeur avec traçabilité

### Requirement: La tarification d’une période verrouillée MUST être immuable
Les prix d’une période verrouillée MUST être non modifiables.

#### Scenario: Tentative de mise à jour sur période verrouillée
- **WHEN** une mise à jour de prix est demandée sur une période verrouillée
- **THEN** le système rejette l’opération avec une erreur fonctionnelle explicite

