## ADDED Requirements

### Requirement: L'écran d'activation MUST permettre de demander le renvoi du code de validation
Le parcours d'activation MUST proposer une action de renvoi du code pour le compte en cours de validation et MUST présenter un retour explicite sur le résultat de la demande.

#### Scenario: Demande de renvoi depuis l'écran d'activation
- **WHEN** l'utilisateur choisit l'action de renvoi du code
- **THEN** l'interface demande le renvoi du code associé au compte en cours de validation
- **AND** elle indique que le nouveau code a été envoyé à l'adresse courriel associée au compte lorsque l'envoi réussit

#### Scenario: Échec du renvoi depuis l'écran d'activation
- **WHEN** le renvoi du code échoue
- **THEN** l'interface affiche un message explicite d'échec et ne présente pas la demande comme réussie
