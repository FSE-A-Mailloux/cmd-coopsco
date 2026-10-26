# Backlog de production des spécifications et maquettes

> Le nom de fichier est conservé pour ne pas rompre les références des artefacts
> archivés. Le document ne pilote plus une migration de code, mais la production des
> spécifications et des maquettes.

## Objectif et horizons

La refonte se déroule en deux horizons. Ce document est actuellement centré sur le
premier ; le second n'est pas encore ouvert.

| Horizon | Objectif | Livrables |
| --- | --- | --- |
| **H1 — en cours** | Disposer d'une spécification à jour et de maquettes qui la représentent | Exigences et scénarios sous `openspec/specs/`, écrans sous `maquettes/` |
| **H2 — ultérieur** | Construire la nouvelle application | Code, en prenant H1 comme source de vérité |

À l'issue de H1, **la spécification et les maquettes deviennent la source de vérité**.
Le legacy cesse alors d'être une référence : il n'aura servi qu'à établir ce qui devait
être spécifié.

## Rôle des sources legacy

Le legacy reste cité dans chaque ticket, conformément à l'exigence
« Chaque tâche de refonte MUST être traçable vers ses sources legacy »
(`openspec/specs/legacy-refactor-context-curation/spec.md`). Mais son statut change selon
l'horizon :

- **En H1**, une source legacy est une **preuve comportementale** : elle atteste qu'une
  règle métier a existé, et sert de matériau pour rédiger l'exigence. Elle n'est pas un
  modèle à reproduire.
- **En H2**, elle redeviendra une référence de comparaison lors de la recette.

Chaque ticket suit `docs/legacy-migration-ticket-template.md` : 1 à 3 fichiers legacy
source, dans le périmètre défini par `docs/legacy-scope.md`, rattachés aux use-cases de
`docs/legacy-use-cases.md`.

Les décisions de reprise faisant autorité sont tenues dans
`docs/legacy-coverage-matrix.md`. Un ticket ne peut pas porter un use-case décidé comme
`ABANDONNÉ`, et un ticket portant un use-case `À CADRER` reste suspendu tant que le
cadrage n'est pas rendu.

## Vue de pilotage

Le travail restant en H1 n'est pas tenu à la main : il est **dérivé de la matrice de
couverture**. Un use-case décidé `REPRIS` mais dépourvu de spécification ou de maquette
est une descente incomplète, et c'est exactement ce qu'il reste à produire.

```powershell
pwsh -File scripts/check-legacy-coverage.ps1
```

La commande restitue deux listes qui font foi sur l'état d'avancement :

- **Descentes incomplètes** — les specs et maquettes à produire.
- **Cadrages en cours** — les use-cases dont le principe est acquis mais dont le
  périmètre doit être instruit avant toute rédaction.

Ce document ne recopie pas ces listes, qui se périmeraient. Il précise **comment** traiter
chaque cas, en distinguant deux natures de travail :

| Nature du trou | Traitement | Où |
| --- | --- | --- |
| Périmètre à instruire ou décision structurante à prendre | Changement OpenSpec dédié | `openspec/changes/` |
| Production directe, décision déjà rendue au PRD | Ticket H1 | ce document, section « Lots H1 » |
| Correction d'un artefact existant devenu non conforme à une décision | Changement OpenSpec dédié | `openspec/changes/` |

La troisième nature n'apparaît pas dans les listes du contrôle, puisqu'elle ne concerne
pas un manque mais un **excès ou une non-conformité** : un artefact présent qui contredit
une décision rendue. Deux cas actuellement :

- `UC-ART-4` — le référentiel fournisseurs est abandonné, mais la notion subsiste dans le
  PRD et les spécifications ; le travail consiste à l'en retirer.
- `UC-CMD-5` — la spécification `frontend-reference-screen-flows` refuse l'annulation
  après paiement, règle invalidée par l'arbitrage 3 ; le travail consiste à la corriger.

### Trous portés par un changement OpenSpec

