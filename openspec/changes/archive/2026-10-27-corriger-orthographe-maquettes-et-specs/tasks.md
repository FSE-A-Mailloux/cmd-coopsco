## 1. Cadrage de la passe orthographique

- [x] 1.1 Établir la liste des fichiers à relire dans `maquettes/` et `openspec/specs/` (ainsi que les deltas OpenSpec concernés) et vérifier que le périmètre exclut `legacy/`.
- [x] 1.2 Définir une grille de correction (accents, cédille, apostrophes, traits d'union, cohérence des libellés partagés) et vérifier qu'elle n'introduit aucune règle modifiant le comportement métier.

## 2. Correction des maquettes

- [x] 2.1 Corriger les fautes d'orthographe des libellés utilisateurs dans `maquettes/screens/**/*.html` et vérifier via revue de diff que seules des modifications textuelles sont appliquées.
- [x] 2.2 Corriger la documentation maquettes (`maquettes/README.md`, catalogues/manifests textuels) et vérifier l'alignement des libellés avec les écrans corrigés.
- [x] 2.3 Relire les écrans partagés entre rôles pour confirmer la cohérence de graphie sur les mêmes actions/états et vérifier l'absence de divergence résiduelle.

## 3. Correction des specs OpenSpec

- [x] 3.1 Corriger l'orthographe dans `openspec/specs/**/*.md` sans altérer les exigences et vérifier que les sections `Requirement`, `Scenario`, `WHEN`, `THEN` restent intactes.
- [x] 3.2 Corriger les deltas OpenSpec impactés par la même terminologie et vérifier la cohérence de libellés entre deltas et specs principales.
- [x] 3.3 Contrôler que les corrections ne changent pas le sens normatif (`MUST`) et vérifier ce point par relecture ciblée des exigences modifiées.

## 4. Vérification finale

- [x] 4.1 Exécuter `openspec validate corriger-orthographe-maquettes-et-specs --strict` et vérifier l'absence d'erreur de structure sur le change.
- [x] 4.2 Exécuter `openspec validate --specs --strict` et vérifier que la passe orthographique n'a pas dégradé les specs globales.
- [x] 4.3 Examiner le diff final et vérifier que seules des corrections rédactionnelles sur maquettes/specs sont présentes, sans changement de comportement ni fichiers hors périmètre.
