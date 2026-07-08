## 1. Mise a jour des regles de navigation maquettes

- [x] 1.1 Mettre a jour la capacite `frontend-reference-screen-flows` pour imposer un scroll independant de la liste d'ecrans par rapport au panneau d'apercu
- [x] 1.2 Verifier que les scenarios de navigation liste/apercu restent testables et non ambigus

## 2. Alignement du parcours encaissement gestionnaire

- [x] 2.1 Mettre a jour la capacite `frontend-reference-screen-flows` pour declencher l'encaissement depuis les actions commande (liste/detail), sans ecran de recherche dedie
- [x] 2.2 Etendre la regle de recherche de la liste des commandes gestionnaire au numero de commande
- [x] 2.3 Ajouter la regle d'action `Encaisser` pour les commandes confirmees dans la liste gestionnaire

## 3. Clarification de l'ecran de saisie paiements

- [x] 3.1 Mettre a jour la regle de contenu de `Encaissement - Saisie paiements` avec affichage du beneficiaire de commande
- [x] 3.2 Imposer un positionnement de l'action d'ajout de paiement au plus proche du tableau des paiements
- [x] 3.3 Retirer la recherche depuis l'ecran de saisie paiements et conserver un contexte de commande preselectionne
- [x] 3.4 Precisser la saisie du titulaire de compte cheque dans un contexte particulier/famille

## 4. Validation OpenSpec de la change

- [x] 4.1 Executer la validation OpenSpec de `aligner-maquettes-encaissement`
- [x] 4.2 Corriger les erreurs de structure eventuelles jusqu'a validation complete
