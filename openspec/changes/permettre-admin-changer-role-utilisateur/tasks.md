## 1. Recadrage des artefacts du changement

- [ ] 1.1 Mettre à jour `proposal.md` pour expliciter que le périmètre est documentaire (specs + maquettes) et vérifier que la section Impact ne mentionne aucun développement applicatif.
- [ ] 1.2 Mettre à jour `design.md` pour décrire une approche de spécification technique/UX uniquement et vérifier l’absence de décisions d’implémentation (API, endpoint, déploiement applicatif).

## 2. Complément des spécifications techniques métier

- [ ] 2.1 Compléter `specs/user-account-lifecycle-management/spec.md` sur les règles de changement de rôle administrateur et vérifier qu’au moins un scénario couvre un refus d’autorisation.
- [ ] 2.2 Compléter `specs/authorization-and-menu-resolution/spec.md` sur la cohérence permissions/menu après changement de rôle et vérifier qu’au moins un scénario couvre une perte de droits et un autre un gain de droits.
- [ ] 2.3 Vérifier l’homogénéité des termes fonctionnels (acteur, cible, rôle, permission) entre les deux specs et corriger les écarts sémantiques.

## 3. Complément des maquettes de référence

- [ ] 3.1 Ajouter le delta `specs/frontend-reference-screen-flows/spec.md` décrivant le parcours visuel d’administration des rôles et vérifier qu’il contient des scénarios WHEN/THEN exploitables en revue UX.
- [ ] 3.2 Décrire les états visuels minimum (succès, erreur de validation, refus d’autorisation) dans la spec frontend et vérifier que chaque état est rattachable à une exigence métier.

## 4. Vérification documentaire de cohérence

- [ ] 4.1 Contrôler la traçabilité entre `proposal.md`, les 3 specs modifiées et `design.md`, puis vérifier que chaque capability de la proposal a son delta.
- [ ] 4.2 Relire `tasks.md` et vérifier que toutes les tâches portent sur la documentation/specification, sans action de développement applicatif.
