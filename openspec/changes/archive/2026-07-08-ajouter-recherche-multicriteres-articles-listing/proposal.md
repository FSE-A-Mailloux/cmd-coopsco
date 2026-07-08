## Why

L'ecran `Articles - Listing` ne propose pas encore de recherche rapide, ce qui ralentit l'identification d'un article dans le referentiel.  
Une recherche multicriteres est necessaire pour fluidifier les revues maquettes et cadrer la regle fonctionnelle avant les autres evolutions articles/prix.

## What Changes

- Ajouter une zone de recherche dans `Articles - Listing`.
- Definir que la recherche porte sur le libelle, la reference article et la marque.
- Aligner la maquette et la documentation maquettes sur cette regle de gestion.

## Capabilities

### New Capabilities
- _Aucune_

### Modified Capabilities
- `frontend-reference-screen-flows`: ajouter la regle fonctionnelle de recherche multicriteres sur l'ecran `Articles - Listing`.

## Impact

- Met a jour la maquette `maquettes/screens/administration/admin-articles.html`.
- Met a jour la documentation `maquettes/README.md`.
- Ajoute un delta spec OpenSpec sur `frontend-reference-screen-flows`.