| Changement | Use-cases |
| --- | --- |
| `reprendre-administration-habilitations` | UC-ADM-2 uniquement ; groupes abandonnés, affectation de rôles déjà cadrée |
| `consulter-journal-actions-et-anomalies` | UC-HISTO-2, UC-HISTO-3 |
| `renvoyer-code-de-validation` | UC-AUTH-7 |
| `annuler-commande-reglee-avec-avoir` | UC-CMD-5, UC-PAY-2 |
| `cadrer-desactivation-de-compte` | UC-AUTH-8 |
| `retirer-referentiel-fournisseur` | UC-ART-4 |

`UC-NAV-3` ne relève pas d'un changement : son cadrage consiste à préciser les accueils
contextuels dans le PRD.

### Attendus d'un changement OpenSpec en H1

Le schéma `spec-driven` produit des spécifications **par construction**, mais jamais de
maquettes. Un changement qui ne produirait que des exigences laisserait donc intacte la
descente incomplète qu'il est censé combler. Tout changement H1 doit par conséquent livrer
les quatre éléments suivants, sur le modèle des changements déjà archivés :

1. **La ou les maquettes** — écran créé ou mis à jour sous `maquettes/screens/`, déclaré
   dans `maquettes/manifest/screens.json`.
2. **Le delta de spécification métier** — la capacité concernée sous `openspec/specs/`.
3. **Le delta `frontend-reference-screen-flows`** — les règles portées par l'écran, dès
   lors qu'une maquette est produite ou modifiée.
4. **La mise à jour de la matrice** — rattachements renseignés, puis exécution du contrôle
   de couverture pour vérifier que la descente incomplète a disparu.

Chaque changement H1 cite ses use-cases dans son objectif, afin que le lien avec la
matrice soit lisible dans les deux sens.

## Verticales

| Code | Verticale | Portée |
| --- | --- | --- |
| AUTH | Authentification & compte | Connexion, inscription, mots de passe, désabonnement |
| CMD | Commande | Initialisation, saisie d'articles, détail, confirmation |
| PAY | Paiement | Règlement, paiement CB, notifications prestataire |
| HIST | Historique & suivi | Commandes en cours, historique utilisateur, journal d'actions |
| CAT | Catalogue & articles | Articles, prix, fournisseurs, années FSE |
| STOCK | Stock & consolidation | Stocks, consolidation, outils de gestion |
| ADMIN | Administration & sécurité | Utilisateurs, affectation de rôles prédéfinis et contrôle d'accès ; groupes, fonctions et permissions non administrables |
| COM | Communication | E-mails, modèles de mail, listes de diffusion |

La verticale `EDI` (éditions & exports) a été retirée : le domaine legacy correspondant est
abandonné, ses use-cases décrivant un moteur de reporting configurable et non un besoin
métier (cf. `docs/legacy-coverage-matrix.md`, arbitrage 1).

## Lots H1 — spécifications et maquettes à produire

Use-cases décidés `REPRIS` au PRD, sans arbitrage restant, dont la spécification ou la
maquette manque. Ils se traitent directement, sans passer par un changement dédié.

### [AUTH] Permettre à un utilisateur de changer son mot de passe

- **Use-cases couverts**: UC-AUTH-5
- **Sources legacy (entrée, 1 à 3)**:
  - `legacy/app/components/changeMyPass/changeMyPass.js`
  - `legacy/ws/phpWs/userManage.php`
- **Comportement historique observé**: l'utilisateur connecté modifie son mot de passe
  depuis son profil.
- **Livrables**:
  - Spec: `user-account-lifecycle-management` — couverte par `FR-034`, à compléter si le
    parcours écran révèle des règles absentes.
  - Maquette: écran de profil utilisateur, inexistant à ce jour.
- **Critères d'acceptation (phase spec & maquettes)**:
  - [ ] L'écran figure au manifeste `maquettes/manifest/screens.json`.
  - [ ] Les règles de validation du nouveau mot de passe sont couvertes par un scénario.
  - [ ] La matrice ne signale plus de descente incomplète pour `UC-AUTH-5`.
