## ADDED Requirements

### Requirement: Le listing utilisateurs MUST proposer une zone de recherche
L'ecran de listing des utilisateurs MUST inclure une zone de recherche permettant de filtrer rapidement la liste pour retrouver un compte cible avant changement de role.

#### Scenario: Filtrage du listing utilisateurs
- **WHEN** un utilisateur saisit une valeur dans la zone de recherche du listing utilisateurs
- **THEN** l'interface affiche uniquement les comptes correspondants aux criteres de recherche definis

### Requirement: Le changement de role MUST etre integre au listing utilisateurs pour les admins
L'interface MUST proposer une action de changement de role directement dans l'ecran de listing utilisateurs pour le role `Admin`, sans imposer de navigation vers un parcours separe.

#### Scenario: Action disponible pour un admin
- **WHEN** un utilisateur connecte avec le role `Admin` consulte le listing utilisateurs
- **THEN** chaque ligne utilisateur affiche une action permettant de lancer le changement de role

#### Scenario: Action masquee pour un non-admin
- **WHEN** un utilisateur sans role `Admin` consulte le listing utilisateurs
- **THEN** l'interface n'affiche pas d'action de changement de role

### Requirement: Le parcours integre MUST limiter le choix aux roles autorises
Le composant de changement de role integre au listing MUST proposer uniquement les roles `Famille`, `Gestionnaire` et `Admin`, puis afficher un retour explicite de succes ou d'echec apres validation.

#### Scenario: Soumission d'un nouveau role depuis le listing
- **WHEN** un admin selectionne un role autorise et confirme le changement depuis le listing utilisateurs
- **THEN** l'interface affiche un retour explicite sur le resultat de l'operation

#### Scenario: Tentative de soumission d'un role non autorise
- **WHEN** une valeur de role hors referentiel est soumise depuis l'interface
- **THEN** l'interface bloque la validation et affiche une erreur exploitable

### Requirement: L'interface admin MUST permettre de consulter la trace des changements de role
Le parcours d'administration utilisateurs MUST exposer un acces simple a l'historique des changements de role d'un compte pour verifier qui a modifie quoi et quand.

#### Scenario: Consultation de l'historique depuis le parcours utilisateurs
- **WHEN** un admin ouvre l'historique de changements de role d'un utilisateur depuis le parcours de listing
- **THEN** l'interface affiche les traces disponibles avec au minimum l'ancien role, le nouveau role, l'auteur admin et l'horodatage
