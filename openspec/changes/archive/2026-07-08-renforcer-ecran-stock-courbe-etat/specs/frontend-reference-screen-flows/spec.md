## ADDED Requirements

### Requirement: L'ecran Stock MUST afficher une synthese recentree sur les ruptures
L'ecran `Stock` MUST afficher l'indicateur `articles en rupture`, et MUST NOT afficher les indicateurs `articles en alerte` ni `entrepots suivis`.

#### Scenario: Consultation de la synthese Stock
- **WHEN** un gestionnaire consulte l'ecran `Stock`
- **THEN** la zone de synthese affiche l'indicateur `articles en rupture`

#### Scenario: Absence d'indicateurs non retenus
- **WHEN** un gestionnaire consulte la zone de synthese de l'ecran `Stock`
- **THEN** les indicateurs `articles en alerte` et `entrepots suivis` ne sont pas affiches

### Requirement: Le listing Stock MUST retirer la colonne seuil mini
Le tableau de l'ecran `Stock` MUST presenter les informations article sans colonne `seuil mini`.

#### Scenario: Lecture des colonnes du listing Stock
- **WHEN** un gestionnaire lit l'en-tete du tableau `Stock`
- **THEN** la colonne `seuil mini` n'est pas presente

### Requirement: Le listing Stock MUST permettre la visualisation de la courbe de stock par article
Chaque ligne article du listing `Stock` MUST exposer une action pour visualiser l'evolution du stock de l'article aux dates de modification du stock.

#### Scenario: Acces a la courbe depuis un article du listing
- **WHEN** un gestionnaire selectionne l'action de visualisation sur une ligne article du listing `Stock`
- **THEN** l'interface ouvre la visualisation de la courbe de stock de cet article, ordonnee selon les dates de modification du stock

### Requirement: Le listing Stock MUST permettre la mise a jour du disponible avec etat calcule
Chaque article du listing `Stock` MUST pouvoir etre modifie en precisant le nombre disponible uniquement.
L'etat de stock affiche MUST etre calcule automatiquement: `Rupture` lorsque le nombre disponible est egal a 0, sinon `Disponible`.

#### Scenario: Mise a jour du disponible d'un article
- **WHEN** un gestionnaire modifie un article dans l'ecran `Stock`
- **THEN** l'interface permet la saisie du nombre disponible sans saisie manuelle de l'etat
- **AND** l'etat affiche est `Rupture` si le disponible vaut 0, sinon `Disponible`