- **Écarts assumés vs legacy**: aucun.

### [CMD] Rattacher une commande à un compte utilisateur

- **Use-cases couverts**: UC-CMD-6
- **Sources legacy (entrée, 1 à 3)**:
  - `legacy/app/components/fseListCmdEnCours/fseListCmdEnCours.js`
  - `legacy/ws/phpWs/fseCmdManage.php`
- **Comportement historique observé**: le gestionnaire associe une commande anonyme à un
  compte utilisateur existant.
- **Livrables**:
  - Spec: `fse-order-and-payment-workflow` — exigence à rédiger à partir de `FR-055`.
  - Maquette: action de rattachement depuis `commandes-liste` ou `commandes-detail`.
- **Critères d'acceptation (phase spec & maquettes)**:
  - [ ] Les règles de correspondance autorisées sont explicitées par des scénarios.
  - [ ] Le cas d'une commande déjà rattachée est couvert.
  - [ ] La matrice ne signale plus de descente incomplète pour `UC-CMD-6`.
- **Écarts assumés vs legacy**: aucun.

### [CMD] Relancer une commande non réglée

- **Use-cases couverts**: UC-CMD-7
- **Sources legacy (entrée, 1 à 3)**:
  - `legacy/app/components/fseListCmdEnCours/fseListCmdEnCours.js`
  - `legacy/ws/phpWs/fseCmdManage.php`
  - `legacy/ws/phpClasses/appMail.php`
- **Comportement historique observé**: le gestionnaire envoie une relance par courriel au
  commanditaire dont la commande n'est pas réglée.
- **Livrables**:
  - Spec: exigence à rédiger à partir de `FR-078` et `FR-079`, incluant l'interdiction de
    relancer une commande déjà validée et la trace de l'envoi.
  - Maquette: action de relance depuis `commandes-liste`.
- **Critères d'acceptation (phase spec & maquettes)**:
  - [ ] Un scénario couvre le refus de relance sur commande déjà validée.
  - [ ] Un scénario couvre la traçabilité de l'envoi dans l'historique des actions.
  - [ ] La matrice ne signale plus de descente incomplète pour `UC-CMD-7`.
- **Écarts assumés vs legacy**: aucun.

### [PAY] Restituer le retour d'un paiement par carte

- **Use-cases couverts**: UC-PAY-4
- **Sources legacy (entrée, 1 à 3)**:
  - `legacy/app/components/paiementcb/paiementcb.js`
  - `legacy/app/components/paiementcb/paiementcb.html`
  - `legacy/ws/phpWs/fseCmdManage.php`
- **Comportement historique observé**: l'application traite le retour du prestataire et
  affiche le résultat (succès, échec, abandon).
- **Livrables**:
  - Spec: `fse-order-and-payment-workflow` — couverte par `FR-061` et `FR-064`, y compris
    le statut « à vérifier » et sa reprise manuelle.
  - Maquette: écran de retour de paiement, inexistant à ce jour.
- **Critères d'acceptation (phase spec & maquettes)**:
  - [ ] L'écran couvre les trois issues et le statut « à vérifier ».
  - [ ] La reprise manuelle après timeout est représentée.
  - [ ] La matrice ne signale plus de descente incomplète pour `UC-PAY-4`.
- **Écarts assumés vs legacy**: le statut « à vérifier » est un ajout, arbitré au PRD
  §14.1 point 5.

### [PAY] Retirer la cotisation d'une commande

- **Use-cases couverts**: UC-PAY-6
- **Sources legacy (entrée, 1 à 3)**:
  - `legacy/app/components/fseListCmdEnCours/fseListCmdEnCours.js`
  - `legacy/ws/phpWs/fseCmdManage.php`
- **Comportement historique observé**: le gestionnaire retire une ligne de cotisation
  d'une commande.
- **Livrables**:
  - Spec: `fse-order-and-payment-workflow` — couverte par `COTIS-01` à `COTIS-05`.
  - Maquette: action de retrait depuis `commandes-detail`, avec confirmation explicite.
