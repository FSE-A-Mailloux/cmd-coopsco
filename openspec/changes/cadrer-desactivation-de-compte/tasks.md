## 1. Spécifications techniques de la future implémentation

- [x] 1.1 Documenter dans `design.md` l’inventaire des entités et attributs liés au compte observés dans le legacy, leur traitement attendu et les limites de l’inventaire; vérifier les correspondances avec les sources citées et signaler les données non vérifiables.
- [x] 1.2 Spécifier dans `design.md` le contrat d’autorisation, de validation du mot de passe, de confirmation, de révocation de session et d’erreur sans mutation; vérifier que chaque cas a un critère d’acceptation.
- [x] 1.3 Spécifier l’ordre transactionnel, le traitement de chaque donnée et les vérifications de succès/rollback dans `design.md`; vérifier que les commandes sont conservées et anonymisées tandis que les accès sont révoqués.

## 2. Maquettes du parcours

- [x] 2.1 Créer la maquette de suppression et l’ajouter au manifest; montrer le récapitulatif, la nouvelle saisie du mot de passe, la confirmation irréversible et le résultat, séparément de la désinscription aux communications; vérifier le JSON du manifest et les références des fichiers.

## 3. Documentation de cadrage

- [x] 3.1 Corriger UC-AUTH-8 et la matrice de couverture à partir du traitement legacy observé (`comGroupe_uti = 0`) et du comportement cible spécifié; vérifier que le legacy et la cible ne sont plus confondus.
- [x] 3.2 Mettre à jour le PRD pour consigner les arbitrages rendus sur suppression définitive, confirmation, anonymisation des commandes et distinction du désabonnement; vérifier qu’aucune question de cadrage résolue ne demeure marquée en attente.
