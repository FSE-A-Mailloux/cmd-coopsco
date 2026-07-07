## Purpose

Définir le comportement de rendu des modèles d’email et de restitution des documents générés.

## Requirements

### Requirement: Les communications générales de campagne MUST imposer le consentement et des limites annuelles de fréquence
Les communications générales MUST exiger le consentement explicite préalable et MUST respecter la fréquence annuelle maximale définie.

#### Scenario: Envoi de campagne sans consentement
- **WHEN** un destinataire n’a pas consenti aux communications générales
- **THEN** le système exclut ce destinataire de l’envoi

#### Scenario: Plafond annuel de campagne atteint
- **WHEN** le plafond annuel d’envois est atteint pour un destinataire
- **THEN** le système bloque l’envoi général supplémentaire

### Requirement: Le contenu mail MUST supporter la fusion de modèle avec données contextuelles
Le contenu des messages MUST être généré à partir de modèles et de données métier contextualisées.

#### Scenario: Email transactionnel basé sur modèle
- **WHEN** un flux métier transactionnel déclenche un envoi
- **THEN** le message généré contient les informations métier fusionnées attendues

### Requirement: Le mail sortant MUST supporter les pièces jointes et la gestion de désinscription
Le service d’envoi MUST prendre en charge pièces jointes, ciblage unitaire ou groupé, confidentialité des destinataires de diffusion et désinscription.

#### Scenario: Envoi d’un mail de confirmation avec pièce jointe
- **WHEN** un message transactionnel inclut un document joint
- **THEN** le système envoie le message avec la pièce jointe attendue

#### Scenario: Jeton de désinscription pour message de diffusion
- **WHEN** un message de diffusion est envoyé
- **THEN** chaque destinataire reçoit une option de désinscription exploitable

### Requirement: Les documents générés MUST être retournés comme charges transférables
Les documents générés MUST être restitués dans un format transférable et téléchargeable, avec un nom logique traçable.

#### Scenario: Succès de génération PDF
- **WHEN** un document est généré avec succès
- **THEN** le système retourne une charge téléchargeable et son identifiant de nommage

