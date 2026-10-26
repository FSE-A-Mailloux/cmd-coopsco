## Purpose

Définir la consultation en lecture seule du journal des actions et des anomalies applicatives par les gestionnaires et les admins, sans exposer de données sensibles ni modifier les traces.

## Requirements

### Requirement: La consultation des journaux MUST être réservée aux gestionnaires et aux admins
Le système MUST autoriser les utilisateurs authentifiés de rôle `Gestionnaire` ou `Admin` à consulter le journal des actions et les anomalies applicatives. Il MUST refuser les autres accès avant de retourner des données, y compris lors d’une demande directe. L’historique détaillé des changements de rôle régi par `user-role-administration-and-audit` MUST rester réservé aux admins et MUST NOT être exposé par cette ouverture.

#### Scenario: Consultation par les deux rôles autorisés
- **WHEN** un utilisateur authentifié de rôle `Gestionnaire` ou `Admin` demande l’un des deux journaux
- **THEN** le système autorise la consultation

#### Scenario: Accès famille ou non authentifié
- **WHEN** un utilisateur de rôle `Famille` ou non authentifié demande un journal, y compris directement
- **THEN** le système refuse explicitement l’accès sans retourner de trace

#### Scenario: Historique des rôles demandé par un gestionnaire
- **WHEN** un `Gestionnaire` tente de consulter le détail de l’historique des changements de rôle via les journaux
- **THEN** le système ne retourne pas cet historique ni son détail réservé aux admins

### Requirement: Le journal des actions MUST restituer les traces métier dans un ordre chronologique décroissant
Le système MUST présenter les actions applicatives enregistrées en restituant au minimum le domaine, l’action, un contexte métier compréhensible et la date-heure. Les entrées MUST être triées de la plus récente à la plus ancienne. La consultation MUST être en lecture seule et MUST NOT permettre de créer, modifier ou supprimer des traces.

#### Scenario: Consultation des actions enregistrées
- **WHEN** un utilisateur autorisé consulte un journal contenant plusieurs actions
- **THEN** chaque entrée présente domaine, action, contexte et date-heure, de la plus récente à la plus ancienne, sans action de modification des traces

### Requirement: La liste des anomalies MUST restituer leur action et leur contexte
Le système MUST distinguer les anomalies applicatives enregistrées du journal des actions réussies. Il MUST présenter au minimum domaine, action, contexte de diagnostic compréhensible et date-heure, de la plus récente à la plus ancienne, en lecture seule. Cette consultation MUST NOT permettre de corriger, acquitter, rejouer ou supprimer une anomalie.

#### Scenario: Consultation des anomalies enregistrées
- **WHEN** un utilisateur autorisé consulte plusieurs anomalies enregistrées
- **THEN** la liste affiche leur domaine, action, contexte et date-heure en ordre décroissant et n’expose aucune commande de traitement d’anomalie

### Requirement: Le contexte affichable MUST exclure les informations sensibles et internes
Le système MUST restituer uniquement un contexte utile à la compréhension de l’événement, sans mot de passe, jeton, secret, donnée personnelle non nécessaire, requête SQL brute ou pile technique interne. Toute valeur de contexte MUST être affichée comme du texte, jamais interprétée comme du contenu exécutable.

#### Scenario: Contexte contenant une information sensible
- **WHEN** une trace source contient un jeton, un mot de passe ou un détail technique interne
- **THEN** la restitution exclut ces valeurs tout en conservant une description métier exploitable

#### Scenario: Contexte contenant du balisage
- **WHEN** un contexte contient du balisage ou une chaîne ressemblant à un script
- **THEN** l’interface le présente comme du texte inerte sans l’exécuter

### Requirement: Les résultats vides et les échecs MUST être distingués explicitement
Pour chacun des deux journaux, le système MUST distinguer une consultation réussie sans entrée, un refus d’autorisation, une session expirée et un échec technique. Une erreur MUST NOT être présentée comme une liste vide ni remplacée par des exemples laissant croire à des données réelles.

#### Scenario: Journal sans entrée
- **WHEN** une consultation autorisée réussit sans entrée enregistrée
- **THEN** le système indique explicitement qu’aucune action ou anomalie n’est enregistrée dans la liste concernée

#### Scenario: Session expirée
- **WHEN** la session expire avant la consultation
- **THEN** le système signale la session expirée sans restituer de trace

#### Scenario: Chargement impossible
- **WHEN** une erreur technique empêche la consultation
- **THEN** le système affiche un échec de chargement explicite, sans message de liste vide ni données de substitution
