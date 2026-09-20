---
description: "Interdiction de modifier le dossier legacy"
applyTo: "legacy/**"
---

# Dossier `legacy/` : lecture seule

Le dossier [legacy/](../../legacy) contient l'application existante. Il sert uniquement de :
- référence pour comprendre le comportement métier actuel ;
- point de départ pour rédiger les spécifications (`openspec/`) et les maquettes écran (`maquettes/`) de la nouvelle application.

## Règles

- Ne jamais créer, modifier ou supprimer de fichier dans `legacy/`, quelle que soit la demande (correction de bug, refactoring, mise à jour de dépendances, formatage, etc.).
- Ne pas exécuter de commande modifiant l'état de `legacy/` (installation de dépendances, migrations de base, `git` destructif, etc.), sauf demande explicite et limitée à des fins de lecture/exécution locale (ex. lancer l'app existante pour l'observer).
- Si une tâche semble nécessiter un changement dans `legacy/`, proposer plutôt l'évolution correspondante dans `openspec/` (specs, changes) et/ou `maquettes/`, et expliquer pourquoi aucune modification de `legacy/` n'est faite.
- Les seules actions autorisées sur `legacy/` sont la lecture, la recherche et l'analyse (grep, lecture de fichiers) pour alimenter les spécifications et maquettes.
