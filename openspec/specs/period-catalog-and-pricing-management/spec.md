## Purpose

Définir les règles métier cibles pour les périodes de campagne, le cycle de vie du catalogue articles et la tarification par période.

## Requirements

### Requirement: La gestion des périodes MUST supporter les contrôles du cycle de campagne
Le système MUST distinguer, pour chaque période, un `statut de période` (`En cours` ou `Non en cours`) et un `état d'ouverture` (`Ouverte` ou `Fermée`).\
Le statut `Non en cours` MUST couvrir les périodes passées et les périodes en préparation, et ces périodes MUST NOT être ouvertes aux commandes.  
Le système MUST garantir l’unicité de la période au statut `En cours` et MUST autoriser l’ouverture des commandes uniquement sur cette période.

#### Scenario: Définition d'une période en cours
- **WHEN** une période est définie `En cours`
- **THEN** le système retire ce statut des autres périodes

#### Scenario: Ouverture/fermeture de période en cours
- **WHEN** le statut d’ouverture de la période en cours est modifié
- **THEN** l’état d’ouverture de cette période est mis à jour sans changer son identité métier

#### Scenario: Refus d’ouverture d’une période non courante
- **WHEN** une demande d’ouverture est effectuée sur une période qui n’est pas en cours
- **THEN** le système refuse l’opération avec une erreur fonctionnelle explicite

#### Scenario: Bascule vers une nouvelle période en cours
- **WHEN** une période non courante est définie en cours
- **THEN** elle devient l’unique période en cours et la période précédemment en cours perd ce statut

### Requirement: La modification des cotisations de période MUST être non rétroactive sur les commandes existantes
Lorsqu’une cotisation de période est modifiée, le système MUST appliquer la nouvelle valeur uniquement aux commandes créées après cette modification, et MUST conserver inchangées les cotisations déjà enregistrées sur les commandes existantes de la période.

#### Scenario: Modification de cotisation après création de commandes
- **WHEN** un gestionnaire modifie les montants de cotisation d’une période pour laquelle des commandes existent déjà
- **THEN** les commandes déjà créées conservent leur cotisation initiale et seules les nouvelles commandes utilisent la cotisation mise à jour

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
