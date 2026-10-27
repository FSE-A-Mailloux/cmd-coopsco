## Context

Voir `proposal.md` pour la motivation. Le référentiel contient des maquettes HTML et des specs OpenSpec majoritairement rédigées en français, avec des libellés actuellement écrits sans accents dans plusieurs fichiers.  
La correction demandée est transversale mais documentaire : elle doit préserver strictement le sens fonctionnel, la structure OpenSpec et les parcours déjà validés.

## Goals / Non-Goals

**Goals:**
- Corriger les fautes d'orthographe françaises sur les textes visibles des maquettes et sur les specs OpenSpec.
- Garantir une cohérence de libellés entre les maquettes, les manifestes et les specs associées.
- Encadrer la passe de correction pour éviter toute dérive vers un changement fonctionnel.

**Non-Goals:**
- Modifier les comportements métier, permissions, scénarios, flux de navigation ou données.
- Reconcevoir l'UI, réorganiser les écrans, ou introduire de nouvelles fonctionnalités.
- Modifier le dossier `legacy/` (lecture seule).

## Decisions

### 1. Traiter la correction comme une passe éditoriale contrôlée
La mise à jour est limitée à la qualité rédactionnelle (accents, cédille, orthographe, typographie simple) sans toucher aux exigences métier.  
Alternative rejetée : corriger au fil de l'eau pendant d'autres changements, qui rendrait la validation et la traçabilité difficiles.

### 2. Parcourir les artefacts par familles de fichiers
Appliquer une relecture structurée sur :
- `maquettes/**/*.html` et `maquettes/**/*.md` pour les textes visibles et la documentation de maquettes ;
- `openspec/specs/**/*.md` et les deltas de changement concernés pour l'alignement rédactionnel.
Alternative rejetée : une correction opportuniste fichier par fichier sans périmètre explicite, trop risquée en couverture.

### 3. Préserver la structure OpenSpec à l'identique
Ne corriger que le texte descriptif et les libellés, sans altérer les marqueurs structurels (`Requirement`, `Scenario`, `WHEN`, `THEN`, sections ADDED/MODIFIED/REMOVED/RENAMED) ni la granularité des exigences.  
Alternative rejetée : reformuler des exigences complètes, qui pourrait involontairement changer l'interprétation normative.

### 4. Vérifier la non-régression documentaire en fin de passe
La validation combine contrôle OpenSpec (`openspec validate`) et revue ciblée des diffs textuels pour confirmer l'absence de changements comportementaux.  
Alternative rejetée : valider uniquement à l'œil, insuffisant pour sécuriser un lot transversal.

## Risks / Trade-offs

- [Risque] Une correction peut modifier involontairement le sens d'une exigence normative → Mitigation : limiter les modifications aux fautes explicites et conserver les formulations métier existantes.
- [Risque] Divergence entre libellés maquettes et specs après correction partielle → Mitigation : relecture croisée des termes partagés (menus, actions, états).
- [Risque] Oubli de fichiers hors parcours principal → Mitigation : balayage systématique des répertoires ciblés et vérification finale des diffs.
- [Compromis] Une passe éditoriale exhaustive peut être longue sur un gros volume de texte → Mitigation : découper la correction par groupes d'écrans/capacités tout en gardant une validation globale finale.

## Migration Plan

Lors de l'apply :
1. Corriger les libellés des maquettes et de leur documentation associée.
2. Corriger l'orthographe des specs OpenSpec principales et des deltas concernés.
3. Vérifier la cohérence des libellés partagés maquettes/specs.
4. Exécuter les validations OpenSpec et relire le diff pour confirmer l'absence de changement fonctionnel.

Rollback : rétablir les versions précédentes des fichiers documentaires touchés si une ambiguïté métier est introduite.
