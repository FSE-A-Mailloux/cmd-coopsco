## Why

La cible interdit aujourd'hui l'annulation dès qu'un paiement est enregistre, alors que le legacy permet l'annulation d'une commande reglee sans produire de piece corrective. Ce parcours doit etre rendu possible et conforme au besoin d'emission d'un avoir rattache a la commande annulee.

## What Changes

- Autoriser l'annulation d'une commande ayant un ou plusieurs paiements enregistres.
- Produire un avoir trace et telechargeable lors de l'annulation d'une commande reglee ; lorsqu'aucune facture n'existe encore, produire d'abord la facture puis l'avoir.
- Remplacer dans les parcours de commandes la regle d'interdiction d'annulation apres paiement par un parcours d'annulation avec confirmation et restitution de l'avoir.
- Distinguer l'annulation commerciale de la commande de la correction d'une saisie d'encaissement ; cette derniere reste hors perimetre tant que son besoin n'est pas cadre.
- Produire les maquettes associees au parcours d'annulation et a la restitution de l'avoir.

## Capabilities

### New Capabilities

<!-- Aucune. -->

### Modified Capabilities

- `fse-order-and-payment-workflow`: definir l'annulation d'une commande reglee et l'emission de l'avoir associe.
- `frontend-reference-screen-flows`: remplacer l'interdiction d'annulation apres paiement par le parcours d'annulation avec avoir.

## Impact

- Specifications OpenSpec du workflow commande, reglement et facturation, ainsi que des parcours de reference frontend.
- Maquettes de liste et de detail de commande.
- Future implementation du domaine commande, de la generation documentaire et des donnees de facturation.
