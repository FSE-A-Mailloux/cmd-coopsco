## Purpose

Définir les règles fonctionnelles cibles des écrans de référence frontend, indépendamment de l’implémentation dynamique.

## Requirements

### Requirement: L'application MUST isoler le référentiel d'écrans de référence dans un module dédié
Le référentiel d'écrans de référence MUST être stocké dans un module dédié séparé du code métier exécutable afin de distinguer clairement les écrans de cadrage et les composants applicatifs dynamiques.

#### Scenario: Organisation des écrans de référence dans un espace dédié
- **WHEN** l'équipe consulte l'arborescence du projet pour travailler sur les écrans de référence
- **THEN** tous les écrans de référence et leurs assets sont regroupés dans le module dédié

### Requirement: L'application MUST proposer une navigation liste-à-aperçu des écrans
Le module de consultation des écrans MUST afficher une liste complète des écrans disponibles sur la gauche et MUST afficher l'aperçu de l'écran sélectionné sur la droite.\
La liste de navigation MUST disposer d'un scroll vertical indépendant du panneau d'aperçu pour faciliter la navigation entre écrans.

#### Scenario: Sélection d'un écran depuis la liste gauche
- **WHEN** un utilisateur sélectionne un écran dans la liste de navigation gauche
- **THEN** le panneau d'aperçu droit affiche l'écran correspondant

#### Scenario: Scroll indépendant de la liste d'écrans
- **WHEN** un utilisateur fait défiler la liste de navigation gauche
- **THEN** seul le panneau de liste se déplace et l'aperçu de l'écran sélectionné reste visible

### Requirement: L'application MUST permettre une consultation hors dépendance backend métier
Les écrans de référence MUST être consultables localement, sans appel aux services métier ni dépendance à des données dynamiques.

#### Scenario: Consultation hors contexte backend
- **WHEN** un utilisateur ouvre le module de consultation des écrans sans backend opérationnel
- **THEN** les écrans de référence restent consultables et navigables

### Requirement: Le référentiel d'écrans de référence MUST être consultable à distance via une URL stable
Le module de maquettes MUST être publiable sur GitHub Pages afin de permettre la consultation distante des écrans de référence pendant les ateliers et revues asynchrones.

#### Scenario: Consultation distante du workbench maquettes
- **WHEN** un participant atelier ouvre l'URL GitHub Pages communiquée par l'équipe
- **THEN** il accède au workbench maquettes et peut naviguer dans le catalogue d'écrans sans installation locale

### Requirement: L'interface MUST appliquer un style neutre et lisible
L'application MUST appliquer un style visuel sobre, cohérent et non rebutant afin de prioriser la compréhension fonctionnelle.

#### Scenario: Revue visuelle transverse des écrans
- **WHEN** plusieurs écrans sont consultés dans une même session
- **THEN** ils présentent une base de style homogène, neutre et lisible

### Requirement: La navigation MUST regrouper les écrans par rôle utilisateur
L'application MUST regrouper les écrans par rôle utilisateur (`famille`, `gestionnaire`, `admin`) et MUST permettre qu'un même écran apparaisse dans plusieurs groupes de rôle si nécessaire.

#### Scenario: Écran partagé entre plusieurs rôles
- **WHEN** un écran est déclaré pour plusieurs rôles dans le manifest
- **THEN** cet écran est visible dans chaque groupe de rôle concerné dans la navigation

### Requirement: Le parcours commandes famille MUST couvrir liste, création et détail
L'application MUST présenter, pour le rôle famille, une liste de ses propres commandes ainsi qu'un accès aux écrans de création et de détail de commande.

#### Scenario: Consultation des commandes par un utilisateur famille
- **WHEN** un utilisateur consulte l'application avec le rôle famille
- **THEN** il dispose d'un écran "mes commandes" limité à ses propres commandes et des écrans de création et détail de commande

### Requirement: Le domaine gestionnaire MUST inclure périodes, articles, tarifs, stock et consolidation
L'application MUST rattacher les écrans périodes, articles, tarifs, stock et consolidation au rôle gestionnaire.

#### Scenario: Consultation des écrans de gestion de référentiel
- **WHEN** un utilisateur consulte l'application avec le rôle gestionnaire
- **THEN** il trouve les écrans périodes, articles, tarifs, stock et consolidation dans son groupe de navigation

### Requirement: Le domaine articles MUST séparer listing et création
L'application MUST proposer un écran de listing des articles et un écran distinct de création d'article.

#### Scenario: Revue du parcours articles
- **WHEN** un utilisateur consulte le domaine articles
- **THEN** il peut ouvrir un écran de listing des articles et un écran de création d'article séparés

### Requirement: Les tarifs MUST être un sous-écran d'un article
L'application MUST présenter les tarifs dans un sous-écran rattaché à un article, avec la liste des prix de cet article selon les périodes.

#### Scenario: Consultation des tarifs depuis un article
- **WHEN** un utilisateur ouvre le sous-écran tarifs depuis la fiche d'un article
- **THEN** il visualise les prix de cet article sur plusieurs périodes

#### Scenario: Tri des périodes par récence
- **WHEN** un utilisateur consulte la liste des tarifs d'un article
- **THEN** les périodes sont triées par date décroissante (les plus récentes en premier)

### Requirement: Le sous-écran tarifs MUST utiliser un prix unique TTC par période
L'application MUST présenter un prix TTC unique par article et par période, sans distinction de prix standard ou prix réseau.

#### Scenario: Lecture d'un tarif de période
- **WHEN** un utilisateur consulte les tarifs d'un article
- **THEN** chaque ligne de période affiche un seul prix TTC (et pas de colonnes standard/réseau)

### Requirement: Le sous-écran tarifs MUST supporter deux modes de formulation du prix
L'application MUST permettre de formuler le prix d'un article soit par saisie directe du prix TTC, soit par prix fournisseur HT utilisé comme prix d'achat avec marge en pourcentage, donnant le prix TTC. Les écrans MUST NOT afficher de champ, filtre, action ou libellé demandant l'identité d'un fournisseur. Pour chaque période, le mode de calcul choisi MUST être persisté en plus du total TTC.

#### Scenario: Formulation en TTC direct
- **WHEN** un utilisateur choisit le mode TTC direct
- **THEN** il saisit directement le prix TTC de la période cible

