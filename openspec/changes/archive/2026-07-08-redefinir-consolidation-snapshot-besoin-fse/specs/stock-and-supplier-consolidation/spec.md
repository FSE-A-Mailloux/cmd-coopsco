## MODIFIED Requirements

### Requirement: La consolidation fournisseur MUST rester consultable en continu
La consolidation fournisseur MUST permettre de consulter l'etat courant de travail de la periode en cours, sans choix manuel de periode, et MUST permettre de consulter les consolidations enregistrees avec horodatage.

#### Scenario: Consultation de la consolidation courante
- **WHEN** un utilisateur autorise consulte la consolidation courante
- **THEN** le systeme retourne la consolidation courante de la periode en cours

#### Scenario: Consultation d'une consolidation enregistree
- **WHEN** un utilisateur autorise consulte l'historique de consolidation
- **THEN** le systeme retourne les consolidations enregistrees avec leur horodatage et leur periode associee

## REMOVED Requirements

### Requirement: La consolidation MUST calculer de manière cohérente les unités nettes et les lots
**Reason**: Le besoin metier cible une quantite a commander par article basee sur stock, commandes familles et besoin FSE, sans logique de lot dans l'ecran de consolidation.
**Migration**: Utiliser la nouvelle regle de calcul de quantite a commander par article introduite dans cette change.

## ADDED Requirements

### Requirement: La consolidation MUST calculer la quantite a commander par article
La consolidation MUST calculer, pour chaque article, la quantite a commander avec la regle `max(0, quantite commandee familles + besoin FSE - stock disponible)`.

#### Scenario: Calcul unitaire de commande fournisseur
- **WHEN** les valeurs stock, quantite commandee familles et besoin FSE sont connues pour un article
- **THEN** le systeme calcule la quantite a commander pour cet article avec un plancher a 0

### Requirement: La consolidation MUST supporter la saisie du besoin FSE par article
Le systeme MUST permettre la saisie et la persistence du besoin FSE (stock a conserver) pour chaque article dans le contexte d'une consolidation.

#### Scenario: Saisie du besoin FSE
- **WHEN** un utilisateur autorise saisit le besoin FSE d'un article dans la consolidation
- **THEN** le systeme enregistre cette valeur pour la consolidation ciblee

### Requirement: La consolidation MUST exposer les indicateurs financiers d'estimation conditionnels
Le systeme MUST calculer et exposer `CA estime`, `Marge estimee` et `Cout du stock prevu (besoin FSE)` pour la periode en cours lorsque les tarifs fournisseur HT necessaires sont renseignes; sinon le systeme MUST signaler une estimation partielle.

#### Scenario: Estimation complete disponible
- **WHEN** tous les tarifs fournisseur HT necessaires sont renseignes pour la periode en cours
- **THEN** le systeme retourne les trois indicateurs financiers d'estimation

#### Scenario: Estimation partielle
- **WHEN** au moins un tarif fournisseur HT necessaire est absent
- **THEN** le systeme retourne les indicateurs disponibles et signale explicitement que l'estimation est partielle
