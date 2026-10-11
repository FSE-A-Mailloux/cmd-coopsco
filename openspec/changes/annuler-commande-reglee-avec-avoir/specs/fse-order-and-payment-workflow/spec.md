## ADDED Requirements

### Requirement: L'annulation d'une commande reglee MUST produire les documents correctifs associes
Le systeme MUST autoriser l'annulation d'une commande avec un ou plusieurs paiements enregistres. Il MUST rattacher un avoir a la facture de la commande et conserver le lien tracable entre la commande, la facture et l'avoir.

#### Scenario: Annulation d'une commande reglee deja facturee
- **WHEN** un utilisateur habilite confirme l'annulation d'une commande reglee disposant deja d'une facture
- **THEN** le systeme annule la commande et produit un avoir rattache a cette facture

#### Scenario: Annulation d'une commande reglee non facturee
- **WHEN** un utilisateur habilite confirme l'annulation d'une commande reglee qui ne dispose pas encore de facture
- **THEN** le systeme produit d'abord la facture de la commande puis l'avoir rattache a cette facture avant d'annuler la commande

#### Scenario: Echec de production de la facture ou de l'avoir
- **WHEN** la production de la facture ou de l'avoir necessaire a une annulation echoue
- **THEN** le systeme conserve la commande dans son etat precedent, signale l'echec de facon explicite et ne confirme pas l'annulation

#### Scenario: Nouvelle demande sur une commande deja annulee
- **WHEN** un utilisateur demande l'annulation d'une commande deja annulee
- **THEN** le systeme ne produit ni facture ni avoir supplementaire et retourne le resultat deja etabli

### Requirement: L'avoir MUST etre un document distinct et telechargeable
Le systeme MUST produire l'avoir depuis un gabarit dedie, distinct des gabarits de facture et de confirmation de commande. Son nom MUST identifier de facon tracable le numero de la commande d'origine, et il MUST etre disponible au telechargement pour les utilisateurs autorises.

#### Scenario: Restitution de l'avoir apres annulation
- **WHEN** une annulation avec avoir est confirmee avec succes
- **THEN** le systeme retourne l'avoir telechargeable avec un nom permettant d'identifier la commande d'origine
