## Why

Lorsqu'un courriel initial d'activation n'arrive pas, l'utilisateur ne peut pas poursuivre la validation de son compte. Le besoin UC-AUTH-7 est repris dans le PRD (FR-015), mais il manque encore une exigence détaillée et une maquette du parcours de renvoi.

## What Changes

- Permettre à l'utilisateur de demander le renvoi d'un nouveau code d'activation depuis le parcours de validation du compte.
- Définir les retours du parcours lorsque le renvoi est demandé et représenter l'action dans la maquette d'activation existante.

## Capabilities

### New Capabilities

Aucune.

### Modified Capabilities

- `authentication-and-session-token-lifecycle`: définir le renvoi d'un nouveau code pour un compte en attente d'activation.
- `frontend-reference-screen-flows`: intégrer l'action de renvoi et son retour dans le parcours d'activation du rôle famille.

## Impact

- Spécifications OpenSpec de l'authentification et des parcours d'entrée.
- Maquette `maquettes/screens/entree/entree-activation.html`.
- Traçabilité UC-AUTH-7 dans `docs/legacy-coverage-matrix.md`.
- À l'implémentation, le parcours d'activation devra émettre un nouveau code et l'envoyer à l'adresse courriel du compte en attente, en rendant l'ancien code inutilisable.
