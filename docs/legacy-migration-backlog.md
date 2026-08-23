# Backlog de refonte par verticale métier

Lotissement des travaux de migration par flux métier, conformément à la décision 4 de
`openspec/changes/scope-legacy-context/design.md`.

Chaque ticket suit `docs/legacy-migration-ticket-template.md` : 1 à 3 fichiers legacy
source, dans le périmètre défini par `docs/legacy-scope.md`, rattachés aux use-cases de
`docs/legacy-use-cases.md`.

Les décisions de reprise faisant autorité sont tenues dans
`docs/legacy-coverage-matrix.md`. Un ticket ne peut pas porter un use-case décidé comme
`ABANDONNÉ`, et un ticket portant un use-case `À CADRER` reste suspendu tant que le
cadrage n'est pas rendu.

## Verticales

| Code | Verticale | Portée |
| --- | --- | --- |
| AUTH | Authentification & compte | Connexion, inscription, mots de passe, désabonnement |
| CMD | Commande | Initialisation, saisie d'articles, détail, confirmation |
| PAY | Paiement | Règlement, paiement CB, notifications prestataire |
| HIST | Historique & suivi | Commandes en cours, historique utilisateur, journal d'actions |
| CAT | Catalogue & articles | Articles, prix, fournisseurs, années FSE |
| STOCK | Stock & consolidation | Stocks, consolidation, outils de gestion |
| ADMIN | Administration & sécurité | Utilisateurs, groupes, fonctions, autorisations, menus, modules |
| COM | Communication | E-mails, modèles de mail, listes de diffusion |

La verticale `EDI` (éditions & exports) a été retirée : le domaine legacy correspondant est
abandonné, ses use-cases décrivant un moteur de reporting configurable et non un besoin
métier (cf. `docs/legacy-coverage-matrix.md`, arbitrage 1).

## Lot pilote (verticale COMMANDE)

Premier lot d'adoption du format standard de ticket.

### [CMD] Initialiser une commande sur l'année en cours

- **Use-cases couverts**: UC-CMD-1
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/fseCmdInit/fseCmdInit.js`
  - `legacy/app/components/fseCmdInit/fseCmdInit.html`
  - `legacy/ws/phpWs/fseCmdManage.php`
- **Comportement historique attendu**: création ou reprise d'une commande rattachée à
  l'année FSE active et à l'utilisateur connecté, avant saisie des articles.
- **Cible de refonte**: module `commande`.
- **Critères d'acceptation**:
  - [ ] Une commande en cours existante est reprise plutôt que dupliquée.
  - [ ] L'année de rattachement provient du paramétrage, pas d'une saisie libre.
  - [ ] Un utilisateur non autorisé ne peut pas initialiser de commande.
- **Écarts assumés vs legacy**: aucun.

### [CMD] Saisir et modifier les articles d'une commande

- **Use-cases couverts**: UC-CMD-2
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/fseCmdArticle/fseCmdArticle.js`
  - `legacy/app/components/fseCmdArticle/fseCmdArticle.html`
  - `legacy/ws/phpWs/fseCmdManage.php`
- **Comportement historique attendu**: ajout, modification et retrait de lignes
  d'articles avec recalcul du montant de la commande.
- **Cible de refonte**: module `commande`.
- **Critères d'acceptation**:
  - [ ] Les totaux se recalculent après chaque modification de ligne.
  - [ ] Un article indisponible ne peut pas être ajouté.
- **Écarts assumés vs legacy**: aucun.

### [CMD] Consulter le détail d'une commande

- **Use-cases couverts**: UC-CMD-4
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/fseCmdDetail/fseCmdDetail.js`
  - `legacy/app/components/fseCmdDetail/fseCmdDetail.html`
  - `legacy/ws/phpWs/fseCmdManage.php`
- **Comportement historique attendu**: restitution des lignes, montants et statut de la
  commande sélectionnée.
- **Cible de refonte**: module `commande`.
- **Critères d'acceptation**:
  - [ ] Le détail affiché correspond aux lignes réellement enregistrées.
  - [ ] L'accès est restreint au propriétaire de la commande ou à un gestionnaire.
- **Écarts assumés vs legacy**: aucun.

## Amorces par verticale

Chaque verticale dispose d'au moins un ticket avec sources legacy explicites.
La verticale `EDI` est sans objet depuis l'abandon du domaine « Éditions & exports ».

### [AUTH] Authentifier un utilisateur

- **Use-cases couverts**: UC-AUTH-1
- **Sources legacy (1 à 3)**:
  - `legacy/app/login/login.js`
  - `legacy/app/login/login.html`
  - `legacy/ws/phpWs/login.php`
- **Comportement historique attendu**: authentification par identifiant/mot de passe,
  ouverture de session applicative et chargement des droits.
- **Cible de refonte**: module `auth`.
- **Critères d'acceptation**:
  - [ ] Les identifiants invalides ne révèlent pas si le compte existe.
  - [ ] Les droits chargés conditionnent le menu affiché.

### [PAY] Payer une commande par carte bancaire

- **Use-cases couverts**: UC-PAY-3, UC-PAY-5
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/fseCmdRgltCb/fseCmdRgltCb.js`
  - `legacy/app/components/paiementcb/paiementcb.js`
  - `legacy/ws/notifHelloAsso.php`
