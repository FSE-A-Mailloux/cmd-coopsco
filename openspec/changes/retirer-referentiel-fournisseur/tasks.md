# Tasks

## 1. Aligner les spécifications métier et les flux

- [ ] 1.1 Mettre à jour `stock-and-supplier-consolidation` pour représenter le prix fournisseur HT comme prix d'achat sans identité fournisseur, puis vérifier les scénarios de calcul et d'estimation partielle.
- [ ] 1.2 Mettre à jour `frontend-reference-screen-flows` pour retirer les champs, filtres, actions et écrans de gestion fournisseur, puis vérifier les scénarios de tarification HT + marge et de consolidation.
- [ ] 1.3 Rechercher dans les specs cibles les occurrences qui impliquent encore un CRUD, une sélection ou un rattachement fournisseur, et corriger chaque occurrence sans supprimer la donnée de prix d'achat.

## 2. Aligner les maquettes et leur publication

- [ ] 2.1 Retirer ou corriger les écrans, routes, menus, colonnes, filtres et actions de fournisseur sous `maquettes/` conformément aux deltas de flux.
- [ ] 2.2 Vérifier que les écrans de prix conservent la saisie `prix fournisseur HT` et l'affichage du prix TTC calculé avec marge.
- [ ] 2.3 Vérifier que l'écran de consolidation conserve les données de décision, les indicateurs financiers et le signalement d'estimation partielle sans afficher d'identité fournisseur.
- [ ] 2.4 Mettre à jour `maquettes/manifest/screens.json` et rechercher les libellés fournisseur résiduels dans les maquettes publiées.

## 3. Mettre à jour la traçabilité de couverture

- [ ] 3.1 Vérifier que `UC-ART-4` reste marqué `ABANDONNÉ` dans `docs/legacy-coverage-matrix.md` avec la décision de conserver le prix fournisseur HT comme prix d'achat.
- [ ] 3.2 Ajouter ou corriger les références du changement et des artefacts dans la matrice sans rattacher à nouveau un use-case abandonné à une maquette cible.

## 4. Vérifier l'intégration du changement

- [ ] 4.1 Exécuter `openspec validate --change "retirer-referentiel-fournisseur"` et corriger toute erreur de structure ou de scénario.
- [ ] 4.2 Exécuter `pwsh -File scripts/check-legacy-coverage.ps1` et vérifier qu'aucune descente incomplète n'est créée pour `UC-ART-4`.
- [ ] 4.3 Relire les deltas, les maquettes et la matrice ensemble pour confirmer qu'aucun écran ou calcul ne dépend d'une identité fournisseur et que le prix d'achat reste couvert.