#### Scenario: Formulation HT fournisseur + marge
- **WHEN** un utilisateur choisit le mode HT fournisseur + marge
- **THEN** il saisit le prix fournisseur HT et la marge en pourcentage, et l'interface affiche le prix TTC correspondant
- **AND** aucun fournisseur n'est demandé

#### Scenario: Persistance du mode de calcul et du total TTC
- **WHEN** un utilisateur enregistre un prix d'article pour une période
- **THEN** la période conserve le mode de calcul utilisé ainsi que le total TTC enregistrés pour les consultations suivantes

### Requirement: Le sous-écran tarifs MUST proposer un écran de modification de prix par période
L'application MUST inclure, depuis le sous-écran tarifs de l'article, un écran dédié à la modification du prix de cet article pour une période choisie.

#### Scenario: Édition d'un prix pour la période active
- **WHEN** un utilisateur ouvre l'édition de prix d'un article
- **THEN** il choisit une période cible (sans date d'effet) et modifie le prix selon l'un des deux modes de formulation

#### Scenario: Édition sans motif de modification
- **WHEN** un utilisateur modifie un prix d'article
- **THEN** l'interface ne demande pas de champ "motif de modification"

### Requirement: Les tableaux des écrans de listing MUST être responsives
Tous les écrans de listing qui présentent des données tabulaires MUST rester lisibles et exploitables en vue téléphone comme en vue desktop.

#### Scenario: Consultation d'un listing tabulaire en vue téléphone
- **WHEN** un utilisateur consulte un écran de listing sur téléphone
- **THEN** l'interface présente les données de manière responsive avec conservation des informations essentielles et des actions principales

#### Scenario: Consultation d'un listing tabulaire en vue desktop
- **WHEN** un utilisateur consulte un écran de listing sur desktop
- **THEN** l'interface affiche une présentation tabulaire complète et alignée avec les mêmes informations métier

### Requirement: Le domaine périodes MUST proposer un écran détaillé de période
L'application MUST inclure un écran détail de période consultable depuis la liste des périodes et MUST y exposer une action explicite de modification de la période.

#### Scenario: Consultation du détail d'une période
- **WHEN** un utilisateur ouvre le détail d'une période
- **THEN** il voit les informations clés de cette période et ses indicateurs de pilotage

#### Scenario: Action de modification depuis le détail de période
- **WHEN** un utilisateur consulte `Périodes - Détail`
- **THEN** l'interface affiche un bouton de modification permettant d'ouvrir `Périodes - Modification`

### Requirement: Le domaine périodes MUST inclure les écrans de création et de modification
L'application MUST proposer un écran `Périodes - Création` et un écran `Périodes - Modification` en complément du listing et du détail de période.\
Les écrans de création et de modification MUST inclure la saisie des cotisations (`1er enfant`, `2e enfant`, `3e enfant et suivants`).\
L'écran de création MUST afficher la reprise de tarifs comme recommandée pour éviter une période avec tarifs vides.\
Les écrans périodes (détail, création, modification) MUST NOT afficher de champ `Code période`.

#### Scenario: Ouverture de l'écran de création de période
- **WHEN** un gestionnaire déclenche l'action de création depuis `Périodes - Listing et actions`
- **THEN** l'interface ouvre l'écran `Périodes - Création`

#### Scenario: Ouverture de l'écran de modification de période
- **WHEN** un gestionnaire déclenche l'action de modification sur une période existante
- **THEN** l'interface ouvre l'écran `Périodes - Modification`

#### Scenario: Saisie des cotisations dans la création de période
- **WHEN** un gestionnaire ouvre `Périodes - Création`
- **THEN** il renseigne les montants de cotisation pour `1er enfant`, `2e enfant`, et `3e enfant et suivants`

#### Scenario: Saisie des cotisations dans la modification de période
- **WHEN** un gestionnaire ouvre `Périodes - Modification`
- **THEN** il modifie les montants de cotisation pour `1er enfant`, `2e enfant`, et `3e enfant et suivants`

#### Scenario: Reprise des tarifs recommandée à la création de période
- **WHEN** un gestionnaire ouvre `Périodes - Création`
- **THEN** l'interface affiche un libellé de reprise tarifs en mode `recommandée` avec un message explicitant le risque de tarifs vides sans reprise

#### Scenario: Absence de code période dans les écrans périodes
- **WHEN** un utilisateur consulte `Périodes - Détail`, `Périodes - Création` ou `Périodes - Modification`
- **THEN** l'interface n'affiche pas de champ `Code période`

### Requirement: Le domaine périodes MUST permettre la reprise des tarifs d'une période précédente
L'application MUST exposer une action pour appliquer les tarifs d'une période précédente sur la période en cours.

#### Scenario: Reprise des tarifs précédents
- **WHEN** un utilisateur lance l'action de reprise de tarifs
- **THEN** l'interface affiche l'opération de copie des tarifs d'une période source vers la période en cours

### Requirement: Le domaine périodes MUST proposer des actions d'état
L'application MUST exposer des actions pour ouvrir ou fermer une période aux commandes et pour la définir comme "période en cours".\
Dans `Périodes - Listing et actions`, seule la période en cours MUST afficher l'action `Ouvrir` active, une seule période MUST être marquée `en cours` à un instant donné, et seules les périodes non courantes MUST afficher l'action `Définir en cours` active.\
Le statut de période MUST être libellé `En cours` ou `Non en cours` (période passée ou en préparation), et l'état d'ouverture MUST être libellé `Ouverte` ou `Fermée`.\
Dans `Périodes - Modification`, le `Statut période` MUST être présenté avant `État d'ouverture`, sous forme de toggles, et le toggle d'ouverture MUST être inactif tant que la période n'est pas en cours.

#### Scenario: Pilotage du cycle de vie d'une période
- **WHEN** un utilisateur consulte la liste des périodes
- **THEN** il dispose d'actions explicites pour ouvrir, fermer et définir la période en cours

#### Scenario: Action ouvrir active uniquement sur la période en cours
- **WHEN** un utilisateur consulte `Périodes - Listing et actions`
- **THEN** le bouton `Ouvrir` est actif uniquement sur la ligne de la période marquée `en cours`

#### Scenario: Unicité de la période en cours dans le listing
- **WHEN** un utilisateur consulte `Périodes - Listing et actions`
- **THEN** une seule ligne est marquée `en cours`

#### Scenario: Action définir en cours active uniquement hors période courante
- **WHEN** un utilisateur consulte `Périodes - Listing et actions`
- **THEN** seules les lignes non marquées `en cours` affichent un bouton `Définir en cours` actif

#### Scenario: Libellés de statut et d'état sur les écrans périodes
- **WHEN** un utilisateur consulte les écrans de période
- **THEN** le statut de période est affiché avec `En cours` ou `Non en cours`, et l'état d'ouverture avec `Ouverte` ou `Fermée`

#### Scenario: Ordre et format des contrôles d'état en modification
- **WHEN** un utilisateur consulte `Périodes - Modification`
- **THEN** les contrôles d'état sont affichés en toggles avec `Statut période` avant `État d'ouverture`

#### Scenario: Ouverture bloquée hors période en cours en modification
- **WHEN** une période n'est pas marquée `en cours` dans `Périodes - Modification`
- **THEN** le contrôle `État d'ouverture` reste inactif et ne peut pas passer à `Ouverte`

### Requirement: Le domaine articles MUST définir la structure de l'article
L'application MUST présenter un article composé d'un code de référence, d'un libellé et d'une marque.

#### Scenario: Consultation de la fiche article
- **WHEN** un utilisateur consulte les écrans de listing ou création d'article
- **THEN** les informations article visibles sont code de référence, libellé et marque

### Requirement: Le listing articles MUST proposer une recherche multicritères
L'écran `Articles - Listing` MUST proposer une zone de recherche permettant de filtrer les lignes du tableau sur le libellé de l'article, sa référence et sa marque.

#### Scenario: Recherche article depuis un champ unique
- **WHEN** un utilisateur saisit une valeur dans la zone de recherche de `Articles - Listing`
- **THEN** l'interface indique que la recherche est appliquée simultanément sur `libellé`, `référence article` et `marque`

### Requirement: La disponibilité commandes MUST filtrer les articles visibles en création de commande
L'application MUST permettre de rendre un article disponible ou indisponible aux commandes, et MUST refléter que les articles indisponibles ne sont pas visibles lors de la création d'une commande.

#### Scenario: Article indisponible aux commandes
- **WHEN** un article est marqué indisponible aux commandes
- **THEN** il n'apparaît pas dans la sélection d'articles de la création de commande

### Requirement: La disponibilité stock MUST filtrer les articles visibles en gestion de stock
L'application MUST permettre de rendre un article disponible ou indisponible dans le stock, et MUST refléter que les articles indisponibles ne sont pas visibles dans l'inventaire.

#### Scenario: Article indisponible dans le stock
- **WHEN** un article est marqué indisponible dans le stock
- **THEN** il n'apparaît pas dans la vue de gestion du stock (inventaire)

### Requirement: L'écran Stock MUST afficher une synthèse recentrée sur les ruptures
L'écran `Stock` MUST afficher l'indicateur `articles en rupture`, et MUST NOT afficher les indicateurs `articles en alerte` ni `entrepôts suivis`.

#### Scenario: Consultation de la synthèse Stock
- **WHEN** un gestionnaire consulte l'écran `Stock`
- **THEN** la zone de synthèse affiche l'indicateur `articles en rupture`

#### Scenario: Absence d'indicateurs non retenus
- **WHEN** un gestionnaire consulte la zone de synthèse de l'écran `Stock`
- **THEN** les indicateurs `articles en alerte` et `entrepôts suivis` ne sont pas affichés

### Requirement: Le listing Stock MUST retirer la colonne seuil mini
Le tableau de l'écran `Stock` MUST présenter les informations article sans colonne `seuil mini`.

#### Scenario: Lecture des colonnes du listing Stock
- **WHEN** un gestionnaire lit l'en-tête du tableau `Stock`
- **THEN** la colonne `seuil mini` n'est pas présente

### Requirement: Le listing Stock MUST permettre la visualisation de la courbe de stock par article
Chaque ligne article du listing `Stock` MUST exposer une action pour visualiser l'évolution du stock de l'article aux dates de modification du stock.

#### Scenario: Accès à la courbe depuis un article du listing
- **WHEN** un gestionnaire sélectionne l'action de visualisation sur une ligne article du listing `Stock`
- **THEN** l'interface ouvre la visualisation de la courbe de stock de cet article, ordonnée selon les dates de modification du stock

### Requirement: Le listing Stock MUST permettre la mise à jour du disponible avec état calculé
Chaque article du listing `Stock` MUST pouvoir être modifié en précisant le nombre disponible uniquement.
L'état de stock affiché MUST être calculé automatiquement: `Rupture` lorsque le nombre disponible est égal à 0, sinon `Disponible`.

#### Scenario: Mise à jour du disponible d'un article
- **WHEN** un gestionnaire modifie un article dans l'écran `Stock`
- **THEN** l'interface permet la saisie du nombre disponible sans saisie manuelle de l'état
- **AND** l'état affiché est `Rupture` si le disponible vaut 0, sinon `Disponible`

### Requirement: L'écran Consolidation MUST représenter un snapshot par période
L'écran `Consolidation` MUST être centré sur la période en cours, affichée en libellé non éditable, et MUST présenter, à un instant donné, les données de stock, de besoin FSE, de quantité à commander et de prix d'achat nécessaires à la décision. Il MUST NOT présenter de colonne, filtre ou action de sélection d'un fournisseur.

#### Scenario: Consultation du snapshot courant
- **WHEN** un gestionnaire ouvre l'écran `Consolidation`
- **THEN** l'interface rappelle la période en cours via un libellé non modifiable et n'affiche pas de sélecteur de période
- **AND** l'interface affiche, par article, le stock disponible, la quantité commandée familles, la saisie du besoin FSE, la quantité à commander calculée et le prix d'achat
- **AND** aucune identité fournisseur n'est affichée

### Requirement: L'écran Consolidation MUST calculer la quantité à commander à partir du différentiel métier
Pour chaque article, l'écran `Consolidation` MUST calculer la quantité à commander selon la règle `max(0, quantité commandée familles + besoin FSE - stock disponible)`.

#### Scenario: Calcul de la quantité à commander d'un article
- **WHEN** un gestionnaire saisit ou modifie le besoin FSE d'un article
- **THEN** l'interface recalcule la quantité à commander avec la formule métier et applique un plancher à 0

### Requirement: L'écran Consolidation MUST permettre l'enregistrement et la consultation historique des snapshots
L'écran `Consolidation` MUST permettre d'enregistrer une consolidation et MUST permettre de consulter ultérieurement les consolidations enregistrées avec leur horodatage.

#### Scenario: Enregistrement d'une consolidation
- **WHEN** un gestionnaire enregistre la consolidation d'une période
- **THEN** la consolidation est stockée avec horodatage et devient consultable dans l'historique

### Requirement: L'écran Consolidation MUST afficher les indicateurs financiers d'estimation et MUST NOT afficher le taux de service
L'écran `Consolidation` MUST afficher `CA estimé`, `Marge estimée` et `Coût du stock prévu (besoin FSE)` sur la période en cours quand les prix fournisseur HT nécessaires, utilisés comme prix d'achat, sont disponibles, et MUST NOT afficher de taux de service.

#### Scenario: Affichage des indicateurs d'estimation
- **WHEN** un gestionnaire consulte la consolidation de la période en cours
- **THEN** l'interface affiche `CA estimé`, `Marge estimée` et `Coût du stock prévu (besoin FSE)` avec indication explicite si une partie des prix fournisseur HT est manquante
- **AND** l'interface n'affiche aucun KPI ni colonne `Taux service`

### Requirement: Le rôle famille MUST inclure un écran dédié de création de compte
L'application MUST inclure, dans les parcours d'entrée du rôle famille, un écran de création de compte distinct des écrans de connexion, activation et récupération d'accès.

#### Scenario: Consultation des parcours d'entrée famille
- **WHEN** un utilisateur consulte les écrans d'entrée du rôle famille
- **THEN** il peut ouvrir un écran "Création de compte"

### Requirement: L'écran d'activation MUST permettre de demander le renvoi du code de validation
Le parcours d'activation MUST proposer une action de renvoi du code pour le compte en cours de validation et MUST présenter un retour explicite sur le résultat de la demande.

#### Scenario: Demande de renvoi depuis l'écran d'activation
- **WHEN** l'utilisateur choisit l'action de renvoi du code
- **THEN** l'interface demande le renvoi du code associé au compte en cours de validation
- **AND** elle indique que le nouveau code a été envoyé à l'adresse courriel associée au compte lorsque l'envoi réussit

#### Scenario: Échec du renvoi depuis l'écran d'activation
- **WHEN** le renvoi du code échoue
- **THEN** l'interface affiche un message explicite d'échec et ne présente pas la demande comme réussie

### Requirement: Les commandes MUST utiliser le référentiel de statuts défini
Les écrans de commandes MUST utiliser uniquement les statuts suivants: `Brouillon`, `En attente de paiement`, `Paiement partiel`, `Paiement en cours`, `Confirmée`, `Annulée`, avec les significations métier associées.

#### Scenario: Consultation d'une commande en brouillon
- **WHEN** une commande est créée mais non validée
- **THEN** son statut affiché est `Brouillon`

#### Scenario: Consultation d'une commande validée non réglée
- **WHEN** une commande est validée par le client sans paiement reçu
- **THEN** son statut affiché est `En attente de paiement`

#### Scenario: Consultation d'une commande avec règlement incomplet
- **WHEN** une partie du montant est réglée
- **THEN** son statut affiché est `Paiement partiel`

#### Scenario: Consultation d'une commande avec paiement CB en traitement
- **WHEN** un paiement CB est en cours de traitement
- **THEN** son statut affiché est `Paiement en cours`

#### Scenario: Consultation d'une commande réglée
- **WHEN** le paiement est validé
- **THEN** son statut affiché est `Confirmée`

#### Scenario: Consultation d'une commande annulée
- **WHEN** une commande est annulée
- **THEN** son statut affiché est `Annulée`

#### Scenario: Annulation autorisée avant paiement
- **WHEN** une commande est en statut `Brouillon` ou `En attente de paiement` sans paiement enregistré
- **THEN** l'interface affiche une action d'annulation disponible

#### Scenario: Annulation après paiement disponible au gestionnaire habilité
- **WHEN** un gestionnaire habilité consulte une commande en statut `Paiement partiel` ou `Confirmée`
- **THEN** l'interface affiche une action d'annulation qui indique qu'un avoir sera émis

#### Scenario: Annulation refusée après paiement
- **WHEN** un membre d'une famille consulte une commande en statut `Paiement partiel` ou `Confirmée`
- **THEN** l'interface n'affiche pas d'action d'annulation

#### Scenario: Confirmation de l'annulation après paiement
- **WHEN** un gestionnaire habilité confirme l'annulation d'une commande avec paiement enregistré
- **THEN** l'interface affiche la confirmation de l'annulation et rend l'avoir téléchargeable

### Requirement: Les gestionnaires MUST disposer d'un parcours de remboursement manuel
Le détail d'une commande annulée avec avoir MUST présenter aux gestionnaires habilités le montant remboursé, le montant restant à rembourser et, si le solde est positif, une action de remboursement manuel. Le formulaire de remboursement MUST afficher le contexte de la commande et collecter le montant, le moyen, la date effective et la référence opérationnelle. Il MUST présenter le montant restant comme plafond et empêcher la soumission d'un montant nul, négatif ou supérieur au plafond connu. La validation serveur reste la source d'autorité en cas de solde modifié entre l'affichage du formulaire et sa soumission.

#### Scenario: Accès au remboursement depuis une commande éligible
- **WHEN** un gestionnaire consulte le détail d'une commande annulée avec avoir et un montant restant à rembourser
- **THEN** l'interface affiche l'action `Enregistrer un remboursement`

#### Scenario: Aucun solde à rembourser
- **WHEN** un gestionnaire consulte une commande annulée avec avoir dont le montant restant à rembourser est nul
- **THEN** l'interface affiche l'état entièrement remboursée et n'affiche pas d'action pour enregistrer un nouveau remboursement

#### Scenario: Détail de remboursement réservé au gestionnaire
- **WHEN** un membre d'une famille consulte le détail de sa commande annulée
- **THEN** l'interface ne présente ni les montants internes remboursés ou restant, ni l'historique des remboursements, ni l'action ou le formulaire de remboursement

#### Scenario: Saisie d'un remboursement manuel
- **WHEN** un gestionnaire ouvre le parcours de remboursement manuel
- **THEN** l'interface affiche le numéro de commande, le montant restant, le montant, le moyen de remboursement, la date et la référence à saisir

#### Scenario: Saisie au-delà du montant restant
- **WHEN** le gestionnaire saisit un montant nul, négatif ou supérieur au montant restant affiché
- **THEN** l'interface signale que la valeur est invalide et empêche la soumission, sans remplacer le contrôle du serveur

#### Scenario: Consultation des remboursements enregistrés
- **WHEN** un gestionnaire consulte une commande comportant des remboursements
- **THEN** l'interface affiche chaque remboursement avec son montant, son moyen, sa date, sa référence et son gestionnaire responsable

### Requirement: La liste des commandes gestionnaire MUST proposer une recherche multicritères en champ unique, un filtre période et un filtre statuts
L'écran `Liste des commandes (gestionnaire)` MUST proposer un champ de recherche unique qui interroge les informations famille (`email parent`, `nom/prénom parent`, `nom/prénom enfant`) et le `numéro de commande`, MUST permettre la sélection d'une période avec la `période en cours` présélectionnée par défaut, et MUST proposer un filtre de statuts en sélection multiple.\
Pour les commandes en statut `Confirmée`, l'écran MUST exposer une action d'encaissement chèque/espèce.

#### Scenario: Recherche multicritères via champ unique
- **WHEN** un gestionnaire saisit une valeur dans le champ de recherche unique
- **THEN** l'interface indique que la recherche est appliquée simultanément sur email, nom et prénom du parent et de l'enfant, ainsi que sur le numéro de commande

#### Scenario: Filtre période présélectionné sur la période en cours
- **WHEN** un gestionnaire ouvre l'écran `Liste des commandes (gestionnaire)`
- **THEN** le filtre `Période` affiche la période en cours par défaut, tout en permettant de sélectionner une période passée

#### Scenario: Filtre statuts en sélection multiple
- **WHEN** un gestionnaire choisit plusieurs statuts de commande dans le filtre de statuts
- **THEN** l'interface représente une sélection multiple de statuts appliquée au filtrage de la liste

#### Scenario: Action d'encaissement depuis la liste des commandes
- **WHEN** un gestionnaire consulte une commande en statut `Confirmée` dans `Liste des commandes (gestionnaire)`
- **THEN** l'interface affiche une action `Encaisser (chèque/espèce)` sur la commande

### Requirement: Les gestionnaires MUST disposer d'un parcours d'encaissement chèque/espèce
L'application MUST présenter un parcours gestionnaire dédié à l'encaissement déclenché depuis une commande existante (liste des commandes ou détail de commande), avec association d'un ou plusieurs paiements et formulaire de saisie des paiements chèque ou espèce contextualisé sur la commande sélectionnée.

#### Scenario: Démarrage d'encaissement depuis la liste des commandes
- **WHEN** un gestionnaire déclenche l'action `Encaisser (chèque/espèce)` depuis `Liste des commandes (gestionnaire)`
- **THEN** l'application ouvre directement l'écran `Encaissement - Saisie paiements` sur la commande cible sans écran de recherche intermédiaire

#### Scenario: Démarrage d'encaissement depuis le détail de commande
- **WHEN** un gestionnaire déclenche l'action `Encaisser (chèque/espèce)` depuis `Détail de commande (gestionnaire)`
- **THEN** l'application ouvre directement l'écran `Encaissement - Saisie paiements` sur cette commande

#### Scenario: Association de plusieurs paiements à une commande
- **WHEN** un gestionnaire enregistre des règlements pour une commande
- **THEN** il peut associer plusieurs lignes de paiement à la même commande

#### Scenario: Affichage du bénéficiaire de la commande dans la saisie paiements
- **WHEN** un gestionnaire consulte `Encaissement - Saisie paiements`
- **THEN** l'écran affiche le bénéficiaire de la commande pour confirmer le contexte d'encaissement

#### Scenario: Positionnement de l'action d'ajout de paiement
- **WHEN** un gestionnaire saisit des paiements dans `Encaissement - Saisie paiements`
- **THEN** l'action d'ajout de paiement est positionnée au plus proche du tableau des paiements

#### Scenario: Absence de bouton de recherche dans la saisie paiements
- **WHEN** un gestionnaire est sur `Encaissement - Saisie paiements` ouvert depuis une commande
- **THEN** l'écran n'affiche pas de bouton de recherche de commande

#### Scenario: Saisie d'un paiement par chèque en contexte famille
- **WHEN** un gestionnaire ajoute un paiement par chèque
- **THEN** il saisit le montant, la banque et le titulaire du compte en contexte particulier/famille afin de permettre l'édition de bordereaux de chèque en fin de période

### Requirement: Le formulaire de création de commande famille MUST refléter le parcours fournitures scolaires
L'application MUST présenter un formulaire de commande famille en plusieurs étapes correspondant au parcours réel: validation contact, saisie des enfants, sélection des fournitures, validation de commande, puis choix du mode de paiement.

#### Scenario: Étape 1 validation contact
- **WHEN** une famille commence la création de commande
- **THEN** la première étape demande la validation de l'email et du numéro de téléphone

#### Scenario: Saisie des enfants concernés
- **WHEN** une famille crée une commande
- **THEN** l'étape enfants demande le nombre d'enfants concernés ainsi que nom, prénom et niveau pour chaque enfant

#### Scenario: Étape enfants explicite et orientée formulaire
- **WHEN** une famille consulte l'étape 2 de création de commande
- **THEN** le titre affiché est `Étape 2 - Enfants concernés par la commande` et les données enfants sont présentées sous forme de zones de saisie par enfant

#### Scenario: Navigation étape 2 vers étapes précédente/suivante
- **WHEN** une famille complète l'étape 2
- **THEN** l'interface affiche des boutons de passage vers l'étape précédente et l'étape suivante

#### Scenario: Pré-remplissage des enfants depuis la commande précédente
- **WHEN** une famille dispose d'une commande précédente
- **THEN** l'étape enfants peut pré-remplir les informations des enfants à partir de la commande précédente via un texte indicatif (sans bouton d'action dédié)

