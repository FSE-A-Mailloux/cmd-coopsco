## 1. Recadrage des artefacts du changement

- [x] 1.1 Réconcilier `proposal.md` avec le commit `c3e2b81` et le changement archivé `2026-10-12-cadrer-changement-role-utilisateur`, en limitant le périmètre aux compléments documentaires.
- [x] 1.2 Mettre à jour `design.md` avec les preuves de couverture, les écarts et les correspondances métier/UX, sans décision d’implémentation applicative.

## 2. Complément des spécifications techniques métier

- [x] 2.1 Remplacer le delta redondant du cycle de vie par un complément de validation du compte cible dans `user-role-administration-and-audit` ; vérifier que les specs principales couvrent déjà le refus non-admin, les rôles autorisés et l’audit.
- [x] 2.2 Compléter `authorization-and-menu-resolution` avec la perte et le gain de droits dès le premier contrôle suivant, même en session ouverte, et le menu à la reconnexion.
- [x] 2.3 Aligner les termes acteur `Admin`, utilisateur cible, rôle cible et permissions prédéfinies entre les deltas et le design, sans modifier la définition des rôles.

## 3. Complément des maquettes de référence

- [x] 3.1 Modifier l’exigence du parcours intégré existant dans `frontend-reference-screen-flows`, en conservant ses scénarios et le listing comme point d’entrée, sans nouvel écran ni modification de code.
- [x] 3.2 Compléter les scénarios de succès, validation et refus d’autorisation, puis vérifier leurs correspondances avec les exigences métier dans la table du design.

## 4. Vérification documentaire de cohérence

- [x] 4.1 Vérifier la traçabilité des trois capacités et l’absence de perte des exigences initiales, puis valider le changement avec `openspec validate permettre-admin-changer-role-utilisateur --strict`.
- [x] 4.2 Synchroniser les trois deltas vers les specs principales, vérifier leur correspondance et leur validité, puis confirmer que le dossier ne contient que du travail documentaire avant archivage.

Les tâches initiales sont réconciliées avec l’existant : les règles d’autorisation, de rôle et d’audit ne sont pas réimplémentées ni dupliquées. Les tâches cochées attestent une couverture documentaire vérifiée, pas la disponibilité d’un serveur applicatif.
