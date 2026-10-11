## 1. Parcours gestionnaire (perimetre du depot)

- [x] 1.1 Mettre a jour `maquettes/screens/commandes/commandes-detail.html` pour presenter l'avoir, les montants rembourses et restant, l'historique des remboursements et l'action de remboursement ; verifier le rendu statique d'une commande annulee.
- [x] 1.2 Ajouter une maquette de saisie de remboursement avec le numero de commande, le montant restant, le montant, le moyen, la date et la reference ; declarer l'ecran dans les deux manifestes et verifier qu'il est expose dans le parcours gestionnaire.

## 2. Specifications detaillees du comportement cible

- [x] 2.1 Definir dans `fse-order-and-payment-workflow` les donnees a conserver pour chaque remboursement, les liens obligatoires vers commande, avoir et gestionnaire, les regles d'integrite, le montant positif et la precision monetaire ; verifier les cas de liens absents, invalides ou incoherents.
- [x] 2.2 Definir dans `fse-order-and-payment-workflow` le calcul du montant remboursable et du solde, les paiements a inclure ou exclure, l'atomicite, la protection contre les soumissions concurrentes et les resultats attendus pour les cas partiels, totaux, hors plafond et non eligibles.
- [x] 2.3 Definir dans `fse-order-and-payment-workflow` les regles d'autorisation d'enregistrement et de consultation, l'origine authentifiee du gestionnaire responsable, les refus attendus et l'immutabilite de l'historique ; verifier les scenarios utilisateur non habilite et famille.

## 3. Coherence et validation

- [x] 3.1 Executer `openspec validate rembourser-commande-annulee --strict`, `pwsh -File scripts/check-legacy-coverage.ps1` et les controles existants des maquettes/manifeste ; verifier les liens entre les deux ecrans.
