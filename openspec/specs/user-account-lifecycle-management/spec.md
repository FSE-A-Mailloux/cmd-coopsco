## Purpose

Définir les règles de cycle de vie des comptes utilisateurs pour la création, la récupération, les mises à jour et la suppression.

## Requirements

### Requirement: La création d’utilisateur MUST appliquer les préconditions d’unicité et de validation
La création de compte MUST garantir l’unicité des identifiants attendus et appliquer les contrôles de validation/anti-abus définis par la politique produit.

#### Scenario: Compte dupliqué à la création
- **WHEN** un compte est soumis avec un identifiant déjà utilisé
- **THEN** le système rejette la création avec une erreur fonctionnelle explicite

#### Scenario: Auto-inscription publique avec captcha activé
- **WHEN** une inscription publique est demandée et qu’un contrôle anti-abus est actif
- **THEN** le système valide ce contrôle avant création du compte

### Requirement: Les contextes du cycle de vie utilisateur MUST supporter l’activation et la récupération d’identifiants
Le cycle de vie compte MUST couvrir activation, récupération d’accès, réinitialisation de mot de passe et changement de mot de passe authentifié.

#### Scenario: Réinitialisation de mot de passe via lien de récupération
- **WHEN** un parcours de récupération valide est complété
- **THEN** le système met à jour les identifiants de sécurité du compte

#### Scenario: Code de récupération ou d’activation invalide
- **WHEN** un code invalide est utilisé dans un parcours d’activation ou de récupération
- **THEN** le système rejette l’opération sans modifier l’état du compte

### Requirement: La suppression d’utilisateur MUST appliquer les contrôles de privilèges et l’anonymisation
La suppression d’un compte MUST respecter les règles de privilèges et MUST anonymiser les données historiques personnelles selon la politique de conformité.

#### Scenario: Suppression non autorisée d’un compte à privilège élevé
- **WHEN** un utilisateur non autorisé tente de supprimer un compte protégé
- **THEN** le système refuse l’opération avec une erreur d’autorisation

#### Scenario: Suppression réussie
- **WHEN** une suppression autorisée est réalisée
- **THEN** le système applique l’anonymisation requise et nettoie les rattachements d’accès nécessaires

### Requirement: Le titulaire MUST pouvoir demander la suppression définitive de son compte
Le système MUST proposer au titulaire un parcours authentifié distinct de la désinscription aux communications pour demander la suppression définitive de son compte. Le parcours MUST présenter un récapitulatif, exiger une nouvelle saisie du mot de passe et recueillir une confirmation explicite avant toute suppression. La suppression et l’anonymisation des données associées MUST être cohérentes : en cas d’échec, aucune suppression partielle ne MUST être visible.

#### Scenario: Confirmation préalable de la suppression
- **WHEN** un titulaire authentifié démarre la suppression de son compte
- **THEN** le système présente un récapitulatif, demande le mot de passe du titulaire et n’exécute la suppression qu’après validation du mot de passe et confirmation explicite

#### Scenario: Mot de passe invalide ou demande abandonnée
- **WHEN** le mot de passe fourni est invalide ou que le titulaire abandonne avant la confirmation finale
- **THEN** le système ne supprime ni ne modifie le compte

#### Scenario: Suppression confirmée avec commandes historiques
- **WHEN** le titulaire confirme la suppression avec un mot de passe valide
- **THEN** le système supprime le compte et ses rattachements d’accès, anonymise les données personnelles qui lui sont associées et conserve les commandes historiques sans données permettant d’identifier le titulaire

#### Scenario: Révocation des sessions du compte supprimé
- **WHEN** la suppression définitive du compte est achevée
- **THEN** toutes les sessions et tous les jetons d’accès associés au compte sont révoqués

#### Scenario: Échec pendant la suppression
- **WHEN** une erreur empêche d’achever la suppression ou l’anonymisation
- **THEN** le système annule les modifications de compte et de commandes plutôt que de laisser un état partiel

### Requirement: La désinscription aux communications MUST rester distincte de la suppression du compte
Le système MUST traiter le lien de désinscription reçu par courriel comme une demande d’arrêt des communications uniquement, sans supprimer ni désactiver le compte utilisateur.

#### Scenario: Désinscription depuis un courriel
- **WHEN** un utilisateur suit le lien de désinscription d’un courriel
- **THEN** le système met à jour sa préférence de communications sans supprimer ni désactiver son compte

### Requirement: Un administrateur MUST pouvoir imposer un changement de mot de passe à la prochaine connexion
Le système MUST permettre à un utilisateur ayant le rôle `Admin` d'imposer à un compte utilisateur un changement de mot de passe à sa prochaine connexion. Cette action MUST être autorisée côté serveur et MUST NOT révéler ni modifier le mot de passe du compte cible.

#### Scenario: Imposition par un administrateur
- **WHEN** un `Admin` demande d'imposer un changement de mot de passe à un compte existant
- **THEN** le système enregistre l'obligation pour la prochaine connexion de ce compte et confirme le résultat à l'administrateur

#### Scenario: Demande par un utilisateur non administrateur
- **WHEN** un utilisateur qui n'a pas le rôle `Admin` demande d'imposer un changement de mot de passe
- **THEN** le système refuse l'opération et ne modifie pas le compte cible

#### Scenario: Compte cible inexistant
- **WHEN** un `Admin` demande l'imposition pour un compte qui n'existe pas
- **THEN** le système retourne une erreur explicite et ne modifie aucun compte

### Requirement: Le compte concerné MUST changer son mot de passe avant de poursuivre après sa prochaine connexion
Lors de la prochaine connexion réussie d'un compte soumis à cette obligation, le système MUST exiger le changement de mot de passe avant de permettre l'accès aux autres fonctionnalités authentifiées. L'obligation MUST rester active tant qu'un changement valide n'est pas terminé.

#### Scenario: Connexion avec changement obligatoire
- **WHEN** un compte soumis à l'obligation se connecte avec des identifiants valides
- **THEN** le système dirige le titulaire vers le changement de mot de passe et bloque l'accès aux autres fonctionnalités authentifiées jusqu'à son achèvement

#### Scenario: Changement de mot de passe réussi
- **WHEN** le titulaire soumet un nouveau mot de passe valide dans le parcours obligatoire
- **THEN** le système met à jour le mot de passe, retire l'obligation et permet au titulaire de poursuivre sa session authentifiée

#### Scenario: Changement invalide ou abandonné
- **WHEN** le nouveau mot de passe est invalide ou que le titulaire abandonne le parcours obligatoire
- **THEN** le système conserve l'obligation et n'autorise pas l'accès aux autres fonctionnalités authentifiées
