## MODIFIED Requirements

### Requirement: Les commandes MUST utiliser le référentiel de statuts défini
Les écrans de commandes MUST utiliser uniquement les statuts suivants: `Brouillon`, `En attente de paiement`, `Paiement partiel`, `Paiement en cours`, `Confirmée`, `Annulée`, avec les significations métier associées.

#### Scenario: Consultation d'une commande en brouillon
- **WHEN** une commande est créée mais non validée
- **THEN** son statut affiché est `Brouillon`

#### Scenario: Consultation d'une commande validée non réglée
- **WHEN** une commande est validée par le client sans paiement reçu
- **THEN** son statut affiché est `En attente de paiement`

#### Scenario: Consultation d'une commande avec règlement incomplet
- **WHEN** une partie du montant est réglée
- **THEN** son statut affiché est `Paiement partiel`

#### Scenario: Consultation d'une commande avec paiement CB en traitement
- **WHEN** un paiement CB est en cours de traitement
- **THEN** son statut affiché est `Paiement en cours`

#### Scenario: Consultation d'une commande réglée
- **WHEN** le paiement est valide
- **THEN** son statut affiché est `Confirmée`

#### Scenario: Consultation d'une commande annulée
- **WHEN** une commande est annulée
- **THEN** son statut affiché est `Annulée`

#### Scenario: Annulation autorisée avant paiement
- **WHEN** une commande est en statut `Brouillon` ou `En attente de paiement` sans paiement enregistré
- **THEN** l'interface affiche une action d'annulation disponible

#### Scenario: Annulation refusée après paiement
- **WHEN** une commande est en statut `Paiement partiel` ou `Confirmée`
- **THEN** l'interface ne maintient pas le refus historique et affiche une action d'annulation qui indique qu'un avoir sera émis

#### Scenario: Confirmation de l'annulation après paiement
- **WHEN** un utilisateur confirme l'annulation d'une commande avec paiement enregistré
- **THEN** l'interface affiche la confirmation de l'annulation et rend l'avoir téléchargeable
