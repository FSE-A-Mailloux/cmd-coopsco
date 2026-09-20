# Proposal

## Why

Le référentiel fournisseur est abandonné dans le périmètre cible, mais sa présence
résiduelle dans le PRD, les spécifications et les maquettes entretient une capacité
qui ne doit plus être proposée. La notion métier utile est le prix fournisseur HT,
qui doit rester disponible comme prix d'achat pour la consolidation et les
estimations financières.

## What Changes

- Retirer les entités, écrans, parcours et règles qui permettent de gérer un
  référentiel fournisseur autonome.
- Remplacer les dépendances au fournisseur par la donnée `prix fournisseur HT`,
  rattachée au contexte de prix ou de consolidation approprié.
- Mettre à jour les spécifications concernées et les règles de parcours d'écran.
- Mettre à jour les maquettes et leur manifeste afin qu'aucun écran de gestion des
  fournisseurs ne soit publié.
- Mettre à jour la matrice de couverture pour rendre la décision d'abandon de
  `UC-ART-4` traçable.

## Capabilities

- **UC-ART-4**: supprimer le référentiel fournisseur comme capacité de gestion.
- **Consolidation et estimation**: conserver le prix fournisseur HT comme prix
  d'achat nécessaire au calcul des quantités et indicateurs financiers.

## Impact

- **Spécifications**: principalement `stock-and-supplier-consolidation` et les
  règles de flux d'écran qui exposent encore le fournisseur.
- **Maquettes**: suppression ou correction des écrans, libellés et actions liés au
  référentiel fournisseur.
- **Traçabilité**: mise à jour de `docs/legacy-coverage-matrix.md` et validation
  par le contrôle de couverture.
- **Implémentation future**: aucun CRUD de fournisseur ni menu associé ne devra
  être construit; les calculs de consolidation continuent d'utiliser le prix
  d'achat conservé.