#### Scenario: Limite du nombre d'enfants
- **WHEN** une famille renseigne le nombre d'enfants concernés
- **THEN** la saisie autorise un maximum de 8 enfants

#### Scenario: Cohérence entre nombre d'enfants et cartes de saisie affichées
- **WHEN** l'étape 2 affiche deux cartes enfants à renseigner
- **THEN** la valeur sélectionnée du nombre d'enfants est `2`

#### Scenario: Libellé niveau contextualisé par période
- **WHEN** une famille renseigne le niveau d'un enfant
- **THEN** le champ affiche le contexte `Niveau (sur la période <nom-période-en-cours>)`

#### Scenario: Absence de niveau principal
- **WHEN** une famille renseigne les enfants
- **THEN** la saisie ne demande pas de niveau principal unique et conserve un niveau par enfant

#### Scenario: Choix des quantités par fourniture de référence
- **WHEN** une famille consulte la liste des fournitures
- **THEN** l'étape fournitures affiche toute la liste avec la quantité recommandée par niveau selon les enfants saisis, et permet de saisir la quantité souhaitée

#### Scenario: Quantités saisissables sur l'étape fournitures
- **WHEN** une famille renseigne l'étape 3
- **THEN** chaque ligne de fourniture expose un champ de saisie de quantité commandée

