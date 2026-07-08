## Context

Le formulaire famille de creation de commande contient une `Etape 5 - Mode de paiement` avec choix CB, cheque ou espece.
Le libelle du bouton final doit traduire correctement l'action attendue: paiement immediat pour CB, confirmation de commande pour les modes hors CB.

## Goals / Non-Goals

**Goals:**
- Definir une regle unique de mapping entre mode de paiement selectionne et libelle du bouton final.
- Garantir une mise a jour immediate du libelle lors d'un changement de radio bouton.
- Aligner la formulation sur l'intention metier du parcours de paiement.

**Non-Goals:**
- Modifier les modes de paiement disponibles.
- Changer les notices explicatives cheque/espece.
- Redefinir le flux de paiement HelloAsso.

## Decisions

1. Deriver le libelle depuis l'etat du mode selectionne
   - Decision: le libelle est calcule depuis la valeur du radio bouton actif, sans logique dupliquee dans plusieurs composants.
   - Alternatives rejetees: libelles statiques par ecran ou hardcode conditionnel disperse; rejetees pour eviter les incoherences.

2. Utiliser deux familles de libelles
   - Decision: CB utilise un libelle de paiement explicite (`Payer la commande`), cheque/espece utilisent un libelle de confirmation.
   - Alternatives rejetees: un libelle unique pour tous les modes; rejetee car ambigu pour CB.

3. Mettre a jour le libelle sur chaque changement de selection
   - Decision: l'evenement de changement des radio boutons met a jour immediatement le libelle.
   - Alternatives rejetees: recalcul uniquement a la soumission; rejetee car retour utilisateur trop tardif.

## Risks / Trade-offs

- [Risque de regression UX si un nouveau mode est ajoute sans mapping] -> Mitigation: centraliser le mapping mode -> libelle et couvrir les cas CB/cheque/espece dans les specs.
- [Risque de divergence de vocabulaire entre maquette et implementation] -> Mitigation: figer la formulation attendue dans le delta spec.

## Migration Plan

1. Mettre a jour le delta spec `frontend-reference-screen-flows`.
2. Adapter la maquette de l'etape 5 pour refleter le libelle dynamique du bouton final.
3. Verifier le comportement de bascule de libelle entre CB, cheque et espece.

## Open Questions

- Le libelle de confirmation hors CB doit-il etre `Confirmer la commande` ou conserver le libelle actuel existant si deja valide metier?
