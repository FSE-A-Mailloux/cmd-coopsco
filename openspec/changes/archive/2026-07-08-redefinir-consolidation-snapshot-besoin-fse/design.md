## Context

L'ecran `Consolidation` actuel est une synthese globale periodisee, orientee KPI generiques, et ne porte pas explicitement la decision de commande fournisseur.  
Le besoin metier cible une vue de travail article par article, figee a un instant donne, sur une periode choisie, avec saisie du besoin FSE (stock a conserver) et calcul de la quantite a commander.

Contraintes:
- conserver un parcours simple pour le gestionnaire (pas de workflow d'approbation complexe),
- maintenir une consultation ulterieure des consolidations enregistrees,
- afficher les indicateurs financiers seulement quand les donnees tarifaires le permettent.

## Goals / Non-Goals

**Goals:**
- Transformer la consolidation en snapshot horodate par periode.
- Permettre la saisie du besoin FSE par article dans la consolidation.
- Calculer la quantite a commander fournisseur pour chaque article.
- Permettre l'enregistrement et la consultation historique des consolidations.
- Afficher `CA estime`, `Marge estimee`, `Cout du stock prevu (besoin FSE)` sur la periode en cours quand les tarifs fournisseur HT sont renseignes.
- Retirer toute notion de taux de service de l'ecran `Consolidation`.

**Non-Goals:**
- Implementer l'integration effective de commande fournisseur (hors perimetre de cette iteration).
- Definir un moteur previsionnel avance (saisonnalite, IA, simulation multi-hypotheses).
- Revoir les regles de tarification des articles au-dela des usages de calcul d'estimation.

## Decisions

1. Consolidation comme instantane horodate
   - Decision: une consolidation est un etat sauvegardable portant une periode cible et un horodatage.
   - Alternatives rejetees: vue toujours "temps reel" sans historisation; rejetee car ne permet pas de tracer la decision de commande.

2. Saisie du besoin FSE par article
   - Decision: la ligne article inclut un champ de saisie du besoin FSE (stock a conserver).
   - Alternatives rejetees: besoin FSE global au niveau periode; rejete car trop imprécis pour la commande fournisseur.

3. Regle de calcul de quantite a commander
   - Decision: calcul par article `quantite_a_commander = max(0, quantite_commandee_familles + besoin_fse - stock_disponible)`.
   - Alternatives rejetees: autoriser des quantites negatives; rejete pour eviter des interpretations ambiguës.

4. Indicateurs financiers conditionnels
   - Decision: afficher `CA estime`, `Marge estimee` et `Cout du stock prevu (besoin FSE)` uniquement avec base tarifaire disponible; sinon afficher un etat "estimation partielle/incomplete".
   - Alternatives rejetees: masquer silencieusement les indicateurs; rejetee pour conserver la transparence metier.

5. Retrait du taux de service
   - Decision: supprimer KPI/colonne `Taux service` de l'ecran `Consolidation`.
   - Alternatives rejetees: conserver pour historique; rejetee car cet indicateur n'a pas de valeur metier dans ce contexte.

## Risks / Trade-offs

- [Donnees tarif fournisseur HT incompletes] -> Mitigation: indicateurs financiers calcules partiellement avec signal explicite de couverture.
- [Ecarts de comprehension sur la formule du besoin a commander] -> Mitigation: expliciter la formule dans la spec et dans les libelles de maquette.
- [Confusion entre snapshot enregistre et vue de travail en cours] -> Mitigation: differencier visuellement "brouillon courant" et "consolidations enregistrees (horodatees)".

## Migration Plan

1. Mettre a jour les deltas specs `stock-and-supplier-consolidation` et `frontend-reference-screen-flows`.
2. Adapter la maquette `admin-consolidation.html` (table article, actions d'enregistrement, historique, indicateurs financiers).
3. Mettre a jour la documentation maquettes.
4. Valider la change OpenSpec.

## Open Questions

- Le calcul de marge estimee doit-il exclure les articles sans tarif fournisseur HT ou bloquer l'indicateur complet?
- Faut-il autoriser plusieurs snapshots pour une meme periode dans une meme journee sans contrainte?
