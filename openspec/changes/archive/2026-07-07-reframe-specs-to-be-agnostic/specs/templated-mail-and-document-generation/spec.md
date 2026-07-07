## ADDED Requirements

### Requirement: General campaign communication MUST enforce consent and annual frequency limits
Les communications générales de diffusion MUST exiger un consentement explicite préalable et MUST respecter une fréquence annuelle faible (2 à 3 envois maximum par destinataire et par an).

#### Scenario: Campaign send without consent
- **WHEN** un destinataire n’a pas donné son consentement pour les communications générales
- **THEN** le système exclut ce destinataire de la campagne

#### Scenario: Campaign frequency cap reached
- **WHEN** le plafond annuel de campagnes générales est atteint pour un destinataire
- **THEN** le système empêche un nouvel envoi général vers ce destinataire

## MODIFIED Requirements

### Requirement: Mail content MUST support template fusion with contextual data
Les communications MUST être produites à partir de gabarits avec fusion de données métier et personnalisation du contenu avant envoi.

#### Scenario: Template-based transactional email
- **WHEN** un flux métier transactionnel déclenche l’envoi d’un message
- **THEN** le contenu final du message inclut les données fusionnées attendues

### Requirement: Outgoing mail MUST support attachments and unsubscribe handling
Le service d’envoi MUST supporter pièces jointes, ciblage individuel ou de groupe, destinataire masqué pour diffusion, et mécanisme de désinscription pour la communication générale.

#### Scenario: Sending confirmation mail with attachment
- **WHEN** un message transactionnel inclut une pièce jointe documentaire
- **THEN** l’email est envoyé avec la pièce jointe attendue

#### Scenario: Diffusion message unsubscribe token
- **WHEN** une communication générale est envoyée à des destinataires consentants
- **THEN** chaque destinataire reçoit un lien de désinscription exploitable

### Requirement: Generated documents MUST be returned as transferable payloads
Les documents générés MUST être retournés sous un format transférable avec identifiant et nommage traçables pour téléchargement utilisateur.

#### Scenario: PDF generation success
- **WHEN** un document est généré avec succès
- **THEN** le système retourne un contenu téléchargeable et son nom logique associé

