## Why

L'etape 5 `Mode de paiement` reutilise actuellement un libelle de bouton final identique quel que soit le mode choisi, ce qui cree une ambiguite pour le cas CB.
Le parcours doit expliciter qu'un paiement CB declenche une action de paiement, alors que cheque/espece restent une confirmation de commande.

## What Changes

- Preciser le comportement du libelle du bouton final a l'etape 5 selon le mode de paiement selectionne.
- Aligner les maquettes et le contrat fonctionnel sur une formulation orientee action:
  - CB: libelle de type `Payer la commande`
  - Cheque / espece: libelle de confirmation de commande
- Encadrer la regle de mise a jour immediate du libelle lorsque le radio bouton change.

## Capabilities

### New Capabilities
- Aucune.

### Modified Capabilities
- `frontend-reference-screen-flows`: ajout d'une regle sur le libelle contextuel du bouton final de l'etape 5 `Mode de paiement`.

## Impact

- Mise a jour du delta spec `frontend-reference-screen-flows`.
- Mise a jour des maquettes/formulaires de creation de commande famille (etape 5) pour refleter le libelle dynamique.
- Aucun impact API/backend attendu.
