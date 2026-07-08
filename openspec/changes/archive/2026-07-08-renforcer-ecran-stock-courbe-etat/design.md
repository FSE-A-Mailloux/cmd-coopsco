## Context

Le parcours gestionnaire expose deja un ecran `Stock`, mais sa lecture metier reste chargee par des indicateurs secondaires et des colonnes non prioritaires.  
Le besoin exprime recentre l'ecran sur la rupture, tout en ajoutant deux leviers operationnels: visualiser la trajectoire temporelle du stock d'un article et modifier sa quantite disponible avec etat calcule.

## Goals / Non-Goals

**Goals:**
- Aligner le contenu de l'ecran `Stock` sur les informations jugées utiles pour la decision.
- Rendre explicite la consultation d'une courbe de stock par article.
- Rendre explicite la mise a jour de la quantite disponible par article, avec un etat de stock derive automatiquement.

**Non-Goals:**
- Redefinir les regles de consolidation fournisseur.
- Introduire des mecanismes de prediction ou de simulation du stock.
- Imposer un composant graphique ou une librairie de charting particuliere.

## Decisions

1. Simplification des KPIs stock
   - Decision: conserver uniquement `articles en rupture` en synthese.
   - Alternatives rejetees: garder `articles en alerte`/`entrepots suivis`; rejetees car non utiles pour le besoin formule.

2. Retrait de la colonne seuil mini
   - Decision: supprimer `seuil mini` de la vue listing stock cible.
   - Alternatives rejetees: conserver en lecture seule; rejetee pour eviter la confusion avec le recentrage fonctionnel.

3. Action explicite de courbe par article
   - Decision: chaque ligne article expose une action `Voir courbe` (ou equivalent) ouvrant la visualisation temporelle indexee sur les dates de modification de stock.
   - Alternatives rejetees: un ecran global unique de courbes; rejetee car moins contextualisee.

4. Edition du disponible avec etat derive
   - Decision: la modification stock sur un article porte sur le nombre disponible uniquement; l'etat est calcule automatiquement (`Rupture` si disponible = 0, sinon `Disponible`).
   - Alternatives rejetees: etat saisi manuellement; rejetee pour eviter les incoherences entre etat et quantite.

## Risks / Trade-offs

- [Risque de perte d'information percue avec la suppression de certains indicateurs] -> Mitigation: expliciter dans les specs le recentrage produit et conserver l'historique via la courbe.
- [Risque d'incoherence entre etat et quantite] -> Mitigation: etat derive automatiquement dans les specs et la maquette.
- [Risque de divergence entre maquette et comportement metier] -> Mitigation: porter les regles dans `frontend-reference-screen-flows` et `stock-and-supplier-consolidation`.

## Migration Plan

1. Mettre a jour les deltas specs des deux capacites ciblees.
2. Adapter la maquette `Stock` selon ces regles.
3. Verifier la coherence du parcours gestionnaire stock avec les nouvelles interactions.

## Open Questions

- Quel niveau de granularite d'horodatage doit etre affiche dans la courbe (date seule ou date + heure)?
