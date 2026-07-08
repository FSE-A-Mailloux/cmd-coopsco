## ADDED Requirements

### Requirement: L'ecran Consolidation MUST representer un snapshot par periode
L'ecran `Consolidation` MUST etre centre sur la periode en cours, affichee en libelle non editable, et MUST presenter, a un instant donne, les donnees article necessaires a la decision de commande fournisseur.

#### Scenario: Consultation du snapshot courant
- **WHEN** un gestionnaire ouvre l'ecran `Consolidation`
- **THEN** l'interface rappelle la periode en cours via un libelle non modifiable et n'affiche pas de selecteur de periode
- **THEN** l'interface affiche, par article, le stock disponible, la quantite commandee familles, la saisie du besoin FSE et la quantite a commander calculee

### Requirement: L'ecran Consolidation MUST calculer la quantite a commander a partir du differentiel metier
Pour chaque article, l'ecran `Consolidation` MUST calculer la quantite a commander selon la regle `max(0, quantite commandee familles + besoin FSE - stock disponible)`.

#### Scenario: Calcul de la quantite a commander d'un article
- **WHEN** un gestionnaire saisit ou modifie le besoin FSE d'un article
- **THEN** l'interface recalcule la quantite a commander avec la formule metier et applique un plancher a 0

### Requirement: L'ecran Consolidation MUST permettre l'enregistrement et la consultation historique des snapshots
L'ecran `Consolidation` MUST permettre d'enregistrer une consolidation et MUST permettre de consulter ulterieurement les consolidations enregistrees avec leur horodatage.

#### Scenario: Enregistrement d'une consolidation
- **WHEN** un gestionnaire enregistre la consolidation d'une periode
- **THEN** la consolidation est stockee avec horodatage et devient consultable dans l'historique

### Requirement: L'ecran Consolidation MUST afficher les indicateurs financiers d'estimation et MUST NOT afficher le taux de service
L'ecran `Consolidation` MUST afficher `CA estime`, `Marge estimee` et `Cout du stock prevu (besoin FSE)` sur la periode en cours quand les donnees tarifaires fournisseur HT necessaires sont disponibles, et MUST NOT afficher de taux de service.

#### Scenario: Affichage des indicateurs d'estimation
- **WHEN** un gestionnaire consulte la consolidation de la periode en cours
- **THEN** l'interface affiche `CA estime`, `Marge estimee` et `Cout du stock prevu (besoin FSE)` avec indication explicite si une partie des tarifs fournisseur HT est manquante
- **AND** l'interface n'affiche aucun KPI ni colonne `Taux service`
