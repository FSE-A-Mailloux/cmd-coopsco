## Why

L'ecran `Stock` des maquettes affiche des informations jugées peu utiles (`articles en alerte`, `entrepots suivis`, `seuil mini`) et ne couvre pas encore deux besoins de gestion clés: suivre l'evolution du stock dans le temps et piloter l'etat/quantite disponible par article.  
Il faut formaliser ces ajustements en regles fonctionnelles OpenSpec avant la prochaine iteration des maquettes.

## What Changes

- Simplifier la synthese de l'ecran `Stock` pour ne conserver que l'indicateur `articles en rupture`.
- Retirer les indicateurs `articles en alerte` et `entrepots suivis` de l'ecran `Stock`.
- Retirer la notion de `seuil mini` du tableau de listing stock.
- Ajouter, pour chaque article, une action de visualisation de la courbe d'evolution du stock dans le temps.
- Ajouter, pour chaque article, la possibilite de modifier l'etat du stock et le nombre disponible.

## Capabilities

### New Capabilities
- _Aucune_

### Modified Capabilities
- `frontend-reference-screen-flows`: ajouter les regles d'affichage et d'interaction de l'ecran `Stock` (KPIs, colonnes, action de courbe, edition etat/quantite).
- `stock-and-supplier-consolidation`: completer les regles metier de suivi stock avec historique temporel par article et mise a jour etat/quantite disponible.

## Impact

- Met a jour les deltas specs OpenSpec sur le domaine stock.
- Cadre les futures modifications de maquettes `Stock` et les comportements attendus.
- Reduit l'ambiguite sur les informations de pilotage stock exposees au gestionnaire.
