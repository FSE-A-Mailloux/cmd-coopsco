## 1. Retrait de la maquette

- [x] 1.1 Supprimer `maquettes\screens\administration\admin-roles-permissions.html` et ses entrées dans `maquettes\manifest\screens.json` et `maquettes\manifest\screens.js` ; vérifier que le fichier est absent, que les deux catalogues sont équivalents et que les sources des autres écrans existent.
- [x] 1.2 Retirer les éventuels liens actifs vers l'écran et documenter dans `maquettes\README.md` les permissions fixes des trois rôles et la distinction avec leur affectation aux utilisateurs ; vérifier qu'aucun écran ou lien de remplacement n'offre d'administration des permissions.

## 2. Alignement du cadrage produit

- [x] 2.1 Reformuler `FR-020` et `FR-025` à `FR-027` dans `openspec\prd-rewrite-agnostique.md`, ainsi que les résumés et arbitrages concernés, pour conserver les identifiants tout en imposant les rôles fixes et l'affectation par un admin ; vérifier que le PRD ne promet plus de gestion de groupes, de fonctions ou de permissions et ne remet pas en cause les contrôles d'accès ni les parcours de rôle actif existants.
- [x] 2.2 Mettre à jour `docs\legacy-coverage-matrix.md` : abandon justifié de `UC-ADM-3`, `UC-ADM-5` et `UC-ADM-6`, reprise adaptée de `UC-ADM-4` via le listing utilisateurs, actualisation des arbitrages et rattachements ; vérifier qu'aucun rattachement ne vise l'écran retiré et que `UC-ADM-2` reste conservé.
- [x] 2.3 Recadrer l'objectif dans `openspec\changes\reprendre-administration-habilitations\.openspec.yaml` et ses références actives pour retirer la gestion de groupes, renvoyer l'affectation de rôles vers son cadrage existant et conserver le changement forcé de mot de passe ; vérifier que cet objectif distinct n'est ni supprimé ni marqué comme implémenté.

## 3. Vérification transverse

- [x] 3.1 Vérifier le workbench ouvert localement et servi en HTTP dans les vues téléphone et desktop : absence de l'écran retiré, navigation et aperçus des autres écrans fonctionnels, ancienne page indisponible ; confirmer que les deux modes de chargement du catalogue ne réintroduisent pas l'écran.
- [x] 3.2 Vérifier la non-régression de `admin-utilisateurs` : choix limité à `Famille`, `Gestionnaire` et `Admin`, changement effectif avec retour explicite, rejet d'un rôle invalide, historique conservé ; confirmer que le fichier et les règles de `user-role-administration-and-audit` n'ont pas été altérés par ce retrait.
- [x] 3.3 Exécuter `scripts\check-legacy-coverage.ps1` et `openspec validate figer-permissions-des-roles --strict` ; vérifier l'absence d'anomalies bloquantes et relire les références actives pour confirmer la cohérence des rôles fixes, sans modifier le dossier `legacy` ni les archives.
