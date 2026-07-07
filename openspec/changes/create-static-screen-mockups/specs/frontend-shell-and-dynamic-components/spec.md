## MODIFIED Requirements

### Requirement: Le shell frontend MUST fournir un cadre applicatif stable
L’application MUST fournir un cadre stable comprenant authentification, navigation contextuelle, zone de contenu métier et retours utilisateur.  
Avant implémentation dynamique, ce cadre MUST être représenté par des maquettes statiques de référence consultables dans un dossier dédié.

#### Scenario: Rendu initial du shell
- **WHEN** un utilisateur ouvre l’application
- **THEN** l’interface affiche les zones principales nécessaires au parcours utilisateur

#### Scenario: Indicateurs commandes par statut sur la periode en cours
- **WHEN** un utilisateur consulte l'ecran `Shell de reference`
- **THEN** la zone de contenu metier affiche le nombre de commandes de la periode en cours pour chaque statut (`Brouillon`, `En attente de paiement`, `Paiement partiel`, `Paiement en cours`, `Confirmee`, `Annulee`) et n'affiche pas d'indicateurs `Demandes en attente` ni `Alertes stock`

#### Scenario: Affichage de la periode en cours et de son etat
- **WHEN** un utilisateur consulte l'ecran `Shell de reference`
- **THEN** la maquette affiche la periode marquee `en cours` et son etat (`Ouverte` ou `Fermee`) pour contextualiser les indicateurs de commande

#### Scenario: Metriques de pilotage sur la periode en cours
- **WHEN** un utilisateur consulte l'ecran `Shell de reference`
- **THEN** la zone centrale affiche des metriques pertinentes de la periode en cours (au minimum `CA`, `Nb commandes`, `Panier moyen`) en complement de la repartition des statuts

#### Scenario: Ordre de grandeur du panier moyen coherent avec le parcours famille
- **WHEN** la maquette `Shell de reference` affiche les metriques de la periode en cours
- **THEN** la valeur de `Panier moyen` est representee avec un ordre de grandeur realiste pour le contexte famille (autour de 50 EUR, et non de plusieurs centaines ou milliers d'euros)

#### Scenario: Recapitulatif des periodes passees
- **WHEN** un utilisateur consulte l'ecran `Shell de reference`
- **THEN** la maquette affiche un recapitulatif des periodes passees (au minimum `CA` et `Nb commandes` par periode) pour comparer la periode en cours avec l'historique

#### Scenario: Référentiel visuel de shell en phase de réécriture
- **WHEN** l’équipe prépare la réécriture de l’interface
- **THEN** elle dispose d’une maquette statique du shell, consultable dans l’espace dédié des maquettes, comme référence de validation

#### Scenario: Navigation shell adaptee au role famille pour les commandes
- **WHEN** un utilisateur famille navigue dans le shell maquette
- **THEN** il accede a la liste de ses propres commandes et aux ecrans de creation et detail de commande

#### Scenario: Navigation shell adaptee au role gestionnaire pour le referentiel
- **WHEN** un utilisateur gestionnaire navigue dans le shell maquette
- **THEN** il accede aux ecrans periodes, articles, tarifs, stock et consolidation

#### Scenario: Navigation shell adaptee au role gestionnaire pour le pilotage periodes/tarifs
- **WHEN** un utilisateur gestionnaire suit le parcours periodes et tarifs
- **THEN** il peut acceder aux ecrans de detail de periode, au sous-ecran tarifs d'un article, a l'edition de prix et a la reprise de tarifs vers la periode en cours

#### Scenario: Navigation shell adaptee aux regles de disponibilite article
- **WHEN** un utilisateur gestionnaire configure la disponibilite d'un article
- **THEN** les parcours de creation de commande et de gestion de stock refletent les filtres de disponibilite commandes/stock

#### Scenario: Navigation d'entree famille avec creation de compte
- **WHEN** un utilisateur famille consulte les parcours d'entree
- **THEN** il accede a un ecran dedie de creation de compte en plus de connexion, activation et recuperation d'acces

#### Scenario: Navigation shell gestionnaire pour l'encaissement commande
- **WHEN** un gestionnaire doit enregistrer des paiements de commande
- **THEN** il suit un parcours dedie comprenant recherche de commande puis saisie d'un ou plusieurs paiements (cheque ou espece)

#### Scenario: Navigation shell famille pour la commande de fournitures scolaires
- **WHEN** une famille prepare une commande
- **THEN** le shell maquette donne acces a un formulaire de commande en plusieurs etapes: validation contact, saisie enfants, selection des fournitures, validation de commande et choix du mode de paiement

#### Scenario: Navigation shell gestionnaire pour la cotisation de periode
- **WHEN** un gestionnaire pilote une periode
- **THEN** il accede a la configuration de la cotisation de la periode (1er enfant, 2e enfant, 3e et suivants)
