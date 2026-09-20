# Design

## Context

Le périmètre cible conserve la consolidation annuelle et le mode de tarification
`prix fournisseur HT + marge`, mais abandonne la gestion d'une entité fournisseur.
Les artefacts actuels mélangent encore l'identité d'un fournisseur avec le prix
d'achat. Les deltas de spécification de ce changement définissent la séparation
attendue entre ces deux notions.

## Goals and Non-Goals

### Goals

- Supprimer toute capacité de CRUD, de recherche et de rattachement fournisseur.
- Conserver une valeur de prix d'achat exploitable par article et période.
- Préserver les calculs de consolidation et les indicateurs financiers conditionnels.
- Aligner les écrans, menus, routes de maquettes et le manifeste sur cette décision.
- Rendre `UC-ART-4` traçable comme use-case abandonné.

### Non-Goals

- Reconcevoir la consolidation, le stock ou les règles de calcul de quantité.
- Définir une nouvelle gestion de tarifs ou une nouvelle stratégie de marge.
- Modifier le dossier `legacy/`, qui reste une source historique de comportement.
- Construire l'application cible durant ce changement de spécification.

## Proposed Solution

Traiter `prix fournisseur HT` comme une donnée de prix d'achat portée par le
contexte article/période déjà utilisé par la tarification et la consolidation.
Retirer toute relation vers une identité fournisseur des modèles cibles, des
contrats d'écran et des maquettes. Conserver les libellés nécessaires au calcul,
mais les employer uniquement pour qualifier le prix, jamais pour suggérer un
référentiel administrable.

## Detailed Design

### Data flow

1. L'administration d'un article ou de sa tarification renseigne le prix
   fournisseur HT pour la période concernée.
2. La consolidation lit ce prix avec le stock disponible, les quantités commandées
   et le besoin FSE.
3. Le système calcule la quantité à commander et les indicateurs financiers selon
   les règles existantes.
4. Si un prix requis manque, les quantités non financières restent disponibles et
   l'estimation est marquée partielle.

Aucune étape ne résout ou ne persiste une identité de fournisseur.

### Interfaces and screens

- Retirer les routes, entrées de menu, actions et écrans dédiés au fournisseur.
- Retirer les colonnes, filtres et sélecteurs de fournisseur des écrans d'article,
  de prix et de consolidation.
- Conserver le champ et le libellé `prix fournisseur HT` dans les parcours où il
  alimente le prix TTC ou les estimations.
- Mettre à jour le manifeste et les règles de flux de référence des maquettes.

### Compatibility and migration

La migration de données applicative n'est pas exécutée dans H1. Les futurs travaux
d'implémentation devront ignorer ou isoler les identifiants fournisseurs historiques
et conserver uniquement les valeurs de prix d'achat nécessaires au périmètre cible.
Les références legacy restent consultables pour la traçabilité, mais ne doivent pas
être reproduites comme fonctionnalités.

### Traceability

La matrice de couverture est mise à jour pour conserver `UC-ART-4` à l'état
`ABANDONNÉ`, avec la décision explicite que le prix fournisseur HT désigne le prix
d'achat sans identité derrière. Le contrôle de couverture doit être exécuté après
la mise à jour des maquettes et de la matrice.

## Testing

- Valider les artefacts OpenSpec et la structure des deltas.
- Rechercher les occurrences de CRUD, de rattachement et d'identité fournisseur
  dans les specs et maquettes ciblées.
- Vérifier que les scénarios de prix HT + marge et d'estimation partielle restent
  présents.
- Exécuter le contrôle de couverture legacy et vérifier l'absence de descente
  incomplète pour `UC-ART-4`.
- Lors de l'implémentation future, ajouter des tests de calcul avec prix présent,
  prix absent et absence totale de référentiel fournisseur.

## Risks and Mitigations

- **Risque**: supprimer le terme fournisseur ferait perdre la donnée nécessaire aux
  calculs. **Mitigation**: conserver explicitement `prix fournisseur HT` comme prix
  d'achat dans les deux deltas de spécification.
- **Risque**: une maquette ou un menu résiduel réintroduit implicitement la capacité.
  **Mitigation**: contrôler les manifestes, routes, actions et libellés avant clôture.
- **Risque**: des références legacy sont interprétées comme des exigences cibles.
  **Mitigation**: les citer uniquement comme preuves historiques et appliquer la
  décision de la matrice.

## Alternatives Considered

- **Conserver un fournisseur par défaut**: rejeté, car cela maintiendrait une
  identité métier sans besoin cible et contredirait l'abandon de `UC-ART-4`.
- **Renommer le prix en prix d'achat immédiatement**: non retenu pour ce cadrage,
  afin de préserver la continuité avec la dénomination existante; le sens métier
  est clarifié sans imposer un renommage global.
- **Supprimer aussi le prix fournisseur HT**: rejeté, car cette donnée reste
  nécessaire au mode HT + marge et aux estimations de consolidation.