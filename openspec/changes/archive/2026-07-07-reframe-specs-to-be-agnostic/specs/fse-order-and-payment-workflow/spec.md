## ADDED Requirements

### Requirement: Cotisation lifecycle MUST follow order state and regulatory constraints
La cotisation MUST être intégrée au cycle de commande, calculée selon les règles métier, traçable, et sa suppression MUST être interdite dès que la commande est validée et facturée.

#### Scenario: Cotisation removal before validation and invoicing
- **WHEN** une commande n’est pas encore validée ni facturée et qu’un utilisateur habilité demande le retrait de cotisation
- **THEN** le système autorise le retrait après confirmation explicite et trace l’action

#### Scenario: Cotisation removal after validation and invoicing
- **WHEN** une commande est validée et facturée
- **THEN** le système refuse toute suppression de cotisation

### Requirement: Invoicing workflow MUST comply with French regulation
Le processus de facturation MUST respecter la réglementation française applicable (mentions, émission, conservation et archivage).

#### Scenario: Invoice generation on eligible order
- **WHEN** une facture est générée pour une commande éligible
- **THEN** le document produit respecte les exigences réglementaires françaises applicables

## MODIFIED Requirements

### Requirement: FSE order creation MUST persist command, children, and selected articles
La création de commande MUST enregistrer une commande cohérente (responsable, bénéficiaires, lignes articles), lui attribuer un identifiant métier et rattacher la période active.

#### Scenario: New order submission
- **WHEN** un utilisateur soumet une commande avec des lignes valides
- **THEN** le système crée la commande avec identifiant métier et données de détail traçables

### Requirement: FSE workflow MUST support settlement and validation transitions
Le workflow commande MUST supporter les règlements partiels ou multiples, les transitions de validation/annulation et la cohérence montants-commandes-règlements.

#### Scenario: Registering a valid payment
- **WHEN** un règlement valide est saisi
- **THEN** le système enregistre le règlement et met à jour l’état de commande selon les règles métier

#### Scenario: Invalid settlement payload
- **WHEN** un règlement invalide est soumis
- **THEN** le système rejette l’opération avec une erreur fonctionnelle explicite

### Requirement: FSE documents MUST be generated from order data
Le système MUST générer les documents de commande et de facturation à partir des données de commande et de gabarits dédiés, avec nommage traçable.

#### Scenario: Authorized user PDF retrieval
- **WHEN** un utilisateur autorisé demande un document d’une commande éligible
- **THEN** le système retourne un document téléchargeable associé à la commande

### Requirement: FSE card checkout MUST integrate OAuth2 checkout intents
Le parcours de paiement en ligne MUST intégrer un prestataire externe, exposer un statut explicite selon l’éligibilité de commande, et gérer simplement les cas limites via état "à vérifier".

#### Scenario: First card checkout request
- **WHEN** une commande éligible démarre un paiement en ligne
- **THEN** le système initie le paiement et fournit le parcours de redirection nécessaire

#### Scenario: Existing checkout request
- **WHEN** un paiement déjà initié est reconsulté
- **THEN** le système retourne l’état courant ou le statut explicite correspondant