- **Critères d'acceptation (phase spec & maquettes)**:
  - [ ] L'action est absente lorsque la commande est validée et facturée.
  - [ ] La confirmation préalable est représentée.
  - [ ] La matrice ne signale plus de descente incomplète pour `UC-PAY-6`.
- **Écarts assumés vs legacy**: retrait interdit dès validation et facturation, arbitré au
  PRD §14.1 point 9.

### [COM] Composer et envoyer un courriel depuis un gabarit

- **Use-cases couverts**: UC-MAIL-1
- **Sources legacy (entrée, 1 à 3)**:
  - `legacy/app/components/mailGest/mailGest.js`
  - `legacy/ws/phpWs/mailManage.php`
  - `legacy/ws/phpClasses/appMail.php`
- **Comportement historique observé**: le gestionnaire rédige un message, choisit un
  gabarit, prévisualise le rendu fusionné puis envoie à un utilisateur, un groupe ou une
  adresse libre.
- **Livrables**:
  - Spec: `templated-mail-and-document-generation` — couverte par `FR-075` à `FR-077`.
  - Maquette: écran de composition et de prévisualisation, inexistant à ce jour.
- **Critères d'acceptation (phase spec & maquettes)**:
  - [ ] Les trois modes de ciblage sont représentés.
  - [ ] La prévisualisation du rendu fusionné précède l'envoi.
  - [ ] L'envoi en destinataire masqué est représenté pour les diffusions.
  - [ ] La matrice ne signale plus de descente incomplète pour `UC-MAIL-1`.
- **Écarts assumés vs legacy**: exclusion des destinataires désinscrits et plafond annuel,
  arbitrés au PRD §14.1 point 4.

## Lots différés en H2

Ces tickets portent des use-cases **déjà couverts par la spécification et les maquettes**.
Ils n'ont plus d'objet en H1 et sont conservés pour la phase de construction, où leurs
critères d'acceptation redeviendront des critères de recette comportementale.

Ils restent rédigés au format H2 du template : le champ « Cible de refonte » et des
critères formulés en « comportement reproduit » y sont donc attendus.
La verticale `EDI` est sans objet depuis l'abandon du domaine « Éditions & exports ».

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

### [ADMIN] Gérer les utilisateurs et leur affecter un rôle prédéfini

> Ticket H2. La reprise adaptée de `UC-ADM-4` est déjà cadrée par
> `cadrer-changement-role-utilisateur`, la capacité `user-role-administration-and-audit`
> et la maquette `admin-utilisateurs`. Les permissions des rôles Famille, Gestionnaire
> et Admin sont fixes ; le changement `reprendre-administration-habilitations` ne porte
> plus que le changement forcé de mot de passe (`UC-ADM-2`).

- **Use-cases couverts**: UC-ADM-1, UC-ADM-4
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/userGest/userGest.js`
  - `legacy/app/components/userGroupList/userGroupList.js`
  - `legacy/ws/phpWs/userManage.php`
- **Comportement historique attendu**: création, modification et rattachement des
  utilisateurs à des groupes porteurs de droits.
- **Adaptation cible**: remplacer l'affectation à des groupes administrables par
  l'affectation d'un rôle existant par un admin, sans modifier les permissions du rôle.
- **Cible de refonte**: module `administration`.
- **Critères d'acceptation**:
  - [ ] Seul un admin peut changer le rôle d'un utilisateur parmi Famille, Gestionnaire et Admin.
  - [ ] Le changement conserve les permissions prédéfinies et produit une trace complète consultable par les admins.
  - [ ] Un compte désactivé ne peut plus se connecter.

### [COM] Envoyer un e-mail depuis un modèle

> Ticket H2. Le travail H1 correspondant est porté par le lot
> « [COM] Composer et envoyer un courriel depuis un gabarit », qui produit la maquette
> manquante. Ce ticket ne sert qu'à la recette comportementale ultérieure.

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
