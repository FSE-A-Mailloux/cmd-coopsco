## MODIFIED Requirements

### Requirement: L'application MUST proposer une navigation liste-a-apercu des ecrans
Le module de consultation des ecrans MUST afficher une liste complete des ecrans disponibles sur la gauche et MUST afficher l'apercu de l'ecran selectionne sur la droite.  
La liste de navigation MUST disposer d'un scroll vertical independant du panneau d'apercu pour faciliter la navigation entre ecrans.

#### Scenario: Selection d'un ecran depuis la liste gauche
- **WHEN** un utilisateur selectionne un ecran dans la liste de navigation gauche
- **THEN** le panneau d'apercu droit affiche l'ecran correspondant

#### Scenario: Scroll independant de la liste d'ecrans
- **WHEN** un utilisateur fait defiler la liste de navigation gauche
- **THEN** seul le panneau de liste se deplace et l'apercu de l'ecran selectionne reste visible

### Requirement: La liste des commandes gestionnaire MUST proposer une recherche multicriteres en champ unique, un filtre periode et un filtre statuts
L'ecran `Liste des commandes (gestionnaire)` MUST proposer un champ de recherche unique qui interroge les informations famille (`email parent`, `nom/prenom parent`, `nom/prenom enfant`) et le `numero de commande`, MUST permettre la selection d'une periode avec la `periode en cours` preselectionnee par defaut, et MUST proposer un filtre de statuts en selection multiple.  
Pour les commandes en statut `Confirmee`, l'ecran MUST exposer une action d'encaissement cheque/espece.

#### Scenario: Recherche multicriteres via champ unique
- **WHEN** un gestionnaire saisit une valeur dans le champ de recherche unique
- **THEN** l'interface indique que la recherche est appliquee simultanement sur email, nom et prenom du parent et de l'enfant, ainsi que sur le numero de commande

#### Scenario: Filtre periode preselectionne sur la periode en cours
- **WHEN** un gestionnaire ouvre l'ecran `Liste des commandes (gestionnaire)`
- **THEN** le filtre `Periode` affiche la periode en cours par defaut, tout en permettant de selectionner une periode passee

#### Scenario: Filtre statuts en selection multiple
- **WHEN** un gestionnaire choisit plusieurs statuts de commande dans le filtre de statuts
- **THEN** l'interface represente une selection multiple de statuts appliquee au filtrage de la liste

#### Scenario: Action d'encaissement depuis la liste des commandes
- **WHEN** un gestionnaire consulte une commande en statut `Confirmee` dans `Liste des commandes (gestionnaire)`
- **THEN** l'interface affiche une action `Encaisser (cheque/espece)` sur la commande

### Requirement: Les gestionnaires MUST disposer d'un parcours d'encaissement cheque/espece
L'application MUST presenter un parcours gestionnaire dedie a l'encaissement declenche depuis une commande existante (liste des commandes ou detail de commande), avec association d'un ou plusieurs paiements et formulaire de saisie des paiements cheque ou espece contextualise sur la commande selectionnee.

#### Scenario: Demarrage d'encaissement depuis la liste des commandes
- **WHEN** un gestionnaire declenche l'action `Encaisser (cheque/espece)` depuis `Liste des commandes (gestionnaire)`
- **THEN** l'application ouvre directement l'ecran `Encaissement - Saisie paiements` sur la commande cible sans ecran de recherche intermediaire

#### Scenario: Demarrage d'encaissement depuis le detail de commande
- **WHEN** un gestionnaire declenche l'action `Encaisser (cheque/espece)` depuis `Detail de commande (gestionnaire)`
- **THEN** l'application ouvre directement l'ecran `Encaissement - Saisie paiements` sur cette commande

#### Scenario: Association de plusieurs paiements a une commande
- **WHEN** un gestionnaire enregistre des reglements pour une commande
- **THEN** il peut associer plusieurs lignes de paiement a la meme commande

#### Scenario: Affichage du beneficiaire de la commande dans la saisie paiements
- **WHEN** un gestionnaire consulte `Encaissement - Saisie paiements`
- **THEN** l'ecran affiche le beneficiaire de la commande pour confirmer le contexte d'encaissement

#### Scenario: Positionnement de l'action d'ajout de paiement
- **WHEN** un gestionnaire saisit des paiements dans `Encaissement - Saisie paiements`
- **THEN** l'action d'ajout de paiement est positionnee au plus proche du tableau des paiements

#### Scenario: Absence de bouton de recherche dans la saisie paiements
- **WHEN** un gestionnaire est sur `Encaissement - Saisie paiements` ouvert depuis une commande
- **THEN** l'ecran n'affiche pas de bouton de recherche de commande

#### Scenario: Saisie d'un paiement par cheque en contexte famille
- **WHEN** un gestionnaire ajoute un paiement par cheque
- **THEN** il saisit le montant, la banque et le titulaire du compte en contexte particulier/famille afin de permettre l'edition de bordereaux de cheque en fin de periode
