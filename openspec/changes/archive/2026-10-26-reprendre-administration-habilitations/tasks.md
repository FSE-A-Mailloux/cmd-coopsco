## 1. Cadrage fonctionnel et traçabilité

- [x] 1.1 Mettre à jour la ligne UC-ADM-2 de `docs/legacy-coverage-matrix.md` pour la rattacher à `user-account-lifecycle-management`, `frontend-reference-screen-flows` et `admin-utilisateurs`, retirer la mention de descente incomplète et vérifier que FR-038 reste sa référence PRD.

## 2. Maquette d'administration

- [x] 2.1 Compléter `maquettes/screens/administration/admin-utilisateurs.html` avec l'action admin d'imposition, la confirmation du compte cible et les retours de succès/erreur, en précisant le changement à la prochaine connexion; vérifier que les actions de changement de rôle et d'historique restent disponibles et qu'aucun mot de passe n'est affiché ou demandé à l'admin.
- [x] 2.2 Vérifier que `admin-utilisateurs` reste référencé dans `maquettes/manifest/screens.json` et `maquettes/manifest/screens.js`, actualiser le principe correspondant dans `maquettes/README.md` et vérifier que l'aperçu présente l'action comme réservée à Admin, sans nouvel écran ni navigation vers une gestion des groupes ou permissions.

## 3. Validation de cohérence

- [x] 3.1 Exécuter `openspec validate --change reprendre-administration-habilitations --strict` et corriger les erreurs de structure ou de couverture des scénarios avant de terminer.
- [x] 3.2 Vérifier la cohérence des deux exigences avec UC-ADM-2 et FR-038, ainsi que l'absence de changement sur les permissions fixes, l'affectation de rôle déjà cadrée et le dossier `legacy`.
