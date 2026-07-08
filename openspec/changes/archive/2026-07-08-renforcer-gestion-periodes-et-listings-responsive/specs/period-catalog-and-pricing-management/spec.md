## MODIFIED Requirements

### Requirement: La gestion des périodes MUST supporter les contrôles du cycle de campagne
Le système MUST distinguer, pour chaque période, un `statut de periode` (`En cours` ou `Non en cours`) et un `etat d'ouverture` (`Ouverte` ou `Fermee`).  
Le statut `Non en cours` MUST couvrir les périodes passées et les périodes en préparation, et ces périodes MUST NOT être ouvertes aux commandes.  
Le système MUST garantir l’unicité de la période au statut `En cours` et MUST autoriser l’ouverture des commandes uniquement sur cette période.

#### Scenario: Définition d'une periode en cours
- **WHEN** une periode est definie `En cours`
- **THEN** le systeme retire ce statut des autres periodes

#### Scenario: Ouverture/fermeture de période en cours
- **WHEN** le statut d’ouverture de la période en cours est modifié
- **THEN** l’état d’ouverture de cette période est mis à jour sans changer son identité métier

#### Scenario: Refus d’ouverture d’une période non courante
- **WHEN** une demande d’ouverture est effectuée sur une période qui n’est pas en cours
- **THEN** le système refuse l’opération avec une erreur fonctionnelle explicite

#### Scenario: Bascule vers une nouvelle période en cours
- **WHEN** une période non courante est définie en cours
- **THEN** elle devient l’unique période en cours et la période précédemment en cours perd ce statut

## ADDED Requirements

### Requirement: La modification des cotisations de période MUST être non rétroactive sur les commandes existantes
Lorsqu’une cotisation de période est modifiée, le système MUST appliquer la nouvelle valeur uniquement aux commandes créées après cette modification, et MUST conserver inchangées les cotisations déjà enregistrées sur les commandes existantes de la période.

#### Scenario: Modification de cotisation après création de commandes
- **WHEN** un gestionnaire modifie les montants de cotisation d’une période pour laquelle des commandes existent déjà
- **THEN** les commandes déjà créées conservent leur cotisation initiale et seules les nouvelles commandes utilisent la cotisation mise à jour
