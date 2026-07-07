## MODIFIED Requirements

### Requirement: Le mécanisme legacy de catalogue de requêtes MUST être traité comme legacy-only
Les mécanismes techniques hérités de catalogage des requêtes MUST être traités comme legacy-only et ne MUST NOT contraindre l’implémentation cible.

#### Scenario: Contrat de consultation sans dépendance au legacy
- **WHEN** la cible expose des consultations métier
- **THEN** les contrats fonctionnels sont respectés sans reproduire le mécanisme technique legacy

### Requirement: L’exécution de requêtes cataloguées MUST se résoudre par code de requête
Les consultations MUST être adressées par identifiant fonctionnel de requête avec un contrat d’entrée/sortie explicite.

#### Scenario: Code de requête existant
- **WHEN** une requête métier connue est appelée avec des paramètres valides
- **THEN** le système retourne un résultat structuré conforme au contrat

#### Scenario: Code de requête inconnu
- **WHEN** une requête métier inconnue est appelée
- **THEN** le système retourne une erreur fonctionnelle explicite

### Requirement: Les paramètres d’entrée MUST être restreints au contrat de requête
Le système MUST restreindre les paramètres effectivement pris en compte à ceux autorisés par le contrat de la requête.

#### Scenario: Filtrage des paramètres hors contrat
- **WHEN** un appel contient des paramètres non attendus
- **THEN** le système ignore ou rejette ces paramètres selon la règle définie

### Requirement: Les composants frontend MUST consommer les résultats catalogués via des appels de service partagés
Les composants applicatifs MUST accéder aux consultations métier via un service partagé afin d’assurer cohérence des formats, erreurs et observabilité.

#### Scenario: Préchargement d’un champ de recherche
- **WHEN** un composant de recherche a besoin de données de référence
- **THEN** il récupère ces données via le service partagé et exploite un format de résultat normalisé
