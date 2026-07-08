## Purpose

Définir les règles fonctionnelles cibles des écrans de référence frontend, indépendamment de l’implémentation dynamique.

## Requirements

### Requirement: L'application MUST isoler le referentiel d'ecrans de reference dans un module dedie
Le referentiel d'ecrans de reference MUST etre stocke dans un module dedie separe du code metier executable afin de distinguer clairement les ecrans de cadrage et les composants applicatifs dynamiques.

#### Scenario: Organisation des ecrans de reference dans un espace dedie
- **WHEN** l'equipe consulte l'arborescence du projet pour travailler sur les ecrans de reference
- **THEN** tous les ecrans de reference et leurs assets sont regroupes dans le module dedie

### Requirement: L'application MUST proposer une navigation liste-a-apercu des ecrans
Le module de consultation des ecrans MUST afficher une liste complete des ecrans disponibles sur la gauche et MUST afficher l'apercu de l'ecran selectionne sur la droite.  
La liste de navigation MUST disposer d'un scroll vertical independant du panneau d'apercu pour faciliter la navigation entre ecrans.

#### Scenario: Selection d'un ecran depuis la liste gauche
- **WHEN** un utilisateur selectionne un ecran dans la liste de navigation gauche
- **THEN** le panneau d'apercu droit affiche l'ecran correspondant

#### Scenario: Scroll independant de la liste d'ecrans
- **WHEN** un utilisateur fait defiler la liste de navigation gauche
- **THEN** seul le panneau de liste se deplace et l'apercu de l'ecran selectionne reste visible

### Requirement: L'application MUST permettre une consultation hors dependance backend metier
Les ecrans de reference MUST etre consultables localement, sans appel aux services metier ni dependance a des donnees dynamiques.

#### Scenario: Consultation hors contexte backend
- **WHEN** un utilisateur ouvre le module de consultation des ecrans sans backend operationnel
- **THEN** les ecrans de reference restent consultables et navigables

### Requirement: L'interface MUST appliquer un style neutre et lisible
L'application MUST appliquer un style visuel sobre, coherent et non rebutant afin de prioriser la comprehension fonctionnelle.

#### Scenario: Revue visuelle transverse des écrans
- **WHEN** plusieurs ecrans sont consultes dans une meme session
- **THEN** ils présentent une base de style homogène, neutre et lisible

### Requirement: La navigation MUST regrouper les ecrans par role utilisateur
L'application MUST regrouper les ecrans par role utilisateur (`famille`, `gestionnaire`, `admin`) et MUST permettre qu'un meme ecran apparaisse dans plusieurs groupes de role si necessaire.

#### Scenario: Ecran partage entre plusieurs roles
- **WHEN** un ecran est declare pour plusieurs roles dans le manifest
- **THEN** cet ecran est visible dans chaque groupe de role concerne dans la navigation

### Requirement: Le parcours commandes famille MUST couvrir liste, creation et detail
L'application MUST presenter, pour le role famille, une liste de ses propres commandes ainsi qu'un acces aux ecrans de creation et de detail de commande.

#### Scenario: Consultation des commandes par un utilisateur famille
- **WHEN** un utilisateur consulte l'application avec le role famille
- **THEN** il dispose d'un ecran "mes commandes" limite a ses propres commandes et des ecrans de creation et detail de commande

### Requirement: Le domaine gestionnaire MUST inclure periodes, articles, tarifs, stock et consolidation
L'application MUST rattacher les ecrans periodes, articles, tarifs, stock et consolidation au role gestionnaire.

#### Scenario: Consultation des ecrans de gestion de referentiel
- **WHEN** un utilisateur consulte l'application avec le role gestionnaire
- **THEN** il trouve les ecrans periodes, articles, tarifs, stock et consolidation dans son groupe de navigation

### Requirement: Le domaine articles MUST separer listing et creation
L'application MUST proposer un ecran de listing des articles et un ecran distinct de creation d'article.