#### Scenario: Affichage du prix TTC des articles
- **WHEN** une famille consulte les lignes article de l'étape 3
- **THEN** chaque article correspondant affiche son prix TTC en plus du champ de quantité

#### Scenario: Cas majoritaire d'une seule référence article
- **WHEN** une famille consulte les fournitures de l'étape 3
- **THEN** la majorité des fournitures affiche une seule référence article avec son champ de quantité

#### Scenario: Représentation d'une liste volumineuse de fournitures
- **WHEN** la liste de fournitures contient un volume courant de 35 à 40 références
- **THEN** l'interface affiche explicitement ce volume et la logique de saisie reste représentative d'une liste longue

#### Scenario: Mode concis en vue téléphone pour l'étape 3
- **WHEN** l'interface est consultée en vue téléphone
- **THEN** l'étape 3 propose une présentation concise en cartes, tout en conservant la saisie des quantités, les recommandations et les prix TTC

#### Scenario: Plusieurs articles pour une fourniture de référence
- **WHEN** une fourniture de référence dispose de plusieurs articles correspondants
- **THEN** le formulaire permet de saisir des quantités pour une ou plusieurs options d'article en parallèle (sans choix exclusif de type liste déroulante unique)

#### Scenario: Exemples de fournitures à références multiples
- **WHEN** une fourniture est dans les cas multi-références
- **THEN** l'interface illustre notamment `Règle plate 30cm` (Règle en bois 30cm, Règle plate - 30 cm - Incassable, Kit du gaucher) et `Lot de surligneurs de couleurs` (lot de 6 + surligneurs unitaires orange/jaune/rose/vert)

