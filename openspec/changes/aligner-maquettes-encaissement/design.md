## Context

Le referentiel de maquettes sert de base de cadrage fonctionnel pour la reecriture.  
Les retours metier montrent trois points de friction sur le parcours encaissement gestionnaire:
- une navigation maquettes qui devient lourde quand la liste d'ecrans est longue,
- un ecran `Encaissement - Recherche commande` redondant avec `Liste des commandes (gestionnaire)`,
- une saisie de paiements qui ne montre pas assez clairement le contexte commande/famille.

## Goals / Non-Goals

**Goals:**
- Rendre explicite le comportement attendu de navigation liste/apercu avec un scroll independant.
- Simplifier le parcours d'encaissement en le declenchant depuis les ecrans commande existants.
- Clarifier les informations et actions minimales de l'ecran `Encaissement - Saisie paiements`.

**Non-Goals:**
- Modifier le workflow metier de paiement (statuts et regles de transition) au-dela du parcours ecran.
- Introduire un nouveau mode de paiement ou de nouvelles regles comptables.
- Definir des choix techniques d'implementation UI (framework, composants, librairies).

## Decisions

1. Entree d'encaissement par la commande existante
   - Decision: l'encaissement cheque/espece est demarre depuis une action sur la commande (liste ou detail), sans ecran de recherche dedie.
   - Alternatives considerees:
     - Conserver un ecran de recherche dedie: rejete car redondant avec la liste commandes.
     - Ajouter un hub encaissement intermediaire: rejete car ajoute une etape sans valeur metier.

2. Recherche commande gestionnaire etendue
   - Decision: le champ de recherche unique couvre aussi le numero de commande.
   - Alternatives considerees:
     - Champ separe "numero de commande": rejete pour eviter une UX a double recherche.

3. Saisie paiements contextualisee
   - Decision: l'ecran de saisie affiche le beneficiaire de la commande, place l'action d'ajout de paiement au plus proche du tableau, et retire les elements de recherche non pertinents.
   - Alternatives considerees:
     - Garder une recherche locale dans la saisie: rejete car l'ecran est deja contextualise par la commande source.

4. Convention cheque orientee contexte famille
   - Decision: le titulaire du compte pour un cheque est traite comme un particulier/famille (et non une organisation).
   - Alternatives considerees:
     - Champ generic "titulaire": rejete car trop ambigu pour le besoin metier vise.

## Risks / Trade-offs

- [Risque de confusion temporaire pour les utilisateurs habitues a un ecran d'entree encaissement dedie] -> Mitigation: expliciter l'action `Encaisser` dans la liste/detail commande.
- [Risque d'interpretations differentes sur les commandes eligibles a l'action `Encaisser`] -> Mitigation: ancrer le scenario sur les commandes `Confirmee`.
- [Risque de divergence entre maquettes et specs] -> Mitigation: centraliser ces regles dans les deltas OpenSpec avant toute nouvelle iteration visuelle.

## Migration Plan

1. Mettre a jour les deltas OpenSpec pour les capacites frontend ciblees.
2. Mettre a jour les maquettes de navigation et d'encaissement en suivant les deltas.
3. Valider les specs OpenSpec.

## Open Questions

- Faut-il autoriser l'action `Encaisser` uniquement en statut `Confirmee`, ou egalement dans un sous-ensemble de statuts en attente de paiement?
- Le beneficiaire affiche dans la saisie doit-il presenter un seul enfant principal ou la liste complete des beneficiaires de la commande?
