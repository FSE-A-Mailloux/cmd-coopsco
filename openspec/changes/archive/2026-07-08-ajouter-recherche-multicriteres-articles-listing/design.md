## Context

Le domaine articles propose deja un ecran de listing distinct de l'ecran de creation, mais sans mecanisme explicite de recherche dans la maquette.  
Le besoin utilisateur est d'ajouter un point d'entree simple pour retrouver un article par libelle, reference ou marque.

## Goals / Non-Goals

**Goals:**
- Ajouter une zone de recherche visible dans `Articles - Listing`.
- Rendre explicite le perimetre de recherche multicriteres (libelle, reference, marque).
- Garder un comportement coherent avec les autres ecrans de listing deja equipes de filtres/recherche.

**Non-Goals:**
- Ajouter des filtres avances supplementaires (disponibilite, plage de prix, periode).
- Modifier le modele article ou les regles de tarification.
- Definir le comportement backend/detail technique de requetage.

## Decisions

1. Champ de recherche unique multicriteres
   - Decision: un seul champ couvre reference, libelle et marque.
   - Alternative rejetee: trois champs distincts, plus lourds pour un besoin de consultation rapide.

2. Positionnement en tete de listing
   - Decision: placer la zone de recherche avant le tableau de listing.
   - Alternative rejetee: recherche en pied d'ecran, moins visible et moins actionnable.

3. Regle exprimee dans la spec frontend reference
   - Decision: porter la regle dans `frontend-reference-screen-flows` pour garantir l'alignement maquette/spec.
   - Alternative rejetee: ne documenter que dans README maquettes, insuffisant pour la tracabilite OpenSpec.

## Risks / Trade-offs

- [Risque d'interpretation differente sur la logique de correspondance] -> Mitigation: scenario explicite mentionnant les 3 champs cibles.
- [Risque d'encombrement visuel du listing] -> Mitigation: conserver une zone de recherche compacte en tete du tableau.
