## Why

La cible interdit aujourd'hui l'annulation dès qu'un paiement est enregistré, alors que le legacy permet l'annulation d'une commande réglée sans produire de pièce corrective. Ce parcours doit être rendu possible et conforme au besoin d'émission d'un avoir rattaché à la commande annulée.

## What Changes

- Autoriser l'annulation d'une commande ayant un ou plusieurs paiements enregistrés.
- Produire un avoir tracé et téléchargeable lors de l'annulation d'une commande réglée ; lorsqu'aucune facture n'existe encore, produire d'abord la facture puis l'avoir.
- Remplacer dans les parcours de commandes la règle d'interdiction d'annulation après paiement par un parcours d'annulation avec confirmation et restitution de l'avoir.
- Distinguer l'annulation commerciale de la commande de la correction d'une saisie d'encaissement ; cette dernière reste hors périmètre tant que son besoin n'est pas cadré.
- Produire les maquettes associées au parcours d'annulation et à la restitution de l'avoir.

## Capabilities

### New Capabilities

<!-- Aucune. -->

### Modified Capabilities

- `fse-order-and-payment-workflow`: définir l'annulation d'une commande réglée et l'émission de l'avoir associé.
- `frontend-reference-screen-flows`: remplacer l'interdiction d'annulation après paiement par le parcours d'annulation avec avoir.

## Impact

- Spécifications OpenSpec du workflow commande, règlement et facturation, ainsi que des parcours de référence frontend.
- Maquettes de liste et de détail de commande.
- Future implémentation du domaine commande, de la génération documentaire et des données de facturation.