#### Scenario: Revue du parcours articles
- **WHEN** un utilisateur consulte le domaine articles
- **THEN** il peut ouvrir un ecran de listing des articles et un ecran de creation d'article separes

### Requirement: Les tarifs MUST etre un sous-ecran d'un article
L'application MUST presenter les tarifs dans un sous-ecran rattache a un article, avec la liste des prix de cet article selon les periodes.

#### Scenario: Consultation des tarifs depuis un article
- **WHEN** un utilisateur ouvre le sous-ecran tarifs depuis la fiche d'un article
- **THEN** il visualise les prix de cet article sur plusieurs periodes

#### Scenario: Tri des periodes par recence
- **WHEN** un utilisateur consulte la liste des tarifs d'un article
- **THEN** les periodes sont triees par date decroissante (les plus recentes en premier)

### Requirement: Le sous-ecran tarifs MUST utiliser un prix unique TTC par periode
L'application MUST presenter un prix TTC unique par article et par periode, sans distinction de prix standard ou prix reseau.

#### Scenario: Lecture d'un tarif de periode
- **WHEN** un utilisateur consulte les tarifs d'un article
- **THEN** chaque ligne de periode affiche un seul prix TTC (et pas de colonnes standard/reseau)

### Requirement: Le sous-ecran tarifs MUST supporter deux modes de formulation du prix
L'application MUST permettre de formuler le prix d'un article soit par saisie directe du prix TTC, soit par prix fournisseur HT avec marge en pourcentage, donnant le prix TTC. Pour chaque periode, le mode de calcul choisi MUST etre persiste en plus du total TTC.

#### Scenario: Formulation en TTC direct
- **WHEN** un utilisateur choisit le mode TTC direct
- **THEN** il saisit directement le prix TTC de la periode cible

#### Scenario: Formulation HT fournisseur + marge
- **WHEN** un utilisateur choisit le mode HT fournisseur + marge
- **THEN** il saisit le prix fournisseur HT et la marge en pourcentage, et l'interface affiche le prix TTC correspondant

#### Scenario: Persistance du mode de calcul et du total TTC
- **WHEN** un utilisateur enregistre un prix d'article pour une periode
- **THEN** la periode conserve le mode de calcul utilise ainsi que le total TTC enregistres pour les consultations suivantes

### Requirement: Le sous-ecran tarifs MUST proposer un ecran de modification de prix par periode
L'application MUST inclure, depuis le sous-ecran tarifs de l'article, un ecran dedie a la modification du prix de cet article pour une periode choisie.

