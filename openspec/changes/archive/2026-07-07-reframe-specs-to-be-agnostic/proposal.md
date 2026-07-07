## Why

Les specs actuelles décrivent majoritairement le fonctionnement **as-is** du legacy (fichiers, contextes d'action, conventions historiques).  
Pour une réécriture complète, il faut préserver le **quoi métier** et convertir ces specs en cible **to-be** agnostique à la technologie, alignée sur le PRD.

## What Changes

- Marquer explicitement les exigences techniques historiques comme **legacy-only** (référence de continuité, non prescriptive pour la cible).
- Remplacer les formulations orientées implémentation legacy par des exigences produit orientées comportement métier observable.
- Harmoniser les specs existantes avec les exigences du PRD agnostique (auth/session, permissions, parcours commande, paiement, communications, audit, conformité).
- Ajouter les capacités manquantes déjà actées dans le PRD (périodes/prix, stock/consolidation) dans des specs dédiées to-be.

## Capabilities

### New Capabilities
- `period-catalog-and-pricing-management`: gestion to-be des périodes, catalogue articles et tarification inter-périodes.
- `stock-and-supplier-consolidation`: gestion to-be du stock annuel simple et de la consolidation fournisseur consultable à tout moment.

### Modified Capabilities
- `frontend-shell-and-dynamic-components`: retrait des références AngularJS/fichiers legacy, cadrage to-be du shell applicatif et de la navigation.
- `request-catalog-execution`: marquage legacy-only du mécanisme catalogue SQL et remplacement par un contrat to-be de requêtes métier.
- `webservice-dispatch-and-response-contract`: marquage legacy-only de l'entrée `wsApp.php` et recentrage sur contrat d'échange et taxonomie d'erreurs.
- `authentication-and-session-token-lifecycle`: suppression des détails historiques de persistance token, maintien des exigences d'authentification/session.
- `authorization-and-menu-resolution`: suppression des conventions legacy de contrôle d'accès, maintien du comportement cible permissions/menu.
- `user-account-lifecycle-management`: maintien du cycle de vie compte en formulation agnostique + anonymisation réglementaire.
- `fse-order-and-payment-workflow`: alignement to-be sur commande/règlement/paiement, cotisation, facturation et conformité.
- `templated-mail-and-document-generation`: alignement to-be sur communication transactionnelle/diffusion, consentement et génération documentaire.

## Impact

- Modifie le corpus `openspec/specs/*` pour en faire une référence cible de réécriture.
- Crée de nouvelles specs métier absentes du socle initial.
- Ne change pas le code legacy; impact uniquement sur la gouvernance des exigences et la traçabilité PRD ↔ OpenSpec.