- **Comportement historique attendu**: redirection vers le prestataire de paiement puis
  réconciliation de la commande à réception de la notification.
- **Cible de refonte**: module `paiement`.
- **Critères d'acceptation**:
  - [ ] Une notification rejouée ne double pas le règlement.
  - [ ] Un paiement échoué laisse la commande dans un état non réglé.

### [HIST] Consulter l'historique de ses commandes

- **Use-cases couverts**: UC-HISTO-1, UC-CMD-9
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/fseCmdHistoList/fseCmdHistoList.js`
  - `legacy/app/components/fseListCmdUti/fseListCmdUti.js`
  - `legacy/ws/phpWs/fseCmdManage.php`
- **Comportement historique attendu**: liste filtrable des commandes passées par
  l'utilisateur avec accès au détail.
- **Cible de refonte**: module `historique`.
- **Critères d'acceptation**:
  - [ ] Seules les commandes de l'utilisateur connecté sont listées.
  - [ ] Le tri et la pagination reproduisent le comportement historique.

### [CAT] Gérer la fiche article et son prix

- **Use-cases couverts**: UC-ART-2, UC-ART-3
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/fseArticleGest/fseArticleGest.js`
  - `legacy/app/components/fseArticlePrixGest/fseArticlePrixGest.js`
  - `legacy/ws/phpWs/fseArticleManage.php`
- **Comportement historique attendu**: création/modification d'un article et de sa
  tarification applicable.
- **Cible de refonte**: module `catalogue`.
- **Critères d'acceptation**:
  - [ ] Un prix sans période de validité valide est refusé.
  - [ ] La modification d'un article n'altère pas les commandes déjà passées.

### [STOCK] Suivre et ajuster le stock d'un article

- **Use-cases couverts**: UC-STK-1, UC-STK-2
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/fseStockList/fseStockList.js`
  - `legacy/app/components/fseStockGest/fseStockGest.js`
  - `legacy/ws/phpWs/fseStockManage.php`
- **Comportement historique attendu**: consultation des quantités et saisie
  d'ajustements de stock.
- **Cible de refonte**: module `stock`.
- **Critères d'acceptation**:
  - [ ] Un ajustement est historisé avec son auteur.
  - [ ] Le stock négatif est traité comme dans le legacy (autorisé ou bloqué, à confirmer).

### [ADMIN] Gérer les utilisateurs et leurs groupes

- **Use-cases couverts**: UC-ADM-1, UC-ADM-4
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/userGest/userGest.js`
  - `legacy/app/components/userGroupList/userGroupList.js`
  - `legacy/ws/phpWs/userManage.php`
- **Comportement historique attendu**: création, modification et rattachement des
  utilisateurs à des groupes porteurs de droits.
- **Cible de refonte**: module `administration`.
- **Critères d'acceptation**:
  - [ ] Le retrait d'un groupe retire immédiatement les droits associés.
  - [ ] Un compte désactivé ne peut plus se connecter.

### [COM] Envoyer un e-mail depuis un modèle

- **Use-cases couverts**: UC-MAIL-1
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/mailGest/mailGest.js`
  - `legacy/ws/phpWs/mailManage.php`
  - `legacy/ws/mail/template_01.html`
- **Comportement historique attendu**: composition d'un message à partir d'un gabarit et
  envoi à une liste de destinataires.
- **Cible de refonte**: module `communication`.
- **Critères d'acceptation**:
  - [ ] Les variables du gabarit sont substituées avant envoi.
  - [ ] Les destinataires désabonnés sont exclus de l'envoi.
