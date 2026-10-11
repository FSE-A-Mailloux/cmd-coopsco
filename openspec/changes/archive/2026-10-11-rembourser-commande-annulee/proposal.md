## Why

L'avoir corrige le document de facturation mais ne restitue pas le montant deja encaisse. Les gestionnaires doivent pouvoir enregistrer et suivre le remboursement manuel d'une commande annulee, quel que soit son moyen de paiement.

## What Changes

- Ajouter un parcours de remboursement manuel disponible apres l'annulation avec avoir d'une commande reglee.
- Permettre l'enregistrement d'un ou plusieurs remboursements, sans depasser le total des paiements conserves en historique.
- Tracer pour chaque remboursement le montant, le moyen, la date, la reference et le gestionnaire responsable.
- Definir les contraintes d'integrite, d'atomicite, de concurrence et d'autorisation applicables a la future implementation.
- Afficher l'etat rembourse, partiellement rembourse ou restant a rembourser dans le detail de commande.
- Ajouter les maquettes de saisie et de consultation des remboursements.

## Capabilities

### New Capabilities

<!-- Aucune. -->

### Modified Capabilities

- `fse-order-and-payment-workflow`: definir l'enregistrement et le suivi des remboursements manuels d'une commande annulee.
- `frontend-reference-screen-flows`: ajouter le parcours gestionnaire de remboursement depuis le detail d'une commande annulee.

## Impact

- Workflow de commande, paiement et facturation OpenSpec.
- Maquette de detail de commande et ecran de saisie de remboursement.
- Le livrable de ce changement est la specification detaillee et ses maquettes de reference ; il ne comprend pas l'implementation de l'application. Les exigences serviront de contrat pour l'application cible, dont le code est gere hors de ce depot.