#### Scenario: Recommandations simplifiées pour exemples multi-références
- **WHEN** l'interface illustre les exemples `Règle plate 30cm` et `Lot de surligneurs de couleurs`
- **THEN** les quantités recommandées par niveau sont fixées à 1 pour chaque niveau afin de rester lisibles

### Requirement: La cotisation MUST être configurée par période avec dégressivité
L'application MUST afficher la configuration du prix de cotisation par période avec une grille dégressive: prix pour le 1er enfant, prix pour le 2e enfant, prix pour le 3e et suivants.

#### Scenario: Configuration de cotisation en détail de période
- **WHEN** un gestionnaire consulte le détail d'une période
- **THEN** il visualise et pilote les montants de cotisation (1er, 2e, 3e et suivants)

### Requirement: La commande famille MUST intégrer la cotisation
L'écran de création de commande MUST inclure le montant de la cotisation dans le total de commande.

#### Scenario: Affichage du coût de cotisation au choix du nombre d'enfants
- **WHEN** le nombre d'enfants est sélectionné dans le formulaire
- **THEN** le coût de cotisation correspondant est affiché dans la zone de saisie des enfants

#### Scenario: Total commande avec cotisation
- **WHEN** une famille finalise sa commande
- **THEN** le récapitulatif affiche le sous-total articles, le montant de cotisation et le total commande incluant la cotisation

