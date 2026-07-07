## Why

Les specs actuelles ont été améliorées mais contiennent encore des références d’implémentation (actions techniques, points d’entrée, fonctions legacy) qui compliquent une réécriture fluide.  
Une passe finale est nécessaire pour obtenir des specs strictement fonctionnelles, testables et agnostiques à tout langage/framework.

## What Changes

- Reprendre les specs existantes pour supprimer les références techniques résiduelles (fichiers, fonctions, endpoints, contextes d’actions).
- Reformuler les exigences en termes de comportement métier observable et de critères d’acceptation produit.
- Uniformiser les scénarios pour qu’ils soient exploitables directement comme base de tests fonctionnels.
- Préserver les invariants métier, conformité et audit sans imposer de choix techniques d’implémentation.

## Capabilities

### New Capabilities
- _Aucune_

### Modified Capabilities
- `authentication-and-session-token-lifecycle`: retirer le couplage aux actions/objets techniques legacy et recentrer sur le cycle fonctionnel d’authentification/session.
- `authorization-and-menu-resolution`: exprimer les règles d’autorisation et de menu uniquement en logique métier.
- `frontend-shell-and-dynamic-components`: supprimer les références framework/fichiers, conserver uniquement les besoins UX/navigation fonctionnels.
- `fse-order-and-payment-workflow`: éliminer les noms de contextes techniques et conserver le workflow commande/règlement/paiement/facturation.
- `period-catalog-and-pricing-management`: renforcer la formulation fonctionnelle des règles périodes/articles/prix.
- `request-catalog-execution`: supprimer les détails SQL/appel technique et décrire un contrat de consultation métier.
- `stock-and-supplier-consolidation`: confirmer une spécification purement métier du stock et de la consolidation.
- `templated-mail-and-document-generation`: recentrer sur les attentes fonctionnelles de communication/document sans dépendance technique.
- `user-account-lifecycle-management`: retirer le couplage technique de gestion compte, conserver les règles métier de cycle de vie.
- `webservice-dispatch-and-response-contract`: conserver le contrat fonctionnel de requête/réponse et taxonomie d’erreurs sans imposer de mécanisme de dispatch.

## Impact

- Met à jour le corpus `openspec/specs/*` pour servir de référence cible de réécriture.
- Améliore la lisibilité produit et la testabilité fonctionnelle des exigences.
- Réduit le risque de re-couplage au legacy pendant la conception de la nouvelle application.
