## Purpose

Définir un corpus legacy de référence, stable et traçable, pour guider la refonte et réduire le bruit contextuel pendant les travaux assistés par Copilot.

## ADDED Requirements

### Requirement: Le corpus legacy de refonte MUST cibler le code métier exploitable
Le système de travail de refonte MUST définir un périmètre source prioritaire qui couvre les écrans métier, la logique frontend et les points d'entrée backend utiles à la migration.

#### Scenario: Inclusion des surfaces métier de référence
- **WHEN** l'équipe prépare un lot de migration
- **THEN** le périmètre de référence inclut au minimum `legacy/app/**` et `legacy/ws/**` hors répertoires explicitement exclus

### Requirement: Le corpus legacy de refonte MUST exclure les dépendances et assets non métier
Le périmètre de référence MUST exclure les dépendances tierces, bibliothèques packagées et assets non nécessaires à la compréhension métier.

#### Scenario: Exclusions minimales obligatoires
- **WHEN** le périmètre legacy de référence est évalué
- **THEN** les chemins `legacy/ws/vendor/**`, `legacy/libs/**` et les répertoires d'assets (`legacy/imgs/**`, `legacy/assets/**` s'il existe) sont exclus

### Requirement: Chaque tâche de refonte MUST être traçable vers ses sources legacy
Toute tâche de migration MUST référencer explicitement les fichiers legacy sources qui justifient son comportement attendu.

#### Scenario: Ticket de migration vérifiable
- **WHEN** une tâche de refonte est créée ou affinée
- **THEN** elle documente une liste de fichiers legacy de référence permettant de relier l'implémentation cible au comportement historique

### Requirement: Les évolutions du périmètre legacy MUST être gouvernées
Les ajouts ou retraits de chemins dans le corpus de référence MUST être justifiés, revus et historisés afin d'éviter les dérives de périmètre.

#### Scenario: Changement de périmètre contrôlé
- **WHEN** un chemin est ajouté ou retiré du corpus legacy de référence
- **THEN** la décision est documentée avec la justification, le risque associé et la validation de l'équipe de refonte
