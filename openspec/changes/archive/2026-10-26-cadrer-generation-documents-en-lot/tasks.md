## 1. Contrats fonctionnels

- [x] 1.1 Finaliser le contrat métier de UC-CON-3 dans `batch-card-paid-order-documents` et vérifier que sélection, exclusions, ordre des lots, limite de 30 et cas d'échec sont décrits par des scénarios testables.
- [x] 1.2 Décrire le parcours d'écran UC-CON-3 dans le delta `frontend-reference-screen-flows` et vérifier que la période courante, la progression, l'absence de commandes et l'échec sont représentés sans spécifier de comportement technique.
- [x] 1.3 Préciser le tri global des commandes par nom puis prénom du premier enfant lié, sans utiliser le numéro de commande comme critère de tri, puis répartir la liste en lots de 30.

## 2. Maquette statique

- [x] 2.1 Créer l'écran statique UC-CON-3 sous `maquettes/screens/` et vérifier qu'il illustre le lancement gestionnaire, des lots séquentiels de 30 au maximum, la progression, l'état sans commande et un échec explicite, sans action fonctionnelle.
- [x] 2.2 Ajouter l'écran aux manifestes JSON et JavaScript et vérifier que le workbench le charge en mode local sans backend.
- [x] 2.3 Mettre à jour l'exemple de maquette pour illustrer le tri alphabétique sur le premier enfant et vérifier que la règle est visible à l'écran.
- [x] 2.4 Comparer l'exemple de bon aux données assemblées pour le modèle legacy `PDF_FSE_CMDS`, représenter les champs utiles disponibles dans le traitement par lot et signaler que le HTML exact du modèle n'est pas versionné dans le dépôt.

## 3. Traçabilité et validation

- [x] 3.1 Rattacher UC-CON-3 à ses spécifications et à l'écran dans `docs/legacy-coverage-matrix.md`, puis vérifier que `scripts/check-legacy-coverage.ps1` ne le signale plus comme à cadrer ni sans spécification/maquette; conserver le signalement « sans PRD » car aucun FR dédié n'existe et ne pas en inventer dans ce changement.
- [x] 3.2 Valider strictement le changement OpenSpec et vérifier que les modifications se limitent aux spécifications, à la documentation et aux maquettes, sans modifier `legacy/` ni créer d'implémentation runtime.
