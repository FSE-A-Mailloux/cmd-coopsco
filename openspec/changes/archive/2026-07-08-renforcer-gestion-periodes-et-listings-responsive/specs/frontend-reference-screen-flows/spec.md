## ADDED Requirements

### Requirement: Les tableaux des ecrans de listing MUST etre responsives
Tous les ecrans de listing qui presentent des donnees tabulaires MUST rester lisibles et exploitables en vue telephone comme en vue desktop.

#### Scenario: Consultation d'un listing tabulaire en vue telephone
- **WHEN** un utilisateur consulte un ecran de listing sur telephone
- **THEN** l'interface presente les donnees de maniere responsive avec conservation des informations essentielles et des actions principales

#### Scenario: Consultation d'un listing tabulaire en vue desktop
- **WHEN** un utilisateur consulte un ecran de listing sur desktop
- **THEN** l'interface affiche une presentation tabulaire complete et alignee avec les memes informations metier

### Requirement: Le domaine periodes MUST inclure les ecrans de creation et de modification
L'application MUST proposer un ecran `Periodes - Creation` et un ecran `Periodes - Modification` en complement du listing et du detail de periode.  
Les ecrans de creation et de modification MUST inclure la saisie des cotisations (`1er enfant`, `2e enfant`, `3e enfant et suivants`).  
L'ecran de creation MUST afficher la reprise de tarifs comme recommandee pour eviter une periode avec tarifs vides.  
Les ecrans periodes (detail, creation, modification) MUST NOT afficher de champ `Code periode`.

#### Scenario: Ouverture de l'ecran de creation de periode
- **WHEN** un gestionnaire declenche l'action de creation depuis `Periodes - Listing et actions`
- **THEN** l'interface ouvre l'ecran `Periodes - Creation`

#### Scenario: Ouverture de l'ecran de modification de periode
- **WHEN** un gestionnaire declenche l'action de modification sur une periode existante
- **THEN** l'interface ouvre l'ecran `Periodes - Modification`

#### Scenario: Saisie des cotisations dans la creation de periode
- **WHEN** un gestionnaire ouvre `Periodes - Creation`
- **THEN** il renseigne les montants de cotisation pour `1er enfant`, `2e enfant`, et `3e enfant et suivants`

#### Scenario: Saisie des cotisations dans la modification de periode
- **WHEN** un gestionnaire ouvre `Periodes - Modification`
- **THEN** il modifie les montants de cotisation pour `1er enfant`, `2e enfant`, et `3e enfant et suivants`

#### Scenario: Reprise des tarifs recommandee a la creation de periode
- **WHEN** un gestionnaire ouvre `Periodes - Creation`
- **THEN** l'interface affiche un libelle de reprise tarifs en mode `recommandee` avec un message explicitant le risque de tarifs vides sans reprise

#### Scenario: Absence de code periode dans les ecrans periodes
- **WHEN** un utilisateur consulte `Periodes - Detail`, `Periodes - Creation` ou `Periodes - Modification`
- **THEN** l'interface n'affiche pas de champ `Code periode`

## MODIFIED Requirements

### Requirement: Le domaine periodes MUST proposer un ecran detaille de periode
L'application MUST inclure un ecran detail de periode consultable depuis la liste des periodes et MUST y exposer une action explicite de modification de la periode.

#### Scenario: Consultation du detail d'une periode
- **WHEN** un utilisateur ouvre le detail d'une periode
- **THEN** il voit les informations cles de cette periode et ses indicateurs de pilotage

#### Scenario: Action de modification depuis le detail de periode
- **WHEN** un utilisateur consulte `Periodes - Detail`
- **THEN** l'interface affiche un bouton de modification permettant d'ouvrir `Periodes - Modification`

### Requirement: Le domaine periodes MUST proposer des actions d'etat
L'application MUST exposer des actions pour ouvrir ou fermer une periode aux commandes et pour la definir comme "periode en cours".  
Dans `Periodes - Listing et actions`, seule la periode en cours MUST afficher l'action `Ouvrir` active, une seule periode MUST etre marquee `en cours` a un instant donne, et seules les periodes non courantes MUST afficher l'action `Definir en cours` active.  
Le statut de periode MUST etre libelle `En cours` ou `Non en cours` (periode passee ou en preparation), et l'etat d'ouverture MUST etre libelle `Ouverte` ou `Fermee`.  
Dans `Periodes - Modification`, le `Statut periode` MUST etre presente avant `Etat d'ouverture`, sous forme de toggles, et le toggle d'ouverture MUST etre inactif tant que la periode n'est pas en cours.

#### Scenario: Pilotage du cycle de vie d'une periode
- **WHEN** un utilisateur consulte la liste des periodes
- **THEN** il dispose d'actions explicites pour ouvrir, fermer et definir la periode en cours

#### Scenario: Action ouvrir active uniquement sur la periode en cours
- **WHEN** un utilisateur consulte `Periodes - Listing et actions`
- **THEN** le bouton `Ouvrir` est actif uniquement sur la ligne de la periode marquee `en cours`

#### Scenario: Unicite de la periode en cours dans le listing
- **WHEN** un utilisateur consulte `Periodes - Listing et actions`
- **THEN** une seule ligne est marquee `en cours`

#### Scenario: Action definir en cours active uniquement hors periode courante
- **WHEN** un utilisateur consulte `Periodes - Listing et actions`
- **THEN** seules les lignes non marquees `en cours` affichent un bouton `Definir en cours` actif

#### Scenario: Libelles de statut et d'etat sur les ecrans periodes
- **WHEN** un utilisateur consulte les ecrans de periode
- **THEN** le statut de periode est affiche avec `En cours` ou `Non en cours`, et l'etat d'ouverture avec `Ouverte` ou `Fermee`

#### Scenario: Ordre et format des controles d'etat en modification
- **WHEN** un utilisateur consulte `Periodes - Modification`
- **THEN** les controles d'etat sont affiches en toggles avec `Statut periode` avant `Etat d'ouverture`

#### Scenario: Ouverture bloquee hors periode en cours en modification
- **WHEN** une periode n'est pas marquee `en cours` dans `Periodes - Modification`
- **THEN** le controle `Etat d'ouverture` reste inactif et ne peut pas passer a `Ouverte`
