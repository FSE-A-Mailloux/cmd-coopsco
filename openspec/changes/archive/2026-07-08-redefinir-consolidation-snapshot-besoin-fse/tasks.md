## 1. Refonte de la maquette Consolidation

- [x] 1.1 Remplacer la synthese actuelle de `admin-consolidation.html` par une vue de consolidation par periode, sans taux de service
- [x] 1.2 Ajouter un tableau article avec stock, quantite commandee familles, saisie besoin FSE et quantite a commander calculee
- [x] 1.3 Ajouter les actions d'enregistrement de consolidation horodatee et une zone de consultation des consolidations enregistrees

## 2. Indicateurs financiers de consolidation

- [x] 2.1 Afficher `CA estime`, `Marge estimee` et `Cout du stock prevu (besoin FSE)` pour la periode en cours
- [x] 2.2 Signaler explicitement l'etat d'estimation partielle si des tarifs fournisseur HT sont manquants

## 3. Alignement documentaire et validation

- [x] 3.1 Mettre a jour `maquettes/README.md` avec les nouvelles regles de l'ecran `Consolidation`
- [x] 3.2 Valider la change `redefinir-consolidation-snapshot-besoin-fse` avec OpenSpec

## 4. Ajustement periode de consolidation non selectable

- [x] 4.1 Adapter la maquette `Consolidation` pour afficher uniquement un libelle de periode en cours, sans selecteur
- [x] 4.2 Mettre a jour les deltas specs de la change pour imposer l'absence de choix manuel de periode
- [x] 4.3 Revalider la change `redefinir-consolidation-snapshot-besoin-fse` avec OpenSpec
