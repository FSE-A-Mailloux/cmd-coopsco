## Why

UC-HISTO-2 et UC-HISTO-3 sont repris dans le PRD mais ne disposent encore ni de specs de consultation ni de maquettes. Les gestionnaires doivent pouvoir examiner les actions et anomalies applicatives sans dépendre d’un accès réservé aux administrateurs comme dans le legacy.

## What Changes

- Spécifier la consultation en lecture seule de deux listes distinctes : journal des actions applicatives et anomalies enregistrées.
- Autoriser explicitement les rôles `Gestionnaire` et `Admin`, selon l’arbitrage confirmé, et refuser les accès `Famille` ou non authentifiés.
- Restituer au minimum domaine, action, contexte et date-heure ; distinguer le contexte métier utile des données sensibles et détails techniques internes.
- Décrire les résultats triés du plus récent au plus ancien, l’absence de résultat, le refus d’accès, la session expirée et l’erreur de chargement.
- Prévoir deux maquettes statiques partagées entre les groupes gestionnaire et admin, puis leur référencement dans les deux manifestes et la matrice de couverture.
- Conserver l’historique des changements de rôle comme parcours distinct réservé aux admins : son ouverture aux gestionnaires n’est pas incluse.

## Capabilities

### New Capabilities
- `application-journal-consultation`: consultation autorisée et en lecture seule des actions et anomalies, contenu affichable et états de résultat ; couvre FR-084 et FR-085 en s’appuyant sur les traces prévues par FR-082 et FR-083.

### Modified Capabilities
- `frontend-reference-screen-flows`: parcours de maquettes pour les deux listes et leurs états, accessibles dans les groupes gestionnaire et admin.

## Impact

- À l’application : maquettes `suivi-journal-actions` et `suivi-anomalies` dans `maquettes/screens/suivi/`, manifestes `screens.json` et `screens.js`, et documentation `maquettes/README.md`.
- À l’application : mise à jour des rattachements UC-HISTO-2/3 dans `docs/legacy-coverage-matrix.md` et clarification des deux rôles de consultation dans le PRD.
- Aucun développement serveur, aucune modification de `legacy/`, aucune nouvelle dépendance.
- La production des traces, leur durée de conservation, l’export, les filtres avancés et le traitement des anomalies restent hors périmètre.
