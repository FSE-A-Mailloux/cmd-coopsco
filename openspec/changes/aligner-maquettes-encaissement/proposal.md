## Why

Les maquettes d'encaissement et de navigation presentent des incoherences qui ralentissent la validation fonctionnelle (ecran redondant, parcours peu direct, lisibilite de navigation).  
Il faut formaliser ces retours en regles OpenSpec avant de poursuivre les modifications des maquettes.

## What Changes

- Rendre la navigation des maquettes plus fluide avec un scroll independant de la liste des ecrans.
- Supprimer la dependance a un ecran de recherche d'encaissement dedie et demarrer l'encaissement depuis la commande.
- Etendre la recherche gestionnaire de commandes au numero de commande.
- Ajouter une action d'encaissement (cheque/espece) sur les commandes confirmees dans la liste gestionnaire.
- Ajuster l'ecran de saisie paiements: beneficiaire visible, ajout de paiement rapproche du tableau, suppression du bouton de recherche, clarifications sur le titulaire du compte pour les paiements cheque.

## Capabilities

### New Capabilities
- _Aucune_

### Modified Capabilities
- `frontend-reference-screen-flows`: ajuster les regles de navigation liste/apercu, la recherche et les actions d'encaissement, et le contenu attendu de l'ecran de saisie paiements.
- `frontend-shell-and-dynamic-components`: aligner le parcours shell gestionnaire pour ouvrir directement la saisie paiements depuis les actions de commande.

## Impact

- Met a jour les deltas OpenSpec de reference pour les maquettes frontend.
- Impose une mise a jour des maquettes gestionnaire sur le parcours encaissement.
- Reduit les ambiguities de parcours avant implementation fonctionnelle.
