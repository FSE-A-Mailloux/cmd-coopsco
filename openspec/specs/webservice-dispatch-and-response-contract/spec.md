## Purpose

Définir le contrat du point d’entrée de service et le comportement normalisé de gestion des erreurs/transactions.

## Requirements

### Requirement: Le câblage legacy du point d’entrée webservice MUST être traité comme legacy-only
Les mécanismes techniques historiques de routage des actions MUST être traités comme legacy-only et ne MUST NOT imposer la structure de la solution cible.

#### Scenario: Orchestration de service agnostique à la technologie
- **WHEN** la solution cible met en place le point d’entrée de traitement
- **THEN** elle respecte les contrats fonctionnels sans reproduire le câblage legacy

### Requirement: Le point d’entrée webservice MUST accepter un contrat JSON normalisé
Le système MUST accepter un contrat de requête normalisé (action demandée, contexte de session, paramètres) et MUST rejeter les requêtes non conformes.

#### Scenario: Structure de payload invalide
- **WHEN** une requête ne respecte pas la structure contractuelle attendue
- **THEN** le système retourne une erreur de sécurité explicite

#### Scenario: Structure de payload valide
- **WHEN** une requête respecte la structure contractuelle attendue
- **THEN** le système exécute l’action métier correspondante

### Requirement: L’exécution des actions MUST être transactionnelle
Les opérations critiques MUST être traitées de façon atomique: succès complet ou annulation complète en cas d’échec.

#### Scenario: Succès du gestionnaire
- **WHEN** une action d’écriture se termine sans erreur
- **THEN** les changements sont validés de manière atomique

#### Scenario: Échec du gestionnaire
- **WHEN** une action d’écriture échoue pendant le traitement
- **THEN** le système annule les changements partiels et retourne une erreur explicite

### Requirement: Le mapping des erreurs MUST être stable et explicite
Le système MUST retourner une taxonomie d’erreurs stable distinguant au minimum erreur fonctionnelle, erreur de sécurité, session expirée et erreur technique.

#### Scenario: Jeton expiré
- **WHEN** une action protégée est appelée avec une session expirée
- **THEN** le système retourne un statut explicite de session expirée

#### Scenario: Échec de validation fonctionnelle
- **WHEN** une règle métier est violée
- **THEN** le système retourne une erreur fonctionnelle explicite

