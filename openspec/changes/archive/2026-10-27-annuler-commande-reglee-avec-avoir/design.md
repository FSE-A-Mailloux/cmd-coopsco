## Context

Voir `proposal.md` pour la motivation et les spécifications delta pour le contrat fonctionnel. La cible ne contient pas encore de code applicatif. Le legacy annule une commande en positionnant un indicateur, sans produire de document correctif, et distingue cette action de l'annulation d'une ligne de règlement.

## Goals / Non-Goals

**Goals:**
- Produire une annulation cohérente de bout en bout, avec facture et avoir lorsque la commande réglée n'est pas encore facturée.
- Assurer la traçabilité et l'unicité du couple facture-avoir pour une commande annulée.
- Rendre le résultat et l'avoir accessibles depuis le parcours de commande.

**Non-Goals:**
- Déclencher un remboursement auprès du prestataire de paiement.
- Corriger ou annuler une ligne d'encaissement erronée sans annulation commerciale.
- Modifier le référentiel de statuts de commande.

## Décisions

### Décision 1 - Finaliser les documents avant le changement d'état

L'annulation d'une commande avec paiement enregistré exécute une opération atomique: retrouver ou produire la facture, produire l'avoir, puis marquer la commande annulée. Toute erreur de génération ou de persistance annule l’opération complète.

Ce choix évite une commande annulée sans pièce corrective. Marquer d'abord la commande annulée exposerait un état non conforme en cas d'échec documentaire.

L'alternative consistant à générer l'avoir de manière asynchrone après l'annulation est écartée: elle introduit une période où la commande annulée n'a pas de document correctif.

### Décision 2 - Facturer puis avoirer les commandes réglées non facturées

Si une commande réglée n'a pas de facture, le système produit sa facture avant de produire l'avoir. L'avoir est toujours rattaché à une facture, conformément à la décision produit prise pour ce changement.

Refuser l'annulation tant que la facture n'existe pas ajouterait un blocage opérationnel. Annuler sans avoir ne satisferait pas le besoin de traçabilité documentaire.

### Décision 3 - Distinguer l'annulation commerciale de la correction d'encaissement

L'annulation commerciale conserve les paiements comme faits historiques et crée la pièce corrective. Elle ne réutilise pas le mécanisme de correction d'une ligne de règlement, qui demeure hors périmètre.

Fusionner les deux parcours reproduirait l'ambiguïté du legacy et ferait dépendre la correction d'une saisie erronée d'une annulation de commande.

### Décision 4 - Garantir l'idempotence de l'annulation

La commande porte une référence unique vers sa facture et son avoir. Une demande répétée après une annulation réussie retourne le résultat existant sans produire de nouveau document.

Cette contrainte protège contre les doubles soumissions de l'interface et les répétitions de requêtes.

## Risks / Trade-offs

- **[Risque]** Une facture est produite uniquement pour être immédiatement créditée. **→ Mitigation:** le lien facture-avoir et leur conservation rendent la séquence explicite et traçable.
- **[Risque]** Le remboursement du paiement est attendu par les utilisateurs. **→ Mitigation:** indiquer clairement dans le parcours que l'annulation émet l'avoir ; le remboursement reste un processus distinct.
- **[Risque]** Deux demandes concurrentes produisent deux avoirs. **→ Mitigation:** imposer une contrainte d'unicité sur l'avoir par commande et traiter la transition dans une transaction.

## Migration Plan

1. Ajouter les structures de persistance des documents de facture et d'avoir avec leurs liens de traçabilité.
2. Implémenter la transaction d'annulation et la production des documents.
3. Exposer l'annulation et le téléchargement de l'avoir dans le parcours commande.
4. Déployer les maquettes et les tests de transitions avant d'activer l'action pour les commandes réglées.

## Open Questions

Aucune.
