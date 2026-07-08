## Why

L'ecran `Consolidation` actuel ne correspond pas au besoin metier: il presente une synthese generique (dont un taux de service) au lieu d'un etat decisionnel a un instant donne pour une periode.  
Le besoin est de piloter la commande fournisseur a partir d'une consolidation horodatee par article, avec saisie du besoin FSE (stock a conserver) et calcul du nombre a commander.

## What Changes

- Redefinir la consolidation comme un **snapshot horodate** rattache a une periode.
- Introduire une saisie par article du **besoin FSE** (stock a conserver).
- Calculer par article la **quantite a commander fournisseur** a partir du stock, des quantites commandees familles et du besoin FSE.
- Permettre l'**enregistrement** d'une consolidation et sa **consultation ulterieure**.
- Afficher dans l'ecran de consolidation, pour la periode en cours, les indicateurs financiers d'estimation: **CA estime**, **marge estimee** (si tarif fournisseur HT renseigne) et **cout du stock prevu (besoin FSE)**.
- Retirer le **taux de service** de l'ecran de consolidation.

## Capabilities

### New Capabilities
- Aucune.

### Modified Capabilities
- `stock-and-supplier-consolidation`: passage d'une consolidation consultable en continu a une consolidation par snapshot horodate, ajout du besoin FSE saisi et des regles de calcul article.
- `frontend-reference-screen-flows`: evolution de l'ecran `Consolidation` (structure de tableau, actions d'enregistrement/consultation, suppression du taux de service, ajout des indicateurs financiers cibles).

## Impact

- Specs OpenSpec:
  - `openspec/specs/stock-and-supplier-consolidation/spec.md`
  - `openspec/specs/frontend-reference-screen-flows/spec.md`
- Maquettes:
  - `maquettes/screens/administration/admin-consolidation.html`
  - `maquettes/README.md`
- Aucun changement d'API externe dans cette iteration de conception maquette/spec.
