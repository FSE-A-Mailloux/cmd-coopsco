## Context

Le pilotage des periodes est decrit dans les specs metier, mais les regles d'eligibilite des actions dans `Periodes - Listing et actions` restent trop implicites pour garantir un comportement uniforme.  
En parallele, le referentiel des ecrans ne couvre pas encore explicitement la creation/modification de periode, et la contrainte responsive des tableaux de listing n'est pas generalisee.

## Goals / Non-Goals

**Goals:**
- Rendre explicites les conditions d'activation des actions `Ouvrir` et `Definir en cours`.
- Confirmer l'invariant metier "une seule periode en cours".
- Completer le parcours ecran periodes avec detail + creation + modification.
- Exiger une representation responsive pour tous les tableaux de listing.

**Non-Goals:**
- Modifier les regles de tarification, de reprise des tarifs ou de cotisation.
- Introduire des choix techniques imposes (framework, composant de tableau, librairie CSS).
- Redefinir le modele de donnees periode au-dela des contraintes de statut et de disponibilite des actions.

## Decisions

1. Regles metier centralisees dans `period-catalog-and-pricing-management`
   - Decision: formaliser les invariants "unicite periode en cours", "ouverture reservee a la periode en cours", "bascule en cours reservee aux periodes non courantes".
   - Alternative rejetee: ne porter ces regles que dans les specs frontend; rejetee pour eviter un couplage UI et conserver un invariant metier transversal.

2. Disponibilite des actions explicite dans `frontend-reference-screen-flows`
   - Decision: specifier les etats de boutons en listing periodes (actif/inactif selon le statut courant) et completer le detail avec l'action de modification.
   - Alternative rejetee: conserver une formulation generique "actions d'etat disponibles"; rejetee car trop ouverte a interpretation.

3. Couverture ecran complete du domaine periodes
   - Decision: ajouter explicitement les ecrans `Creation de periode` et `Modification de periode` dans les requirements frontend.
   - Alternative rejetee: inferrer ces ecrans depuis les actions sans requirement dedie; rejetee car la couverture ecran devient non testable.

4. Responsive listing en requirement transversal frontend
   - Decision: imposer la lisibilite et l'exploitabilite des tableaux de listing sur telephone et desktop.
   - Alternative rejetee: laisser chaque listing traiter son responsive au cas par cas; rejetee pour eviter des divergences UX.

## Risks / Trade-offs

- [Risque de confusion entre "active" et "en cours"] -> Mitigation: utiliser dans les scenarios la terminologie `periode en cours` de maniere uniforme.
- [Risque de surcontrainte visuelle sur les listings] -> Mitigation: exprimer des criteres fonctionnels de lisibilite et interaction, sans imposer de pattern technique unique.
- [Risque d'incoherence temporaire entre maquettes existantes et nouvelles specs] -> Mitigation: prevoir des taches de mise a jour ciblees des ecrans periodes et des listings principaux.

## Migration Plan

1. Mettre a jour les deltas specs pour les capacites ciblees.
2. Aligner les maquettes `Periodes - Listing et actions`, `Periodes - Detail`, puis ajouter les ecrans creation/modification.
3. Verifier la conformite des ecrans de listing a la contrainte responsive.
4. Valider la change OpenSpec avant implementation.

## Open Questions

- Les listes periodes peuvent-elles afficher explicitement les periodes verrouillees avec actions d'etat desactivees, ou faut-il les masquer?
- La creation de periode doit-elle proposer une duplication optionnelle des parametres de la periode precedente des la creation, ou seulement apres creation?
