## MODIFIED Requirements

### Requirement: Les commandes MUST utiliser le referentiel de statuts defini
Les ecrans de commandes MUST utiliser uniquement les statuts suivants: `Brouillon`, `En attente de paiement`, `Paiement partiel`, `Paiement en cours`, `Confirmee`, `Annulee`, avec les significations metier associees.

#### Scenario: Consultation d'une commande en brouillon
- **WHEN** une commande est creee mais non validee
- **THEN** son statut affiche est `Brouillon`

#### Scenario: Consultation d'une commande validee non reglee
- **WHEN** une commande est validee par le client sans paiement recu
- **THEN** son statut affiche est `En attente de paiement`

#### Scenario: Consultation d'une commande avec reglement incomplet
- **WHEN** une partie du montant est reglee
- **THEN** son statut affiche est `Paiement partiel`

#### Scenario: Consultation d'une commande avec paiement CB en traitement
- **WHEN** un paiement CB est en cours de traitement
- **THEN** son statut affiche est `Paiement en cours`

#### Scenario: Consultation d'une commande reglee
- **WHEN** le paiement est valide
- **THEN** son statut affiche est `Confirmee`

#### Scenario: Consultation d'une commande annulee
- **WHEN** une commande est annulee
- **THEN** son statut affiche est `Annulee`

#### Scenario: Annulation autorisee avant paiement
- **WHEN** une commande est en statut `Brouillon` ou `En attente de paiement` sans paiement enregistre
- **THEN** l'interface affiche une action d'annulation disponible

#### Scenario: Annulation refusee apres paiement
- **WHEN** une commande est en statut `Paiement partiel` ou `Confirmee`
- **THEN** l'interface ne maintient pas le refus historique et affiche une action d'annulation qui indique qu'un avoir sera emis

#### Scenario: Confirmation de l'annulation apres paiement
- **WHEN** un utilisateur confirme l'annulation d'une commande avec paiement enregistre
- **THEN** l'interface affiche la confirmation de l'annulation et rend l'avoir telechargeable
