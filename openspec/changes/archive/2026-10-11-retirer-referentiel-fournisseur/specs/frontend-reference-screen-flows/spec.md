# Spec Delta

## Purpose

Aligner les écrans de catalogue, de tarification et de consolidation sur la
suppression du référentiel fournisseur, sans retirer la saisie du prix d'achat.

## MODIFIED Requirements

### Requirement: Les écrans de prix MUST distinguer le prix d'achat de l'identité fournisseur

Les écrans MUST afficher et saisir la donnée `prix fournisseur HT` comme prix
d'achat lorsque le mode HT + marge est utilisé. Ils MUST NOT afficher de champ,
filtre, action ou libellé demandant l'identité d'un fournisseur.

#### Scenario: Saisie d'un prix HT et d'une marge

- **GIVEN** un gestionnaire édite le prix d'un article
- **WHEN** il choisit le mode `prix fournisseur HT + marge`
- **THEN** l'écran permet de saisir le prix fournisseur HT et la marge
- **AND** il affiche le prix TTC calculé
- **AND** il ne demande aucun fournisseur

### Requirement: L'écran de consolidation MUST rester centré sur l'article et la période

L'écran `Consolidation` MUST présenter les données de stock, de besoin FSE, de
quantité à commander et de prix d'achat nécessaires à la décision. Il MUST NOT
présenter de colonne, filtre ou action de sélection d'un fournisseur.

#### Scenario: Consultation de la consolidation

- **GIVEN** un utilisateur autorisé consulte la consolidation de la période en cours
- **THEN** l'écran affiche les données nécessaires par article
- **AND** il peut afficher les indicateurs financiers lorsque les prix d'achat sont disponibles
- **AND** il signale une estimation partielle si un prix d'achat manque
- **AND** aucune identité fournisseur n'est affichée

## REMOVED Requirements

### Requirement: Un écran de gestion du référentiel fournisseur MUST être publié

L'écran, les routes, les entrées de menu et les actions dédiés à la gestion des
fournisseurs sont retirés des flux de référence et du manifeste des maquettes.