#### Scenario: Edition d'un prix pour la periode active
- **WHEN** un utilisateur ouvre l'edition de prix d'un article
- **THEN** il choisit une periode cible (sans date d'effet) et modifie le prix selon l'un des deux modes de formulation

#### Scenario: Edition sans motif de modification
- **WHEN** un utilisateur modifie un prix d'article
- **THEN** l'interface ne demande pas de champ "motif de modification"

### Requirement: Les tableaux des ecrans de listing MUST etre responsives
Tous les ecrans de listing qui presentent des donnees tabulaires MUST rester lisibles et exploitables en vue telephone comme en vue desktop.

#### Scenario: Consultation d'un listing tabulaire en vue telephone
- **WHEN** un utilisateur consulte un ecran de listing sur telephone
- **THEN** l'interface presente les donnees de maniere responsive avec conservation des informations essentielles et des actions principales

#### Scenario: Consultation d'un listing tabulaire en vue desktop
- **WHEN** un utilisateur consulte un ecran de listing sur desktop
- **THEN** l'interface affiche une presentation tabulaire complete et alignee avec les memes informations metier

### Requirement: Le domaine periodes MUST proposer un ecran detaille de periode
L'application MUST inclure un ecran detail de periode consultable depuis la liste des periodes et MUST y exposer une action explicite de modification de la periode.

#### Scenario: Consultation du detail d'une periode
- **WHEN** un utilisateur ouvre le detail d'une periode
- **THEN** il voit les informations cles de cette periode et ses indicateurs de pilotage

#### Scenario: Action de modification depuis le detail de periode
- **WHEN** un utilisateur consulte `Periodes - Detail`
- **THEN** l'interface affiche un bouton de modification permettant d'ouvrir `Periodes - Modification`

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

### Requirement: Le domaine periodes MUST permettre la reprise des tarifs d'une periode precedente
L'application MUST exposer une action pour appliquer les tarifs d'une periode precedente sur la periode en cours.

#### Scenario: Reprise des tarifs precedents
- **WHEN** un utilisateur lance l'action de reprise de tarifs
- **THEN** l'interface affiche l'operation de copie des tarifs d'une periode source vers la periode en cours

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

### Requirement: Le domaine articles MUST definir la structure de l'article
L'application MUST presenter un article compose d'un code de reference, d'un libelle et d'une marque.

#### Scenario: Consultation de la fiche article
- **WHEN** un utilisateur consulte les ecrans de listing ou creation d'article
- **THEN** les informations article visibles sont code de reference, libelle et marque

### Requirement: Le listing articles MUST proposer une recherche multicriteres
L'ecran `Articles - Listing` MUST proposer une zone de recherche permettant de filtrer les lignes du tableau sur le libelle de l'article, sa reference et sa marque.

#### Scenario: Recherche article depuis un champ unique
- **WHEN** un utilisateur saisit une valeur dans la zone de recherche de `Articles - Listing`
- **THEN** l'interface indique que la recherche est appliquee simultanement sur `libelle`, `reference article` et `marque`

### Requirement: La disponibilite commandes MUST filtrer les articles visibles en creation de commande
L'application MUST permettre de rendre un article disponible ou indisponible aux commandes, et MUST refleter que les articles indisponibles ne sont pas visibles lors de la creation d'une commande.

#### Scenario: Article indisponible aux commandes
- **WHEN** un article est marque indisponible aux commandes
- **THEN** il n'apparait pas dans la selection d'articles de la creation de commande

### Requirement: La disponibilite stock MUST filtrer les articles visibles en gestion de stock
L'application MUST permettre de rendre un article disponible ou indisponible dans le stock, et MUST refleter que les articles indisponibles ne sont pas visibles dans l'inventaire.

#### Scenario: Article indisponible dans le stock
- **WHEN** un article est marque indisponible dans le stock
- **THEN** il n'apparait pas dans la vue de gestion du stock (inventaire)

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

### Requirement: Le role famille MUST inclure un ecran dedie de creation de compte
L'application MUST inclure, dans les parcours d'entree du role famille, un ecran de creation de compte distinct des ecrans de connexion, activation et recuperation d'acces.

#### Scenario: Consultation des parcours d'entree famille
- **WHEN** un utilisateur consulte les ecrans d'entree du role famille
- **THEN** il peut ouvrir un ecran "Creation de compte"

### Requirement: Les commandes MUST utiliser le referentiel de statuts defini
Les ecrans de commandes MUST utiliser uniquement les statuts suivants: `Brouillon`, `En attente de paiement`, `Paiement partiel`, `Paiement en cours`, `Confirmee`, `Annulee`, avec les significations metier associees.

#### Scenario: Consultation d'une commande en brouillon
- **WHEN** une commande est creee mais non validee
- **THEN** son statut affiche est `Brouillon`

#### Scenario: Consultation d'une commande validee non reglee
- **WHEN** une commande est validee par le client sans paiement recu
- **THEN** son statut affiche est `En attente de paiement`

#### Scenario: Consultation d'une commande avec reglement incomplet
- **WHEN** une partie du montant est reglee
- **THEN** son statut affiche est `Paiement partiel`

#### Scenario: Consultation d'une commande avec paiement CB en traitement
- **WHEN** un paiement CB est en cours de traitement
- **THEN** son statut affiche est `Paiement en cours`

#### Scenario: Consultation d'une commande reglee
- **WHEN** le paiement est valide
- **THEN** son statut affiche est `Confirmee`

#### Scenario: Consultation d'une commande annulee
- **WHEN** une commande est annulee
- **THEN** son statut affiche est `Annulee`

#### Scenario: Annulation autorisee avant paiement
- **WHEN** une commande est en statut `Brouillon` ou `En attente de paiement` sans paiement enregistre
- **THEN** l'interface affiche une action d'annulation disponible

#### Scenario: Annulation refusee apres paiement
- **WHEN** au moins un paiement est enregistre pour une commande
- **THEN** l'interface indique que l'annulation de la commande n'est plus possible

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

### Requirement: Le formulaire de creation de commande famille MUST refleter le parcours fournitures scolaires
L'application MUST presenter un formulaire de commande famille en plusieurs etapes correspondant au parcours reel: validation contact, saisie des enfants, selection des fournitures, validation de commande, puis choix du mode de paiement.

#### Scenario: Etape 1 validation contact
- **WHEN** une famille commence la creation de commande
- **THEN** la premiere etape demande la validation de l'email et du numero de telephone

#### Scenario: Saisie des enfants concernes
- **WHEN** une famille cree une commande
- **THEN** l'etape enfants demande le nombre d'enfants concernes ainsi que nom, prenom et niveau pour chaque enfant

#### Scenario: Etape enfants explicite et orientee formulaire
- **WHEN** une famille consulte l'etape 2 de creation de commande
- **THEN** le titre affiche est `Etape 2 - Enfants concernes par la commande` et les donnees enfants sont presentees sous forme de zones de saisie par enfant

#### Scenario: Navigation etape 2 vers etapes precedente/suivante
- **WHEN** une famille complete l'etape 2
- **THEN** l'interface affiche des boutons de passage vers l'etape precedente et l'etape suivante

#### Scenario: Pre-remplissage des enfants depuis la commande precedente
- **WHEN** une famille dispose d'une commande precedente
- **THEN** l'etape enfants peut pre-remplir les informations des enfants a partir de la commande precedente via un texte indicatif (sans bouton d'action dedie)

