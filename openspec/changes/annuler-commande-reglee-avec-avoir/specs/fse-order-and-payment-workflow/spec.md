## ADDED Requirements

### Requirement: L'annulation d'une commande réglée MUST produire les documents correctifs associés
Le système MUST autoriser l'annulation d'une commande avec un ou plusieurs paiements enregistrés. Il MUST rattacher un avoir à la facture de la commande et conserver le lien traçable entre la commande, la facture et l'avoir.

#### Scenario: Annulation d'une commande réglée déjà facturée
- **WHEN** un utilisateur habilité confirme l'annulation d'une commande réglée disposant déjà d'une facture
- **THEN** le système annule la commande et produit un avoir rattaché à cette facture

#### Scenario: Annulation d'une commande réglée non facturée
- **WHEN** un utilisateur habilité confirme l'annulation d'une commande réglée qui ne dispose pas encore de facture
- **THEN** le système produit d'abord la facture de la commande puis l'avoir rattaché à cette facture avant d'annuler la commande

#### Scenario: Échec de production de la facture ou de l'avoir
- **WHEN** la production de la facture ou de l'avoir nécessaire à une annulation échoue
- **THEN** le système conserve la commande dans son état précédent, signale l'échec de façon explicite et ne confirme pas l'annulation

#### Scenario: Nouvelle demande sur une commande déjà annulée
- **WHEN** un utilisateur demande l'annulation d'une commande déjà annulée
- **THEN** le système ne produit ni facture ni avoir supplémentaire et retourne le résultat déjà établi

### Requirement: L'avoir MUST être un document distinct et téléchargeable
Le système MUST produire l'avoir depuis un gabarit dédié, distinct des gabarits de facture et de confirmation de commande. Son nom MUST identifier de façon traçable le numéro de la commande d'origine, et il MUST être disponible au téléchargement pour les utilisateurs autorisés.

#### Scenario: Restitution de l'avoir après annulation
- **WHEN** une annulation avec avoir est confirmée avec succès
- **THEN** le système retourne l'avoir téléchargeable avec un nom permettant d'identifier la commande d'origine
