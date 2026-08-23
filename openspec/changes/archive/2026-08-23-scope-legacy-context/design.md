## Context

Voir `proposal.md` pour la motivation.  
Le dossier `legacy/` mélange du code métier exploitable pour la migration et des volumes importants de dépendances tierces et d'assets statiques. Cette hétérogénéité dégrade la qualité du contexte utilisé pendant la refonte.

Contraintes actuelles:
- Le chantier doit démarrer sans ajouter d'infrastructure externe.
- Le périmètre doit rester compréhensible et vérifiable par toute l'équipe.
- La traçabilité entre legacy et cible doit être explicite pour limiter les régressions fonctionnelles.

## Goals / Non-Goals

**Goals:**
- Définir un périmètre legacy de référence orienté métier pour la refonte.
- Formaliser des exclusions stables pour supprimer le bruit non métier.
- Outiller la traçabilité des lots de migration vers les fichiers legacy sources.
- Permettre une adoption immédiate sans dépendance à une base vectorielle.

**Non-Goals:**
- Construire une pipeline d'embedding ou une base vectorielle externe.
- Modifier le comportement fonctionnel de l'application legacy.
- Refonte complète de l'arborescence legacy dans ce changement.

## Decisions

### Décision 1 - Approche A "corpus local curé"
**Choix:** démarrer avec un corpus local dans le repo, sans infrastructure RAG externe.  
**Pourquoi:** coût d'entrée faible, déploiement immédiat, alignement avec le besoin de lancer rapidement la refonte.  
**Alternatives considérées:**
- Base vectorielle + RAG externe: plus puissant mais surcoût d'exploitation prématuré.
- Base vectorielle + MCP pour Copilot: cible pertinente à moyen terme, trop ambitieuse pour le démarrage.

### Décision 2 - Exclusions minimales obligatoires
**Choix:** exclure systématiquement `legacy/ws/vendor/**`, `legacy/libs/**` et les assets (`legacy/imgs/**`, `legacy/assets/**` si présent).  
**Pourquoi:** ces zones apportent peu de signal métier et beaucoup de bruit contextuel.  
**Alternatives considérées:**
- Exclusion partielle au cas par cas: plus flexible, mais difficile à maintenir et source d'incohérence.
- Aucune exclusion: risque élevé de dilution du contexte de migration.

### Décision 3 - Traçabilité obligatoire par lot
**Choix:** chaque tâche de refonte référence explicitement les fichiers legacy source.  
**Pourquoi:** rendre la migration audit-able, faciliter la relecture fonctionnelle et réduire les écarts de comportement.  
**Alternatives considérées:**
- Traçabilité facultative: plus rapide au court terme, mais fragilise la qualité de migration.
- Traçabilité uniquement en revue finale: trop tardive pour corriger les dérives.

### Décision 4 - Lotissement par flux métier
**Choix:** organiser les lots de migration par verticales métier (commande, paiement, historique, etc.).  
**Pourquoi:** conserve le sens fonctionnel et simplifie la validation utilisateur.  
**Alternatives considérées:**
- Lotissement par technologie (HTML/JS/PHP): faible cohérence fonctionnelle et validation plus difficile.

## Risks / Trade-offs

- **[Risque]** Exclure un fichier considéré "asset" mais nécessaire à la compréhension d'un parcours.  
  **→ Mitigation** autoriser une exception tracée et revue avec justification explicite.

- **[Risque]** La discipline de traçabilité ajoute du temps de rédaction aux tickets.  
  **→ Mitigation** utiliser un format court standardisé (1 à 3 fichiers legacy par ticket).

- **[Trade-off]** Sans vector DB, la recherche sémantique avancée est limitée au démarrage.  
  **→ Mitigation** prévoir un point de décision ultérieur pour évaluer l'approche C (MCP + index vectoriel) après premiers lots.

## Migration Plan

1. Stabiliser la définition du corpus legacy de référence et ses exclusions minimales.
2. Publier la convention de traçabilité dans la préparation des tickets de refonte.
3. Démarrer les premiers lots verticaux sur ce périmètre curé.
4. Mesurer la qualité de migration (compréhension, rework, écarts fonctionnels).
5. Reconsidérer l'ajout d'une base vectorielle si le signal reste insuffisant.

## Open Questions

- Quel seuil déclenche l'évaluation d'une évolution vers MCP + base vectorielle (volume de tickets, taux de rework, temps de recherche)?