#### Scenario: Limite du nombre d'enfants
- **WHEN** une famille renseigne le nombre d'enfants concernes
- **THEN** la saisie autorise un maximum de 8 enfants

#### Scenario: Coherence entre nombre d'enfants et cartes de saisie affichees
- **WHEN** l'etape 2 affiche deux cartes enfants a renseigner
- **THEN** la valeur selectionnee du nombre d'enfants est `2`

#### Scenario: Libelle niveau contextualise par periode
- **WHEN** une famille renseigne le niveau d'un enfant
- **THEN** le champ affiche le contexte `Niveau (sur la periode <nom-periode-en-cours>)`

#### Scenario: Absence de niveau principal
- **WHEN** une famille renseigne les enfants
- **THEN** la saisie ne demande pas de niveau principal unique et conserve un niveau par enfant

#### Scenario: Choix des quantites par fourniture de reference
- **WHEN** une famille consulte la liste des fournitures
- **THEN** l'etape fournitures affiche toute la liste avec la quantite recommandee par niveau selon les enfants saisis, et permet de saisir la quantite souhaitee

#### Scenario: Quantites saisissables sur l'etape fournitures
- **WHEN** une famille renseigne l'etape 3
- **THEN** chaque ligne de fourniture expose un champ de saisie de quantite commandee

#### Scenario: Affichage du prix TTC des articles
- **WHEN** une famille consulte les lignes article de l'etape 3
- **THEN** chaque article correspondant affiche son prix TTC en plus du champ de quantite

#### Scenario: Cas majoritaire d'une seule reference article
- **WHEN** une famille consulte les fournitures de l'etape 3
- **THEN** la majorite des fournitures affiche une seule reference article avec son champ de quantite

#### Scenario: Representation d'une liste volumineuse de fournitures
- **WHEN** la liste de fournitures contient un volume courant de 35 a 40 references
- **THEN** l'interface affiche explicitement ce volume et la logique de saisie reste representative d'une liste longue

