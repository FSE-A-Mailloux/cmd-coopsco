## 1. Mise a jour de la maquette Stock

- [x] 1.1 Retirer les KPIs `articles en alerte` et `entrepots suivis` de l'ecran `Stock`
- [x] 1.2 Retirer la colonne `seuil mini` du tableau de listing `Stock`
- [x] 1.3 Ajouter une action par article pour visualiser la courbe d'evolution du stock
- [x] 1.4 Ajouter une action/modale d'edition par article pour mettre a jour l'etat de stock et le nombre disponible

## 2. Alignement spec et documentation

- [x] 2.1 Appliquer les nouvelles regles `Stock` dans les deltas specs `frontend-reference-screen-flows` et `stock-and-supplier-consolidation`
- [x] 2.2 Mettre a jour la documentation maquettes pour refleter le recentrage de l'ecran `Stock`

## 3. Validation OpenSpec

- [x] 3.1 Valider la change `renforcer-ecran-stock-courbe-etat` avec OpenSpec

## 4. Ajustement historique stock par date de modification

- [x] 4.1 Adapter la maquette `Stock` pour presenter la courbe sur les dates de modification de stock
- [x] 4.2 Mettre a jour les deltas specs `frontend-reference-screen-flows` et `stock-and-supplier-consolidation` pour preciser la base temporelle par date de modification

## 5. Ajustement calcul automatique de l'etat de stock

- [x] 5.1 Adapter la maquette `Stock` pour ne modifier que le nombre disponible et afficher l'etat calcule automatiquement
- [x] 5.2 Mettre a jour les deltas specs pour imposer le calcul de l'etat (`Rupture` si disponible = 0, sinon `Disponible`)