#### Scenario: Étape 4 validation de commande
- **WHEN** une famille atteint l'étape 4 de création
- **THEN** l'interface affiche une étape `Étape 4 - Validation de votre commande`

#### Scenario: Récapitulatif final limité aux articles choisis
- **WHEN** la famille consulte l'étape 4
- **THEN** seuls les articles choisis sont affichés avec quantité, montant unitaire TTC, total par ligne, et total global de commande

#### Scenario: Étape 5 choix du mode de paiement
- **WHEN** la famille poursuit après l'étape 4
- **THEN** l'interface affiche une étape `Étape 5 - Mode de paiement`

#### Scenario: Modes de paiement disponibles à l'étape 5
- **WHEN** la famille consulte l'étape 5
- **THEN** elle peut choisir entre `Paiement CB (via HelloAsso)`, `Paiement par chèque` et `Paiement en espèce`

#### Scenario: Notice explicative pour paiement par chèque
- **WHEN** la famille choisit le paiement par chèque
- **THEN** l'interface affiche une notice succincte indiquant le dépôt du chèque avec nom de l'enfant, niveau et numéro de commande au dos, ainsi que le bon de commande reçu par mail et imprimé

#### Scenario: Notice explicative pour paiement en espèce
- **WHEN** la famille choisit le paiement en espèce
- **THEN** l'interface affiche une notice succincte indiquant que l'appoint est obligatoire et que le bon de commande reçu par mail doit être imprimé

#### Scenario: Détail commande cohérent avec les lignes de fournitures choisies
- **WHEN** un utilisateur consulte l'écran `Détail commande` d'une commande famille
- **THEN** les lignes de commande reprennent des fournitures réalistes issues de la création de commande (article choisi, quantité, PU TTC, total ligne)

### Requirement: L'étape 5 MUST adapter le libellé du bouton final au mode de paiement sélectionné
Dans `Étape 5 - Mode de paiement`, le libellé du bouton d'action final MUST refléter l'intention métier du mode choisi: paiement effectif pour CB, confirmation pour chèque/espèce.\
L'étape MUST afficher un message explicite indiquant que la commande est prise en compte à réception du paiement.

#### Scenario: Libellé orienté paiement pour CB
- **WHEN** la famille sélectionne le radio bouton `Paiement CB (via HelloAsso)` dans `Étape 5 - Mode de paiement`
- **THEN** le bouton final affiche un libellé de paiement explicite, de type `Payer la commande`

#### Scenario: Libellé de confirmation pour chèque
- **WHEN** la famille sélectionne le radio bouton `Paiement par chèque` dans `Étape 5 - Mode de paiement`
- **THEN** le bouton final affiche un libellé de confirmation de commande (et non un libellé de paiement CB)

