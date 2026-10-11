## Context

Voir `proposal.md` pour la motivation et les specifications delta pour le contrat fonctionnel. La cible ne contient pas encore de code applicatif. Le legacy annule une commande en positionnant un indicateur, sans produire de document correctif, et distingue cette action de l'annulation d'une ligne de reglement.

## Goals / Non-Goals

**Goals:**
- Produire une annulation coherente de bout en bout, avec facture et avoir lorsque la commande reglee n'est pas encore facturee.
- Assurer la tracabilite et l'unicite du couple facture-avoir pour une commande annulee.
- Rendre le resultat et l'avoir accessibles depuis le parcours de commande.

**Non-Goals:**
- Declencher un remboursement aupres du prestataire de paiement.
- Corriger ou annuler une ligne d'encaissement erronee sans annulation commerciale.
- Modifier le referentiel de statuts de commande.

## Decisions

### Decision 1 - Finaliser les documents avant le changement d'etat

L'annulation d'une commande avec paiement enregistre execute une operation atomique: retrouver ou produire la facture, produire l'avoir, puis marquer la commande annulee. Toute erreur de generation ou de persistance annule l'operation complete.

Ce choix evite une commande annulee sans piece corrective. Marquer d'abord la commande annulee exposerait un etat non conforme en cas d'echec documentaire.

L'alternative consistant a generer l'avoir de maniere asynchrone apres l'annulation est ecartee: elle introduit une periode ou la commande annulee n'a pas de document correctif.

### Decision 2 - Facturer puis avoirer les commandes reglees non facturees

Si une commande reglee n'a pas de facture, le systeme produit sa facture avant de produire l'avoir. L'avoir est toujours rattache a une facture, conformement a la decision produit prise pour ce changement.

Refuser l'annulation tant que la facture n'existe pas ajouterait un blocage operationnel. Annuler sans avoir ne satisferait pas le besoin de tracabilite documentaire.

### Decision 3 - Distinguer l'annulation commerciale de la correction d'encaissement

L'annulation commerciale conserve les paiements comme faits historiques et cree la piece corrective. Elle ne reutilise pas le mecanisme de correction d'une ligne de reglement, qui demeure hors perimetre.

Fusionner les deux parcours reproduirait l'ambiguite du legacy et ferait dependre la correction d'une saisie erronee d'une annulation de commande.

### Decision 4 - Garantir l'idempotence de l'annulation

La commande porte une reference unique vers sa facture et son avoir. Une demande repetee apres une annulation reussie retourne le resultat existant sans produire de nouveau document.

Cette contrainte protege contre les doubles soumissions de l'interface et les repetitions de requetes.

## Risks / Trade-offs

- **[Risque]** Une facture est produite uniquement pour etre immediatement creditee. **→ Mitigation:** le lien facture-avoir et leur conservation rendent la sequence explicite et tracable.
- **[Risque]** Le remboursement du paiement est attendu par les utilisateurs. **→ Mitigation:** indiquer clairement dans le parcours que l'annulation emet l'avoir ; le remboursement reste un processus distinct.
- **[Risque]** Deux demandes concurrentes produisent deux avoirs. **→ Mitigation:** imposer une contrainte d'unicite sur l'avoir par commande et traiter la transition dans une transaction.

## Migration Plan

1. Ajouter les structures de persistance des documents de facture et d'avoir avec leurs liens de tracabilite.
2. Implementer la transaction d'annulation et la production des documents.
3. Exposer l'annulation et le telechargement de l'avoir dans le parcours commande.
4. Deployer les maquettes et les tests de transitions avant d'activer l'action pour les commandes reglees.

## Open Questions

Aucune.
