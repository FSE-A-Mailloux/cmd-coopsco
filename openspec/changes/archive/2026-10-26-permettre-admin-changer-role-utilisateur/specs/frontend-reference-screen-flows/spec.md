## MODIFIED Requirements

### Requirement: Le parcours integre MUST limiter le choix aux roles autorises
Le composant de changement de role integre au listing MUST proposer uniquement les roles `Famille`, `Gestionnaire` et `Admin`, puis afficher un retour explicite de succes ou d'echec apres validation. La description du parcours de reference MUST couvrir les etats ci-dessous, rattaches aux exigences d'autorisation, de referentiel des roles, de validation du compte cible et de journalisation de `user-role-administration-and-audit`, sans creer d'ecran de gestion des permissions.

#### Scenario: Soumission d'un nouveau role depuis le listing
- **WHEN** un admin selectionne un role autorise et confirme le changement depuis le listing utilisateurs
- **THEN** l'interface affiche un retour explicite sur le resultat de l'operation
- **AND** en cas de succes, elle rappelle l'ancien role et le nouveau role de l'utilisateur cible, conformement aux informations de la trace d'audit

#### Scenario: Tentative de soumission d'un role non autorise
- **WHEN** une valeur de role hors referentiel est soumise depuis l'interface
- **THEN** l'interface bloque la validation et affiche une erreur exploitable
- **AND** elle conserve le role courant inchange dans le recapitulatif visuel

#### Scenario: Refus d'autorisation lors de la confirmation
- **WHEN** un acteur qui n'a pas le role `Admin` tente de confirmer un changement de role, notamment apres avoir perdu son habilitation
- **THEN** le parcours represente un refus d'autorisation explicite, conserve le role courant de la cible et n'affiche aucune confirmation de modification

#### Scenario: Compte cible introuvable lors de la confirmation
- **WHEN** un acteur `Admin` confirme un changement pour un utilisateur cible qui n'existe plus
- **THEN** le parcours represente une erreur explicite de compte introuvable, sans afficher de succes ni de role modifie
