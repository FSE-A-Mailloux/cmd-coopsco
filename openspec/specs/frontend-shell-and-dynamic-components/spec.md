## Purpose

Définir le contrat du shell frontend et le comportement de navigation dynamique par composant.

## Requirements

### Requirement: Les détails d’implémentation frontend legacy MUST être traités comme legacy-only
Les détails d’implémentation de l’interface legacy MUST être considérés comme référence de migration uniquement et MUST NOT contraindre la solution cible.

#### Scenario: Réécriture sans couplage à la solution legacy
- **WHEN** l’équipe réalise la nouvelle interface applicative
- **THEN** elle respecte les exigences fonctionnelles sans reproduire les mécanismes techniques legacy

### Requirement: Le shell frontend MUST fournir un cadre applicatif stable
L'application MUST fournir un cadre stable comprenant authentification, navigation contextuelle, zone de contenu métier et retours utilisateur.\
Avant implémentation dynamique, ce cadre MUST être représenté par des maquettes statiques de référence consultables dans un dossier dédié.

#### Scenario: Rendu initial du shell
- **WHEN** un utilisateur ouvre l'application
- **THEN** l'interface affiche les zones principales nécessaires au parcours utilisateur

#### Scenario: Résolution de route au démarrage
- **WHEN** aucun contexte d'entrée spécifique n'est fourni
- **THEN** l'application charge le parcours ou composant métier par défaut

#### Scenario: Indicateurs commandes par statut sur la période en cours
- **WHEN** un utilisateur consulte l'écran `Shell de référence`
- **THEN** la zone de contenu métier affiche le nombre de commandes de la période en cours pour chaque statut (`Brouillon`, `En attente de paiement`, `Paiement partiel`, `Paiement en cours`, `Confirmée`, `Annulée`) et n'affiche pas d'indicateurs `Demandes en attente` ni `Alertes stock`

#### Scenario: Affichage de la période en cours et de son état
- **WHEN** un utilisateur consulte l'écran `Shell de référence`
- **THEN** la maquette affiche la période marquée `en cours` et son état (`Ouverte` ou `Fermée`) pour contextualiser les indicateurs de commande

#### Scenario: Métriques de pilotage sur la période en cours
- **WHEN** un utilisateur consulte l'écran `Shell de référence`
- **THEN** la zone centrale affiche des métriques pertinentes de la période en cours (au minimum `CA`, `Nb commandes`, `Panier moyen`) en complément de la répartition des statuts

#### Scenario: Ordre de grandeur du panier moyen cohérent avec le parcours famille
- **WHEN** la maquette `Shell de référence` affiche les métriques de la période en cours
- **THEN** la valeur de `Panier moyen` est représentée avec un ordre de grandeur réaliste pour le contexte famille (autour de 50 EUR, et non de plusieurs centaines ou milliers d'euros)

#### Scenario: Récapitulatif des périodes passées
- **WHEN** un utilisateur consulte l'écran `Shell de référence`
- **THEN** la maquette affiche un récapitulatif des périodes passées (au minimum `CA` et `Nb commandes` par période) pour comparer la période en cours avec l'historique

#### Scenario: Référentiel visuel de shell en phase de réécriture
- **WHEN** l'équipe prépare la réécriture de l'interface
- **THEN** elle dispose d'une maquette statique du shell, consultable dans l'espace dédié des maquettes, comme référence de validation

#### Scenario: Navigation shell adaptée au rôle famille pour les commandes
- **WHEN** un utilisateur famille navigue dans le shell maquette
- **THEN** il accède à la liste de ses propres commandes et aux écrans de création et détail de commande

#### Scenario: Navigation shell adaptée au rôle gestionnaire pour le référentiel
- **WHEN** un utilisateur gestionnaire navigue dans le shell maquette
- **THEN** il accède aux écrans périodes, articles, tarifs, stock et consolidation

#### Scenario: Navigation shell adaptée au rôle gestionnaire pour le pilotage périodes/tarifs
- **WHEN** un utilisateur gestionnaire suit le parcours périodes et tarifs
- **THEN** il peut accéder aux écrans de détail de période, au sous-écran tarifs d'un article, à l'édition de prix et à la reprise de tarifs vers la période en cours

#### Scenario: Navigation shell adaptée aux règles de disponibilité article
- **WHEN** un utilisateur gestionnaire configure la disponibilité d'un article
- **THEN** les parcours de création de commande et de gestion de stock reflètent les filtres de disponibilité commandes/stock

#### Scenario: Navigation d'entrée famille avec création de compte
- **WHEN** un utilisateur famille consulte les parcours d'entrée
- **THEN** il accède à un écran dédié de création de compte en plus de connexion, activation et récupération d'accès

#### Scenario: Navigation shell gestionnaire pour l'encaissement commande
- **WHEN** un gestionnaire doit enregistrer des paiements de commande
- **THEN** il suit un parcours dédié déclenché depuis l'action `Encaisser (chèque/espèce)` d'une commande `Confirmée` (depuis la liste des commandes ou le détail de commande), ouvrant directement la saisie d'un ou plusieurs paiements

#### Scenario: Navigation shell famille pour la commande de fournitures scolaires
- **WHEN** une famille prépare une commande
- **THEN** le shell maquette donne accès à un formulaire de commande en plusieurs étapes: validation contact, saisie enfants, sélection des fournitures, validation de commande et choix du mode de paiement

#### Scenario: Navigation shell gestionnaire pour la cotisation de période
- **WHEN** un gestionnaire pilote une période
- **THEN** il accède à la configuration de la cotisation de la période (1er enfant, 2e enfant, 3e et suivants)

### Requirement: La navigation composant MUST être basée sur une pile
La navigation MUST conserver un historique de parcours permettant l’aller, le retour et la restauration de contexte.

#### Scenario: Chargement d’un composant
- **WHEN** un utilisateur ouvre un composant autorisé
- **THEN** le système active ce composant et met à jour le contexte de navigation

#### Scenario: Retour au composant précédent
- **WHEN** un utilisateur déclenche une action de retour
- **THEN** le système restaure le composant précédent avec son contexte

### Requirement: L’état frontend MUST exposer les retours de chargement et d’erreur
L’interface MUST indiquer explicitement les états de chargement et les erreurs pour éviter les états ambigus côté utilisateur.

#### Scenario: Cycle de vie d’une requête
- **WHEN** une opération distante démarre puis se termine
- **THEN** l’interface expose l’état de chargement pendant l’opération puis le retire à la fin

#### Scenario: Échec de chargement de contenu
- **WHEN** un contenu métier ne peut pas être affiché
- **THEN** l’interface affiche une erreur explicite et maintient un état cohérent