#### Scenario: Mode concis en vue telephone pour l'etape 3
- **WHEN** l'interface est consultee en vue telephone
- **THEN** l'etape 3 propose une presentation concise en cartes, tout en conservant la saisie des quantites, les recommandations et les prix TTC

#### Scenario: Plusieurs articles pour une fourniture de reference
- **WHEN** une fourniture de reference dispose de plusieurs articles correspondants
- **THEN** le formulaire permet de saisir des quantites pour une ou plusieurs options d'article en parallele (sans choix exclusif de type liste deroulante unique)

#### Scenario: Exemples de fournitures a references multiples
- **WHEN** une fourniture est dans les cas multi-references
- **THEN** l'interface illustre notamment `Regle plate 30cm` (Regle en bois 30cm, Regle plate - 30 cm - Incassable, Kit du gaucher) et `Lot de surligneurs de couleurs` (lot de 6 + surligneurs unitaires orange/jaune/rose/vert)

#### Scenario: Recommandations simplifiees pour exemples multi-references
- **WHEN** l'interface illustre les exemples `Regle plate 30cm` et `Lot de surligneurs de couleurs`
- **THEN** les quantites recommandees par niveau sont fixees a 1 pour chaque niveau afin de rester lisibles

### Requirement: La cotisation MUST etre configuree par periode avec degressivite
L'application MUST afficher la configuration du prix de cotisation par periode avec une grille degressive: prix pour le 1er enfant, prix pour le 2e enfant, prix pour le 3e et suivants.

#### Scenario: Configuration de cotisation en detail de periode
- **WHEN** un gestionnaire consulte le detail d'une periode
- **THEN** il visualise et pilote les montants de cotisation (1er, 2e, 3e et suivants)

### Requirement: La commande famille MUST integrer la cotisation
L'ecran de creation de commande MUST inclure le montant de la cotisation dans le total de commande.

#### Scenario: Affichage du cout de cotisation au choix du nombre d'enfants
- **WHEN** le nombre d'enfants est selectionne dans le formulaire
- **THEN** le cout de cotisation correspondant est affiche dans la zone de saisie des enfants

#### Scenario: Total commande avec cotisation
- **WHEN** une famille finalise sa commande
- **THEN** le recapitulatif affiche le sous-total articles, le montant de cotisation et le total commande incluant la cotisation

#### Scenario: Etape 4 validation de commande
- **WHEN** une famille atteint l'etape 4 de creation
- **THEN** l'interface affiche une etape `Etape 4 - Validation de votre commande`

#### Scenario: Recapitulatif final limite aux articles choisis
- **WHEN** la famille consulte l'etape 4
- **THEN** seuls les articles choisis sont affiches avec quantite, montant unitaire TTC, total par ligne, et total global de commande

#### Scenario: Etape 5 choix du mode de paiement
- **WHEN** la famille poursuit apres l'etape 4
- **THEN** l'interface affiche une etape `Etape 5 - Mode de paiement`

#### Scenario: Modes de paiement disponibles a l'etape 5
- **WHEN** la famille consulte l'etape 5
- **THEN** elle peut choisir entre `Paiement CB (via HelloAsso)`, `Paiement par cheque` et `Paiement en espece`

#### Scenario: Notice explicative pour paiement par cheque
- **WHEN** la famille choisit le paiement par cheque
- **THEN** l'interface affiche une notice succincte indiquant le depot du cheque avec nom de l'enfant, niveau et numero de commande au dos, ainsi que le bon de commande recu par mail et imprime

#### Scenario: Notice explicative pour paiement en espece
- **WHEN** la famille choisit le paiement en espece
- **THEN** l'interface affiche une notice succincte indiquant que l'appoint est obligatoire et que le bon de commande recu par mail doit etre imprime

#### Scenario: Detail commande coherent avec les lignes de fournitures choisies
- **WHEN** un utilisateur consulte l'ecran `Detail commande` d'une commande famille
- **THEN** les lignes de commande reprennent des fournitures realistes issues de la creation de commande (article choisi, quantite, PU TTC, total ligne)
