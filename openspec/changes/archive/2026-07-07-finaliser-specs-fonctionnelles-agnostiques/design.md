## Context

Les specs principales ont déjà été améliorées et traduites, mais certaines exigences restent partiellement couplées à des détails d’implémentation (noms d’actions, fonctions, formats techniques, composants legacy).  
Ce couplage freine la réécriture car il mélange le "quoi métier" et le "comment technique".

## Goals / Non-Goals

**Goals:**
- Rendre les specs strictement orientées comportement métier observable.
- Supprimer les références résiduelles à des langages, frameworks, fichiers, fonctions ou conventions d’implémentation.
- Préserver les règles métier, la conformité et les critères de testabilité.
- Uniformiser les scénarios pour servir de base directe aux tests fonctionnels de la future application.

**Non-Goals:**
- Modifier les règles métier validées dans le PRD.
- Concevoir l’architecture technique cible (API, base de données, infrastructure).
- Implémenter du code applicatif.

## Decisions

1. **Conserver les capacités existantes**  
   La passe finale se fait par modification des capacités déjà en place pour préserver la traçabilité.

2. **Conserver les noms de Requirement existants**  
   Pour limiter le bruit de migration, la passe privilégie la reformulation du contenu et des scénarios sans renommer les exigences.

3. **Supprimer les marqueurs techniques dans les descriptions**  
   Les références à des fonctions, fichiers, endpoints ou mécanismes internes sont retirées au profit de formulations fonctionnelles.

4. **Maintenir des scénarios testables**  
   Chaque Requirement garde des scénarios WHEN/THEN vérifiables côté produit, sans hypothèse d’implémentation.

## Risks / Trade-offs

- **[Risque] Perte de précision technique utile à la maintenance legacy**  
  → **Mitigation**: la connaissance technique reste dans le code legacy et l’historique des changes archivées.

- **[Risque] Ambiguïté d’implémentation pour l’équipe de réécriture**  
  → **Mitigation**: formulations centrées sur résultat attendu + scénarios d’acceptation explicites.

- **[Trade-off] Noms de Requirement parfois hétérogènes**  
  → **Mitigation**: stabilité de traçabilité priorisée dans cette passe; un renommage dédié pourra être fait ensuite.

## Migration Plan

1. Mettre à jour les deltas de chaque capacité ciblée avec des Requirements entièrement reformulés en mode fonctionnel.
2. Synchroniser les deltas vers `openspec/specs/*`.
3. Valider les specs OpenSpec.
4. Archiver la change après validation.

## Open Questions

1. Faut-il lancer une passe ultérieure de normalisation des intitulés de Requirement (homogénéisation FR/EN) ?
2. Souhaite-t-on imposer une structure de scénarios standard (ex. happy path + refus + cas limite) pour toutes les capacités ?