#### Scenario: Libellé de confirmation pour espèce
- **WHEN** la famille sélectionne le radio bouton `Paiement en espèce` dans `Étape 5 - Mode de paiement`
- **THEN** le bouton final affiche un libellé de confirmation de commande (et non un libellé de paiement CB)

#### Scenario: Mise à jour immédiate du libellé lors du changement de mode
- **WHEN** la famille change de radio bouton de mode de paiement au sein de l'étape 5
- **THEN** le libellé du bouton final est mis à jour immédiatement pour rester cohérent avec le mode actuellement sélectionné

#### Scenario: Information de prise en compte de commande
- **WHEN** la famille consulte `Étape 5 - Mode de paiement`
- **THEN** l'interface affiche un texte explicite indiquant que la commande n'est prise en compte qu'à réception du paiement

### Requirement: Le listing utilisateurs MUST proposer une zone de recherche
L'écran de listing des utilisateurs MUST inclure une zone de recherche permettant de filtrer rapidement la liste pour retrouver un compte cible avant changement de rôle.

#### Scenario: Filtrage du listing utilisateurs
- **WHEN** un utilisateur saisit une valeur dans la zone de recherche du listing utilisateurs
- **THEN** l'interface affiche uniquement les comptes correspondants aux critères de recherche définis

### Requirement: Le changement de rôle MUST être intégré au listing utilisateurs pour les admins
L'interface MUST proposer une action de changement de rôle directement dans l'écran de listing utilisateurs pour le rôle `Admin`, sans imposer de navigation vers un parcours séparé.

#### Scenario: Action disponible pour un admin
- **WHEN** un utilisateur connecté avec le rôle `Admin` consulte le listing utilisateurs
- **THEN** chaque ligne utilisateur affiche une action permettant de lancer le changement de rôle

#### Scenario: Action masquée pour un non-admin
- **WHEN** un utilisateur sans rôle `Admin` consulte le listing utilisateurs
- **THEN** l'interface n'affiche pas d'action de changement de rôle

### Requirement: Le parcours intégré MUST limiter le choix aux rôles autorisés
Le composant de changement de rôle intégré au listing MUST proposer uniquement les rôles `Famille`, `Gestionnaire` et `Admin`, puis afficher un retour explicite de succès ou d'échec après validation. La description du parcours de référence MUST couvrir les états ci-dessous, rattachés aux exigences d'autorisation, de référentiel des rôles, de validation du compte cible et de journalisation de `user-role-administration-and-audit`, sans créer d'écran de gestion des permissions.

#### Scenario: Soumission d'un nouveau rôle depuis le listing
- **WHEN** un admin sélectionne un rôle autorisé et confirme le changement depuis le listing utilisateurs
- **THEN** l'interface affiche un retour explicite sur le résultat de l'opération
- **AND** en cas de succès, elle rappelle l'ancien rôle et le nouveau rôle de l'utilisateur cible, conformément aux informations de la trace d'audit

#### Scenario: Tentative de soumission d'un rôle non autorisé
- **WHEN** une valeur de rôle hors référentiel est soumise depuis l'interface
- **THEN** l'interface bloque la validation et affiche une erreur exploitable
- **AND** elle conserve le rôle courant inchangé dans le récapitulatif visuel

#### Scenario: Refus d'autorisation lors de la confirmation
- **WHEN** un acteur qui n'a pas le rôle `Admin` tente de confirmer un changement de rôle, notamment après avoir perdu son habilitation
- **THEN** le parcours représente un refus d'autorisation explicite, conserve le rôle courant de la cible et n'affiche aucune confirmation de modification

#### Scenario: Compte cible introuvable lors de la confirmation
- **WHEN** un acteur `Admin` confirme un changement pour un utilisateur cible qui n'existe plus
- **THEN** le parcours représente une erreur explicite de compte introuvable, sans afficher de succès ni de rôle modifié

### Requirement: L'interface admin MUST permettre de consulter la trace des changements de rôle
Le parcours d'administration utilisateurs MUST exposer un accès simple à l'historique des changements de rôle d'un compte pour vérifier qui a modifié quoi et quand.

#### Scenario: Consultation de l'historique depuis le parcours utilisateurs
- **WHEN** un admin ouvre l'historique de changements de rôle d'un utilisateur depuis le parcours de listing
- **THEN** l'interface affiche les traces disponibles avec au minimum l'ancien rôle, le nouveau rôle, l'auteur admin et l'horodatage

### Requirement: Le référentiel de maquettes MUST exclure l'administration des rôles et permissions
Le référentiel de maquettes MUST NOT contenir l'écran `admin-roles-permissions`, une entrée de navigation vers cet écran ou un écran de remplacement dédié à la gestion des rôles et permissions. Les parcours de maquette MUST NOT proposer de création, duplication ou modification de la définition des rôles ou de leurs permissions.

#### Scenario: Consultation du catalogue de maquettes
- **WHEN** un utilisateur consulte le catalogue de maquettes en mode local ou à distance
- **THEN** l'écran `admin-roles-permissions` n'apparaît ni dans la navigation ni dans les aperçus disponibles

#### Scenario: Accès à l'ancienne page
- **WHEN** un utilisateur tente d'ouvrir directement l'ancienne page `admin-roles-permissions`
- **THEN** cette page n'est plus fournie par le référentiel de maquettes

#### Scenario: Préservation de l'administration des utilisateurs
- **WHEN** un admin consulte le listing utilisateurs après le retrait de l'écran
- **THEN** le changement de rôle parmi `Famille`, `Gestionnaire` et `Admin` et la consultation de son historique restent disponibles
- **AND** ces actions ne permettent pas de modifier les permissions des rôles

### Requirement: Le listing utilisateurs MUST permettre à un admin d'imposer un changement de mot de passe
La maquette d'administration des utilisateurs MUST représenter une action réservée au rôle `Admin` permettant d'imposer à un compte le changement de mot de passe à sa prochaine connexion. Le parcours MUST distinguer cette action d'une réinitialisation de mot de passe et MUST présenter une confirmation ou un retour explicite de résultat.

#### Scenario: Action disponible pour un admin
- **WHEN** un `Admin` consulte le listing utilisateurs
- **THEN** chaque compte présente une action permettant de demander le changement de mot de passe à sa prochaine connexion

#### Scenario: Confirmation de l'action
- **WHEN** un `Admin` déclenche l'action pour un compte
- **THEN** la maquette présente l'identité du compte ciblé et demande une confirmation avant d'imposer le changement

