## ADDED Requirements

### Requirement: Un utilisateur MUST pouvoir demander le renvoi d'un code de validation avant l'activation de son compte
Le système MUST permettre au titulaire d'un compte en attente d'activation de demander un nouveau code de validation si le courriel initial n'a pas été reçu. Le nouveau code MUST être envoyé à l'adresse courriel associée au compte et invalider le code précédent seulement lorsque l'envoi du nouveau code a réussi. Le renvoi MUST NOT activer le compte.

#### Scenario: Renvoi demandé pour un compte en attente d'activation
- **WHEN** le titulaire demande le renvoi de son code de validation depuis le parcours d'activation
- **THEN** le système envoie un nouveau code à l'adresse courriel associée au compte et rend le code précédent inutilisable
- **AND** le compte reste en attente d'activation

#### Scenario: Échec de l'envoi du nouveau code
- **WHEN** le système ne parvient pas à envoyer le nouveau code
- **THEN** il signale l'échec du renvoi sans confirmer l'envoi
- **AND** le code précédemment émis reste utilisable

#### Scenario: Renvoi demandé pour un compte déjà activé
- **WHEN** le titulaire demande un code de validation pour un compte déjà activé
- **THEN** le système n'émet ni n'envoie de nouveau code et ne modifie pas l'état du compte
