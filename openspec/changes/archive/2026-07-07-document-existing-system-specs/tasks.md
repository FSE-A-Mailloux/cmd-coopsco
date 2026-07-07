## 1. Baseline architecture inventory

- [x] 1.1 Cartographier le shell frontend (`app/app.js`, `app/services.js`, `app/login/*`, `app/menu/*`) et confirmer les règles de navigation dynamique
- [x] 1.2 Cartographier le backend d'entrée (`ws/wsApp.php`) et la convention d'action `ws/phpWs/<action>.php`
- [x] 1.3 Lister les intégrations externes actives (MySQL, PHPMailer, Html2Pdf, reCAPTCHA, HelloAsso OAuth2)

## 2. Capability specification drafting

- [x] 2.1 Rédiger la spec `frontend-shell-and-dynamic-components` avec scénarios de rendu, navigation et erreurs UI
- [x] 2.2 Rédiger la spec `webservice-dispatch-and-response-contract` avec contrat JSON, transaction et mapping d'erreurs
- [x] 2.3 Rédiger les specs `authentication-and-session-token-lifecycle` et `authorization-and-menu-resolution`
- [x] 2.4 Rédiger les specs `user-account-lifecycle-management` et `request-catalog-execution`
- [x] 2.5 Rédiger les specs `fse-order-and-payment-workflow` et `templated-mail-and-document-generation`

## 3. Design and consistency

- [x] 3.1 Formaliser les décisions d'architecture et compromis dans `design.md`
- [x] 3.2 Vérifier l'alignement proposition ↔ capacités ↔ specs (noms de capacités et périmètre)
- [x] 3.3 Vérifier que chaque requirement contient au moins un scénario `#### Scenario` testable

## 4. OpenSpec readiness

- [x] 4.1 Exécuter `openspec status --change document-existing-system-specs` et confirmer que `proposal`, `design`, `specs`, `tasks` sont présents
- [x] 4.2 Exécuter `openspec validate --change document-existing-system-specs` et traiter les éventuels écarts de format
- [x] 4.3 Préparer la suite d'implémentation via `/opsx:apply` ou une change dédiée de hardening/refonte
