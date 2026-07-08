## Why

Les maquettes et les specs ne cadrent pas encore suffisamment les regles de pilotage des periodes et l'ergonomie des ecrans de listing.  
Cette change est necessaire pour verrouiller les regles de gestion attendues (unicite de periode en cours, actions autorisees) et completer le referentiel d'ecrans periodes.

## What Changes

- Preciser les regles de gestion des periodes: unicite de la periode en cours, contraintes d'ouverture, contraintes de bascule en cours.
- Preciser la disponibilite des actions dans `Periodes - Listing et actions` selon l'etat de la periode.
- Exiger un rendu responsive pour les tableaux des ecrans de listing.
- Completer le domaine periodes avec un bouton de modification dans `Periodes - Detail`.
- Ajouter les ecrans de creation et de modification d'une periode dans le referentiel maquettes.

## Capabilities

### New Capabilities
- _Aucune_

### Modified Capabilities
- `period-catalog-and-pricing-management`: renforcer les invariants metier sur la periode en cours et les conditions d'ouverture des periodes.
- `frontend-reference-screen-flows`: aligner les ecrans de gestion des periodes, les actions visibles en listing/detail et la contrainte responsive des tableaux de listing.

## Impact

- Met a jour les specs de gestion des periodes et de reference ecrans frontend.
- Implique des ajustements des maquettes gestionnaire sur les ecrans periodes et listing.
- Clarifie les comportements attendus avant implementation applicative.
