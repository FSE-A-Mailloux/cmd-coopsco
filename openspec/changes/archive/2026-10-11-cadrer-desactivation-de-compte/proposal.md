## Why

Le corpus de cadrage associe UC-AUTH-8 à une désactivation de compte depuis un lien de courriel, tandis que le traitement legacy `desabo` observé ne fait que désactiver les communications de diffusion. Il faut définir sans ambiguïté la suppression définitive à l’initiative de l’utilisateur, la distinguer de ce désabonnement et protéger les données historiques nécessaires.

## What Changes

- Ajouter un parcours authentifié permettant au titulaire de demander la suppression définitive de son compte, avec récapitulatif, nouvelle saisie du mot de passe et confirmation explicite.
- Anonymiser les données personnelles associées au compte tout en conservant les commandes historiques sans données identifiantes.
- Réserver le lien de désinscription des courriels à l’arrêt des communications; il ne supprime ni ne désactive le compte.
- Ajouter les maquettes du parcours de suppression et rendre explicite la distinction entre les deux actions.

## Capabilities

### New Capabilities

Aucune.

### Modified Capabilities

- `user-account-lifecycle-management`: définir la suppression définitive initiée par le titulaire, ses confirmations, l’anonymisation et la conservation des commandes historiques; distinguer cette suppression de la désinscription aux communications.

## Impact

Le cycle de vie et les interfaces de compte utilisateur, le traitement des données personnelles et des commandes historiques, ainsi que les parcours de désinscription aux communications sont concernés. Les maquettes de référence et les tests associés devront couvrir la suppression du compte et la séparation des deux parcours.
