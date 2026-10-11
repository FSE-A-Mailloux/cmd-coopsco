## Context

Voir `proposal.md` (section **Why**) pour la motivation produit.  
Ce changement est documentaire: il vise la complétude et la cohérence des spécifications techniques et des maquettes de référence.  
Le périmètre couvre trois capacités déjà existantes: gestion du cycle de vie utilisateur, autorisation/résolution de menu, et parcours visuels frontend de référence.

## Goals / Non-Goals

**Goals:**
- Définir des exigences testables sur le changement de rôle administrateur dans les specs techniques.
- Décrire les écrans de référence et les états visuels du parcours de changement de rôle.
- Garantir l’alignement entre règles fonctionnelles (specs) et rendu attendu (maquettes).
- Produire un dossier de changement prêt pour la revue, sans implémentation applicative.

**Non-Goals:**
- Développer ou modifier le code applicatif backend/frontend.
- Introduire de nouveaux mécanismes techniques d’authentification/session.
- Étendre le périmètre à d’autres domaines métiers non liés aux habilitations.

## Decisions

### 1) Séparer explicitement les règles métier et les règles visuelles
- **Décision**: documenter les règles métier dans `user-account-lifecycle-management` et `authorization-and-menu-resolution`, puis documenter les écrans et états dans `frontend-reference-screen-flows`.
- **Rationale**: cette séparation garde chaque spec lisible, tout en couvrant l’intégralité du comportement attendu.
- **Alternatives considérées**:
  - **Tout décrire dans une seule capacité**: rejeté, car ambigu entre exigences fonctionnelles et représentation UI.
  - **Ne documenter que les maquettes**: rejeté, car insuffisant pour valider les règles d’autorisation.

### 2) Formaliser un noyau minimal d’exigences techniques testables
- **Décision**: imposer dans les specs les contrôles minimaux (habilitation acteur, validité rôle cible, existence utilisateur cible, traçabilité).
- **Rationale**: ces exigences représentent le socle nécessaire pour cadrer la réalisation ultérieure et la recette.
- **Alternatives considérées**:
  - **Exigences trop générales**: rejeté, car non vérifiables.
  - **Exigences trop bas niveau implémentation**: rejeté, car hors périmètre du change.

### 3) Exiger une traçabilité croisée specs ↔ maquettes
- **Décision**: chaque état visuel clé (succès, refus d’autorisation, erreur de validation) est relié à une exigence métier correspondante.
- **Rationale**: réduit les écarts entre ateliers de cadrage, spécifications et revues UI.
- **Alternatives considérées**:
  - **Maquettes sans états d’erreur**: rejeté, car incomplet pour la validation fonctionnelle.
  - **État visuel non relié aux specs**: rejeté, car source d’interprétation divergente.

## Risks / Trade-offs

- **[Risque]** Glissement vers des détails d’implémentation dans les artefacts.  
  **→ Mitigation**: relire chaque section contre le critère « observable et testable ».
- **[Risque]** Maquettes et specs évoluent de manière asynchrone.  
  **→ Mitigation**: vérifier explicitement les correspondances scénario métier ↔ état visuel.
- **[Trade-off]** Un cadrage documentaire précis demande plus d’effort initial mais réduit les ambiguïtés en phase de réalisation.

## Migration Plan

1. Mettre à jour les deltas de specs des capacités ciblées.
2. Ajouter/compléter les exigences de maquettes dans `frontend-reference-screen-flows`.
3. Vérifier la cohérence terminologique et scénaristique entre artefacts.
4. Soumettre le change à revue fonctionnelle/UX avant phase d’apply.

**Rollback**
- Revenir aux versions précédentes des artefacts OpenSpec si le cadrage est rejeté en revue.