#### Scenario: Retour après confirmation
- **WHEN** l'administrateur confirme l'action
- **THEN** la maquette représente un retour de succès ou un message d'erreur explicite sans afficher ni demander le mot de passe du compte

#### Scenario: Action masquée pour un non-admin
- **WHEN** un utilisateur sans rôle `Admin` consulte le listing utilisateurs
- **THEN** la maquette ne présente pas l'action d'imposition de changement de mot de passe

### Requirement: L'écran de génération en lot MUST représenter le parcours gestionnaire UC-CON-3
La maquette de référence UC-CON-3 MUST être rattachée au rôle `gestionnaire` et MUST présenter la période en cours, le critère d'éligibilité des commandes (règlement par carte non annulé), le tri alphabétique par nom puis prénom du premier enfant lié à chaque commande, la limite de 30 commandes par lot et l'ordre de génération. Elle MUST illustrer la progression séquentielle, le cas sans commande éligible et l'échec d'un lot. L'écran de référence MUST rester statique et MUST NOT laisser croire qu'une génération ou un téléchargement réel est exécuté.

#### Scenario: Consultation du parcours de génération
- **WHEN** un gestionnaire consulte l'écran de référence UC-CON-3
- **THEN** il voit que le traitement concerne uniquement la période en cours et les commandes ayant un règlement par carte non annulé, avec au plus 30 commandes par lot

#### Scenario: Visualisation de la progression
- **WHEN** un gestionnaire consulte l'exemple de traitement en cours
- **THEN** la maquette montre les lots ordonnés alphabétiquement par nom puis prénom du premier enfant, le rang du lot courant et l'état de restitution du PDF attendu

#### Scenario: Aucune commande éligible
- **WHEN** le gestionnaire consulte l'état de résultat sans commande éligible
- **THEN** la maquette montre un message explicite et aucun lot ni document téléchargé

#### Scenario: Échec d'un lot
- **WHEN** le gestionnaire consulte l'état d'un lot dont la génération a échoué
- **THEN** la maquette identifie le lot en échec et montre un message d'erreur sans afficher le traitement complet comme réussi

#### Scenario: Aperçu statique d'un bon de commande
- **WHEN** le gestionnaire consulte l'exemple de bon de commande associé à la génération en lot
- **THEN** la maquette illustre les données assemblées par le traitement legacy pour le modèle de lot : informations de commande et de période, commanditaire, enfants avec leur classe, articles avec référence, marque, quantité, prix et total, ainsi que le total de commande
- **AND** l'exemple est explicitement fictif et ne prétend pas reproduire une mise en page dont le modèle HTML n'est pas disponible dans le dépôt

#### Scenario: Maquette sans exécution réelle
- **WHEN** un utilisateur consulte ou actionne les contrôles visuels de la maquette
- **THEN** aucune requête métier, génération de PDF ou téléchargement réel n'est déclenché

### Requirement: Les maquettes MUST couvrir les deux journaux pour les gestionnaires et les admins
Le référentiel MUST proposer deux écrans distincts `suivi-journal-actions` et `suivi-anomalies`, déclarés pour les groupes `gestionnaire` et `admin` dans les deux manifestes. Chaque écran MUST représenter les colonnes domaine, action, contexte et date-heure, avec des exemples fictifs triés du plus récent au plus ancien. Les écrans MUST rester consultables localement et à distance sans backend, en reprenant les styles partagés et la présentation responsive des listings.

#### Scenario: Navigation des deux rôles
- **WHEN** un participant ouvre le catalogue sous le groupe gestionnaire ou admin
- **THEN** il peut ouvrir les deux journaux avec leurs exemples fictifs et leurs quatre informations minimales

#### Scenario: Aperçu téléphone et desktop
- **WHEN** un participant consulte chacun des deux journaux en mode téléphone puis desktop
- **THEN** les quatre informations sont lisibles en cartes sur téléphone sans défilement horizontal de la page, et en tableau sur desktop, avec les mêmes entrées dans le même ordre

### Requirement: Les maquettes des journaux MUST distinguer données et états de consultation
Chaque écran MUST représenter explicitement les états liste renseignée, liste vide, refus d’autorisation, session expirée et erreur de chargement définis dans `application-journal-consultation`. Les exemples MUST être identifiés comme fictifs, sans appel réseau, persistance ni action de modification ou traitement des traces. Les états d’erreur MUST NOT afficher les exemples comme s’il s’agissait de résultats réels.

#### Scenario: Revue des états sans backend
- **WHEN** un participant consulte les variantes de chacun des deux écrans hors connexion
- **THEN** il peut examiner les cinq états identifiés sans service métier ni donnée réelle

#### Scenario: Accès refusé ou session expirée
- **WHEN** une variante représente un utilisateur non autorisé ou une session expirée
- **THEN** elle affiche le message correspondant sans trace visible et sans suggérer qu’un masquage de navigation suffit à protéger les données

#### Scenario: Absence de résultat et erreur technique
- **WHEN** un participant compare les variantes vide et erreur de chargement
- **THEN** la première indique l’absence d’entrée et la seconde un échec explicite, sans les confondre ni proposer des exemples de substitution

### Requirement: Les libellés visibles des maquettes MUST respecter l'orthographe française
Les textes affichés aux utilisateurs dans les maquettes de référence MUST utiliser l'orthographe française correcte, y compris les accents, la cédille (`ç`), les apostrophes et la casse attendue.

#### Scenario: Libellé nécessitant un accent ou une cédille
- **WHEN** un libellé de maquette contient un mot français dont la graphie correcte comporte un accent ou une cédille
- **THEN** la maquette affiche ce mot avec sa graphie correcte, sans suppression du signe diacritique

#### Scenario: Validation d'un écran corrigé
- **WHEN** l'équipe relit un écran du référentiel de maquettes
- **THEN** aucun libellé utilisateur de cet écran n'emploie une variante orthographique fautive liée à l'absence d'accent ou de cédille

### Requirement: Les libellés partagés entre maquettes et specs MUST rester cohérents
Lorsqu'un même libellé fonctionnel est référencé dans une maquette et dans les specs OpenSpec de flux d'écrans, la même orthographe française MUST être utilisée dans les deux sources.

#### Scenario: Alignement d'un libellé entre artefacts
- **WHEN** un libellé fonctionnel est présent à la fois dans une maquette et dans une spec OpenSpec associée
- **THEN** les deux occurrences utilisent la même graphie française correcte
