## 1. Définition du corpus legacy de référence

- [x] 1.1 Documenter le périmètre inclus (`legacy/app/**`, `legacy/ws/**` hors exclusions) et vérifier que le document de cadrage est validé en revue d'équipe
- [x] 1.2 Formaliser les exclusions obligatoires (`legacy/ws/vendor/**`, `legacy/libs/**`, assets dont `legacy/imgs/**` et `legacy/assets/**` si présent) et vérifier qu'elles sont listées sans ambiguïté dans la convention de chantier

## 2. Mise en place des garde-fous de périmètre

- [x] 2.1 Aligner les règles de dépôt/outillage pour refléter les exclusions décidées et vérifier qu'aucun fichier des zones exclues n'est requis par les nouveaux tickets de refonte
- [x] 2.2 Définir une procédure d'exception de périmètre (ajout/retrait de chemin) et vérifier qu'un exemple de demande d'exception est disponible pour l'équipe

## 3. Traçabilité des lots de migration

- [x] 3.1 Ajouter un format standard de ticket imposant 1 à 3 fichiers legacy source et vérifier son adoption sur un premier lot pilote
- [x] 3.2 Organiser le backlog de refonte par verticales métier (commande, paiement, historique, etc.) et vérifier qu'au moins un ticket par verticale référence des sources legacy explicites

## 4. Cartographie des use-cases fonctionnels legacy

- [x] 4.1 Générer `docs/legacy-use-cases.md` en regroupant les use-cases fonctionnels legacy par domaine métier et vérifier que chaque use-case cite ses fichiers source legacy
- [x] 4.2 Revoir la couverture du document avec l'équipe et vérifier qu'aucun flux métier majeur de l'application legacy n'est manquant

## 5. Validation du démarrage de chantier

- [x] 5.1 Exécuter `openspec validate "scope-legacy-context" --type change` et vérifier que la validation OpenSpec est sans erreur
- [x] 5.2 Vérifier la readiness d'implémentation avec `openspec status --change scope-legacy-context` et confirmer que `proposal`, `specs`, `design` et `tasks` sont présents
