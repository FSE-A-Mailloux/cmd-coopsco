## ADDED Requirements

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
