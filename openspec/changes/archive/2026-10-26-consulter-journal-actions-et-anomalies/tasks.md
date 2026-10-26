## 1. Alignement documentaire

- [x] 1.1 Préciser dans `openspec/prd-rewrite-agnostique.md` FR-084/085 et l’arbitrage de consultation les rôles `Gestionnaire` et `Admin`, puis rattacher la nouvelle capacité aux exigences concernées ; vérifier que FR-082/083 et la restriction admin de l’historique des rôles restent inchangées.

## 2. Maquettes de consultation

- [x] 2.1 Créer `maquettes/screens/suivi/suivi-journal-actions.html` avec domaine, action, contexte et date-heure, exemples fictifs triés du plus récent au plus ancien et les cinq variantes nommées ; vérifier la présence des états renseigné, vide, refus, session expirée et erreur, sans modification des traces ni appel réseau.
- [x] 2.2 Créer `maquettes/screens/suivi/suivi-anomalies.html` sur les mêmes conventions ; vérifier les quatre colonnes, l’ordre décroissant, les cinq variantes et l’absence d’action de correction, acquittement, rejeu ou suppression.
- [x] 2.3 Vérifier les exemples des deux écrans contre les exigences de contexte : aucune donnée réelle, secret, requête SQL brute, pile technique ou détail d’historique des rôles ; vérifier qu’un exemple contenant du balisage est affiché comme texte inerte et que les états refus/expiration ne montrent aucune trace.

## 3. Catalogue et couverture

- [x] 3.1 Déclarer les deux écrans dans `maquettes/manifest/screens.json` et `screens.js`, catégorie « Historique et suivi », rôles `gestionnaire` et `admin` ; vérifier que les deux manifestes contiennent les mêmes entrées et que chaque source existe.
- [x] 3.2 Actualiser `maquettes/README.md` pour les journaux partagés en lecture seule et leurs variantes fictives ; vérifier que le document ne présente pas les maquettes comme une protection serveur réelle.
- [x] 3.3 Synchroniser les deux deltas dans les specs principales selon le workflow de synchronisation, puis compléter UC-HISTO-2/3 dans `docs/legacy-coverage-matrix.md` avec `application-journal-consultation`, `frontend-reference-screen-flows` et l’écran correspondant ; vérifier que chaque référence désigne un artefact effectivement présent avant de retirer la mention de descente incomplète.

## 4. Vérification transverse

- [x] 4.1 Examiner chaque écran dans les groupes gestionnaire et admin du workbench, en téléphone et desktop, localement sans backend et via le mode de consultation distante disponible ; vérifier la navigation, les quatre informations accessibles en cartes sur téléphone sans débordement horizontal et en tableau sur desktop, et les cinq variantes distinctes sans données de substitution en erreur.
- [x] 4.2 Exécuter `openspec validate consulter-journal-actions-et-anomalies --strict`, `openspec validate --specs --strict` et `pwsh -File scripts\check-legacy-coverage.ps1` ; vérifier l’absence d’erreur pour le changement et ses références, sans masquer les avertissements indépendants.
- [x] 4.3 Vérifier le diff final : seuls les artefacts documentaires, les deux nouvelles maquettes, leur intégration au catalogue et leurs styles partagés ciblés sont modifiés ; aucun fichier `legacy`, code serveur, nouvelle dépendance ni permission de l’historique des rôles ne doit avoir changé.
