## Why

Le produit doit permettre a un administrateur de changer le role d'un utilisateur sans passer par des manipulations techniques. Ce besoin n'est pas cadre aujourd'hui: les regles metier, la tracabilite et le parcours ecran ne sont pas formalises, ce qui bloque la conception et la mise en oeuvre coherente.

## What Changes

- Cadrer une experience simple de changement de role integree a l'ecran de listing des utilisateurs.
- Imposer une zone de recherche sur le listing utilisateurs pour retrouver rapidement un compte a modifier.
- Definir les regles metier du changement de role: seuls les admins peuvent agir, avec les roles cibles `Famille`, `Gestionnaire`, `Admin`.
- Definir la tracabilite obligatoire de chaque changement de role (acteur, cible, ancien role, nouveau role, horodatage, motif/eventuel contexte) et sa consultation ulterieure.
- Cadrer les exigences techniques necessaires a l'implementation (controles d'autorisation, persistance du journal, points d'integration front/back).

## Capabilities

### New Capabilities

- `user-role-administration-and-audit`: definir le changement de role utilisateur par un admin, le referentiel des roles autorises et la journalisation consultable des modifications.

### Modified Capabilities

- `frontend-reference-screen-flows`: integrer le changement de role dans le listing utilisateurs avec une zone de recherche et un parcours de consultation de l'historique des changements de role.

## Impact

- Specifications OpenSpec: ajout d'une capacite metier/technique sur l'administration des roles et adaptation des parcours frontend de reference.
- Maquettes: evolution des ecrans de gestion utilisateurs (listing + action de changement de role + consultation de trace).
- Implementation future: controles d'autorisation admin, API de mise a jour de role, stockage/lecture du journal d'audit, tests d'habilitation et de tracabilite.
