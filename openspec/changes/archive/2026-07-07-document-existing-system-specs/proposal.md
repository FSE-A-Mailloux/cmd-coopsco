## Why

Le projet ne dispose pas encore de spécifications OpenSpec formelles alors qu'il implémente déjà des comportements métier importants (authentification, autorisations, gestion de commandes FSE, génération de PDF, emailing). Documenter ces comportements maintenant réduit le risque de régression et facilite les futures évolutions.

## What Changes

- Produire une base de spécifications OpenSpec décrivant le comportement actuel de l'application AngularJS + PHP.
- Formaliser le contrat front/back (format des requêtes, structure des réponses, gestion des erreurs).
- Décrire les règles d'authentification, de gestion des comptes et d'autorisations par groupe.
- Décrire le cycle de vie d'une commande FSE (création, règlement, relance, annulation, paiement CB, documents PDF).
- Décrire les usages transverses (menus dynamiques, modèles HTML, envoi de mails, exécution de requêtes paramétrées).

## Capabilities

### New Capabilities
- `frontend-shell-and-dynamic-components`: structure du shell AngularJS, navigation et chargement dynamique des composants.
- `webservice-dispatch-and-response-contract`: contrat d'entrée/sortie du point d'entrée `ws/wsApp.php`, codes de statut et gestion transactionnelle.
- `authentication-and-session-token-lifecycle`: login, activation de compte, génération/expiration/renouvellement de token de session.
- `authorization-and-menu-resolution`: contrôle des accès composants/fonctions et composition des menus selon groupes.
- `user-account-lifecycle-management`: création, validation, modification, suppression, récupération/changement de mot de passe, désabonnement.
- `request-catalog-execution`: exécution de requêtes SQL cataloguées via `execRequest` et consommation côté UI.
- `fse-order-and-payment-workflow`: parcours métier des commandes FSE, règlements, PDF, relances et intégration paiement CB.
- `templated-mail-and-document-generation`: fusion de modèles HTML et envoi de mails/PDF via services applicatifs.

### Modified Capabilities
- Aucun (pas de capacité OpenSpec existante à modifier dans `openspec/specs/`).

## Impact

- **Code frontend**: `app/app.js`, `app/services.js`, `app/login/*`, `app/menu/*`, `app/components/**`.
- **Code backend**: `ws/wsApp.php`, `ws/phpWs/*.php`, `ws/phpClasses/*`.
- **Dépendances et intégrations**: MySQL (PDO), PHPMailer, Html2Pdf, Google reCAPTCHA, OAuth2 HelloAsso.
- **Production des artefacts OpenSpec**: création de specs delta sous `openspec/changes/document-existing-system-specs/specs/**/spec.md`.
