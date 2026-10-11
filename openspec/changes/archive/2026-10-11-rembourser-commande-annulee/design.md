## Context

Voir `proposal.md` pour la motivation et les specifications delta pour le contrat fonctionnel. Le changement `annuler-commande-reglee-avec-avoir` produit l'avoir mais n'organise pas le retour des fonds. Le remboursement doit rester independant du prestataire de paiement, car il est execute manuellement par un gestionnaire pour tout moyen de paiement.

Le livrable de ce changement est constitue des specifications fonctionnelles detaillees et des maquettes de reference ; il ne comprend pas la realisation d'une application. Le depot ne contient pas le code applicatif cible ni son schema de persistance. Les exigences de persistance, transaction et autorisation decrivent donc le comportement attendu de la future implementation, sans prescrire un schema physique ou une technologie de base de donnees.

## Goals / Non-Goals

**Goals:**
- Enregistrer des remboursements manuels partiels ou complets sans depasser les paiements historiques de la commande.
- Rendre chaque remboursement attribuable a un gestionnaire et consultable depuis la commande.
- Ne rendre l'action disponible qu'apres l'annulation avec avoir.

**Non-Goals:**
- Initier un remboursement automatique aupres d'un prestataire de paiement.
- Modifier ou annuler une ligne de reglement legacy.
- Automatiser le rapprochement bancaire, la remise de cheque ou la sortie d'especes.

## Decisions

### Decision 1 - Conserver les remboursements comme mouvements distincts

Chaque remboursement est un mouvement immutable associe a une commande annulee et a son avoir. Son montant est deduit du montant total regle pour calculer le reste a rembourser, sans modifier les paiements d'origine.

Ce choix preserve la piste d'audit et distingue le retour de fonds de la correction d'un encaissement. Modifier les paiements historiques masquerait le montant effectivement encaisse et la raison du remboursement.

### Decision 2 - Autoriser les remboursements partiels avec plafond transactionnel

Un gestionnaire peut saisir plusieurs remboursements, a condition que leur somme ne depasse jamais le montant total regle. Le controle est realise dans la transaction qui enregistre le mouvement pour resister aux soumissions concurrentes.

Imposer un remboursement unique simplifierait l'interface mais ne couvre pas les remboursements par plusieurs moyens ou en plusieurs echeances.

### Decision 3 - Remboursement manuel pour tous les moyens

L'interface enregistre le moyen effectivement utilise, y compris carte bancaire, cheque et especes, mais ne contacte aucun prestataire. La reference obligatoire permet de rattacher l'action a la preuve operationnelle ou comptable correspondante.

Une integration automatique CB aurait des dependances, des droits et des modes d'echec propres au prestataire ; elle sera traitee par un changement dedie si elle est retenue.

## Risks / Trade-offs

- **[Risque]** Un gestionnaire enregistre un remboursement qui n'a pas ete execute hors application. **→ Mitigation:** exiger une reference et le gestionnaire responsable, puis rendre le mouvement consultable et auditable.
- **[Risque]** Deux gestionnaires remboursent simultanement le meme solde. **→ Mitigation:** controler le plafond dans une transaction et imposer l'integrite des montants en persistance.
- **[Risque]** Un remboursement errone exige une correction. **→ Mitigation:** conserver les mouvements sans modification ; cadrer un parcours correctif trace dans un changement ulterieur.

## Plan d'implementation de l'application cible (hors perimetre de ce changement)

1. Ajouter la persistance des mouvements et les contraintes d'integrite conformement a la spec `fse-order-and-payment-workflow`.
2. Implementer le calcul du solde et son controle atomique/concurrentiel avec les regles d'eligibilite.
3. Appliquer les habilitations cote serveur et attribuer chaque mouvement a l'identite authentifiee.
4. Implementer les ecrans de reference et verifier les scenarios fonctionnels specifies ; ces travaux applicatifs feront l'objet de taches dediees dans le depot de l'application cible.
