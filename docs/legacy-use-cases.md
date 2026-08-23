# Cartographie des use-cases fonctionnels legacy

Regroupement par domaine métier des comportements réellement implémentés dans
l'application legacy. Chaque use-case cite ses fichiers source legacy, tous situés dans
le périmètre défini par `docs/legacy-scope.md`.

Convention d'identifiant : `UC-<DOMAINE>-<n>`. Ces identifiants sont référencés par les
tickets de refonte (`docs/legacy-migration-backlog.md`).

## Authentification & compte utilisateur

### UC-AUTH-1 — Connexion par login / mot de passe
- **Description**: L'utilisateur saisit son identifiant et son mot de passe (haché SHA-256) ; le système vérifie les informations, génère un token de session et retourne le groupe/commune associé.
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/app/login/login.js`, `legacy/app/login/login.html`, `legacy/ws/phpWs/login.php`

### UC-AUTH-2 — Activation de compte par lien e-mail
- **Description**: L'utilisateur clique sur un lien d'activation reçu par e-mail ; le système vérifie le code de validation, valide le compte et ouvre une session.
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/app/app.js`, `legacy/ws/phpWs/login.php`, `legacy/ws/phpWs/userValid.php`

### UC-AUTH-3 — Validation de compte par code saisi
- **Description**: L'utilisateur saisit manuellement le code reçu par e-mail pour activer son compte ; le système contrôle la correspondance code/identifiant.
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/app/components/validUser/validUser.js`, `legacy/app/components/validUser/validUser.html`, `legacy/ws/phpWs/userValid.php`

### UC-AUTH-4 — Récupération de mot de passe oublié
- **Description**: L'utilisateur demande un e-mail de réinitialisation ; le système envoie un lien sécurisé permettant de saisir un nouveau mot de passe.
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/app/components/recupPass/recupPass.js`, `legacy/ws/phpWs/userManage.php`

### UC-AUTH-5 — Changement de son propre mot de passe
- **Description**: L'utilisateur connecté modifie son mot de passe depuis son profil.
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/app/components/changeMyPass/changeMyPass.js`, `legacy/ws/phpWs/userManage.php`

### UC-AUTH-6 — Changement de mot de passe via lien e-mail
- **Description**: L'utilisateur réinitialise son mot de passe en suivant le lien reçu ; le système valide le nouveau mot de passe avant enregistrement.
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/app/components/changePass/changePass.js`, `legacy/ws/phpWs/userManage.php`

### UC-AUTH-7 — Renvoi du code de validation
- **Description**: L'utilisateur demande un nouveau code d'activation si l'e-mail initial n'est pas parvenu.
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/ws/phpWs/resendCode.php`, `legacy/ws/mail/newValidationCode.html`, `legacy/ws/phpWs/userManage.php`

### UC-AUTH-8 — Désinscription (désabonnement)
- **Description**: L'utilisateur clique sur le lien de désabonnement depuis un e-mail ; le système désactive son compte.
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/app/components/desabo/desabo.js`, `legacy/ws/phpWs/userManage.php`

## Administration & sécurité

### UC-ADM-1 — Gestion des utilisateurs
- **Description**: L'administrateur crée, modifie ou supprime des comptes ; un e-mail d'activation est envoyé à la création.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/userGest/userGest.js`, `legacy/app/components/userList/userList.js`, `legacy/ws/phpWs/userManage.php`, `legacy/ws/phpWs/getUser.php`

### UC-ADM-2 — Forcer le changement de mot de passe d'un utilisateur
- **Description**: L'administrateur impose un changement de mot de passe à la prochaine connexion de l'utilisateur.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/userForceMdp/userForceMdp.js`, `legacy/ws/phpWs/userManage.php`

### UC-ADM-3 — Gestion des groupes
- **Description**: L'administrateur crée, modifie ou supprime des groupes (coopératives/communes), chacun lié à un code commune.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/groupGest/groupGest.js`, `legacy/app/components/groupList/groupList.js`, `legacy/ws/phpWs/groupManage.php`

### UC-ADM-4 — Affectation d'utilisateurs à des groupes
- **Description**: L'administrateur associe ou retire des utilisateurs d'un groupe.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/userGroupList/userGroupList.js`, `legacy/ws/phpWs/userGroupManage.php`

### UC-ADM-5 — Gestion des fonctions applicatives
- **Description**: L'administrateur crée, modifie ou supprime des fonctions (rôles) structurant les habilitations.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/functionGest/functionGest.js`, `legacy/app/components/functionList/functionList.js`, `legacy/ws/phpWs/functionManage.php`

### UC-ADM-6 — Gestion des autorisations par groupe
- **Description**: L'administrateur attribue ou révoque les autorisations d'accès (composants, états) pour un groupe donné.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/autorisationList/autorisationList.js`, `legacy/app/components/autorisationList/autorisationList.html`, `legacy/ws/phpWs/autorisationManage.php`

### UC-ADM-7 — Gestion du menu de navigation
- **Description**: L'administrateur configure les entrées de menu (ordre, libellé, composant cible) et leur rattachement aux groupes/modules.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/menuGest/menuGest.js`, `legacy/app/components/menuCompList/menuCompList.js`, `legacy/ws/phpWs/menuManage.php`

### UC-ADM-8 — Gestion des modules
- **Description**: L'administrateur administre les modules applicatifs et leurs paramètres.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/moduleList/moduleList.js`, `legacy/app/components/moduleParam/moduleParam.js`, `legacy/ws/phpWs/moduleManage.php`

### UC-ADM-9 — Gestion des composants applicatifs
- **Description**: L'administrateur référence les composants disponibles (code, libellé, fichier) et les rend sélectionnables par les menus et autorisations.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/compList/compList.js`, `legacy/app/components/compGest/compGest.js`, `legacy/ws/phpWs/compManage.php`

## Catalogue & articles

### UC-ART-1 — Consultation de la liste des articles
- **Description**: Le gestionnaire consulte le catalogue FSE avec filtrage et tri.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseArticleList/fseArticleList.js`, `legacy/ws/phpWs/fseArticleManage.php`

### UC-ART-2 — Création / modification / suppression d'un article
- **Description**: Le gestionnaire gère la fiche article (libellé, marque, type, code, fournisseur, options stock/conso/famille) ; la suppression purge prix et stocks associés.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseArticleGest/fseArticleGest.js`, `legacy/ws/phpWs/fseArticleManage.php`

### UC-ART-3 — Gestion des prix d'article par année et famille
- **Description**: Le gestionnaire fixe ou modifie les prix de vente d'un article pour une année et une famille de coopérative données.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseArticlePrixGest/fseArticlePrixGest.js`, `legacy/ws/phpWs/fseArticlePrixManage.php`

### UC-ART-4 — Gestion des fournisseurs
- **Description**: Le gestionnaire crée, modifie ou supprime un fournisseur référencé dans le catalogue.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseFournisseurGest/fseFournisseurGest.js`, `legacy/app/components/fseFournisseurList/fseFournisseurList.js`, `legacy/ws/phpWs/fseFournisseurManage.php`

### UC-ART-5 — Gestion des années de campagne
- **Description**: L'administrateur ouvre, clôture ou supprime une période annuelle de commande ; une seule période peut être « en cours ».
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/fseAnneeGest/fseAnneeGest.js`, `legacy/app/components/fseAnneeList/fseAnneeList.js`, `legacy/ws/phpWs/fseAnneeManage.php`

## Commande FSE

### UC-CMD-1 — Initialisation d'une commande
- **Description**: L'utilisateur ou le gestionnaire saisit les informations du commanditaire (nom, prénom, e-mail, téléphone, enfants/classe) et démarre une commande sur la période en cours.
- **Acteur**: utilisateur / gestionnaire
- **Sources legacy**: `legacy/app/components/fseCmdInit/fseCmdInit.js`, `legacy/app/components/fseCmdInit/fseCmdInit.html`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-CMD-2 — Sélection et quantification des articles
- **Description**: L'utilisateur ajoute, modifie les quantités ou supprime des articles du panier avant confirmation.
- **Acteur**: utilisateur / gestionnaire
- **Sources legacy**: `legacy/app/components/fseCmdArticle/fseCmdArticle.js`, `legacy/app/components/fseCmdArticle/fseCmdArticle.html`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-CMD-3 — Confirmation et enregistrement de la commande
- **Description**: L'utilisateur valide le récapitulatif ; le système enregistre la commande.
- **Acteur**: utilisateur / gestionnaire
- **Sources legacy**: `legacy/app/components/fseCmdConfirm/fseCmdConfirm.js`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-CMD-4 — Consultation du détail d'une commande
- **Description**: Le gestionnaire ou l'utilisateur consulte le détail complet d'une commande : articles, montants, règlements.
- **Acteur**: gestionnaire / utilisateur
- **Sources legacy**: `legacy/app/components/fseCmdDetail/fseCmdDetail.js`, `legacy/app/components/fseCmdDetail/fseCmdDetail.html`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-CMD-5 — Annulation d'une commande
- **Description**: Le gestionnaire annule une commande existante ; l'action est historisée.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseListCmdEnCours/fseListCmdEnCours.js`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-CMD-6 — Liaison d'une commande à un utilisateur existant
- **Description**: Le gestionnaire associe une commande anonyme à un compte utilisateur.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseListCmdEnCours/fseListCmdEnCours.js`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-CMD-7 — Relance d'une commande non réglée
- **Description**: Le gestionnaire envoie une relance e-mail au commanditaire dont la commande n'est pas réglée.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseListCmdEnCours/fseListCmdEnCours.js`, `legacy/ws/phpWs/fseCmdManage.php`, `legacy/ws/phpClasses/appMail.php`

### UC-CMD-8 — Liste des commandes en cours (vue gestionnaire)
- **Description**: Le gestionnaire consulte toutes les commandes de la campagne en cours, avec accès au détail, au règlement, au PDF et à la facturation.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseListCmdEnCours/fseListCmdEnCours.js`, `legacy/app/components/fseListCmdEnCours/fseListCmdEnCours.html`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-CMD-9 — Liste des commandes par utilisateur (vue famille)
- **Description**: L'utilisateur consulte ses propres commandes passées et en cours.
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/app/components/fseListCmdUti/fseListCmdUti.js`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-CMD-10 — Génération du PDF de commande ou de facture
- **Description**: Le gestionnaire ou l'utilisateur génère un PDF récapitulatif ou une facture, fusionné depuis un modèle HTML.
- **Acteur**: gestionnaire / utilisateur
- **Sources legacy**: `legacy/app/components/fseCmdDetail/fseCmdDetail.js`, `legacy/ws/phpWs/fseCmdManage.php`, `legacy/ws/phpClasses/class.FusionJsonHtml.php`

## Paiement

### UC-PAY-1 — Enregistrement d'un règlement
- **Description**: Le gestionnaire saisit un ou plusieurs règlements sur une commande (type, montant, banque, date) et valide la commande.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseCmdRegler/fseCmdRegler.js`, `legacy/app/components/fseCmdRegler/fseCmdRegler.html`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-PAY-2 — Annulation d'un règlement
- **Description**: Le gestionnaire annule un règlement enregistré sur une commande.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseCmdRegler/fseCmdRegler.js`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-PAY-3 — Initialisation d'un paiement par carte bancaire
- **Description**: Le système lance un paiement CB via le prestataire ; les URLs de retour (succès, échec, abandon) sont construites dynamiquement.
- **Acteur**: utilisateur / système
- **Sources legacy**: `legacy/app/components/fseCmdRgltCb/fseCmdRgltCb.js`, `legacy/app/components/paiementcb/paiementcb.js`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-PAY-4 — Traitement du retour de paiement CB
- **Description**: L'application traite le retour du prestataire après paiement et affiche le résultat (succès, échec, abandon).
- **Acteur**: système / utilisateur
- **Sources legacy**: `legacy/app/components/paiementcb/paiementcb.js`, `legacy/app/components/paiementcb/paiementcb.html`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-PAY-5 — Notification serveur de confirmation de paiement
- **Description**: Le backend reçoit la notification serveur-à-serveur du prestataire confirmant un paiement CB et met à jour l'état de la commande.
- **Acteur**: système
- **Sources legacy**: `legacy/ws/notifHelloAsso.php`, `legacy/ws/phpClasses/class.DataBase.php`

### UC-PAY-6 — Suppression d'une cotisation sur une commande
- **Description**: Le gestionnaire retire une ligne de cotisation d'une commande.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseListCmdEnCours/fseListCmdEnCours.js`, `legacy/ws/phpWs/fseCmdManage.php`

## Historique & suivi

### UC-HISTO-1 — Consultation de l'historique des commandes d'une campagne
- **Description**: Le gestionnaire consulte l'historique des commandes d'une campagne (dates, états, montants).
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseCmdHistoList/fseCmdHistoList.js`, `legacy/app/components/fseCmdHistoList/fseCmdHistoList.html`, `legacy/ws/phpWs/fseCmdManage.php`

### UC-HISTO-2 — Consultation du journal des actions applicatives
- **Description**: L'administrateur consulte le journal d'audit des actions effectuées sur les données (action, contexte, table, date).
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/actionHistoList/actionHistoList.js`, `legacy/ws/phpWs/init.php`

### UC-HISTO-3 — Consultation des anomalies applicatives
- **Description**: L'administrateur consulte la liste des anomalies enregistrées (erreurs, traitements en défaut) avec action et contexte associés.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/anomalieList/anomalieList.js`, `legacy/app/components/anomalieList/anomalieList.html`

## Stock

### UC-STK-1 — Consultation de la liste des stocks
- **Description**: Le gestionnaire consulte le stock disponible par article pour la campagne en cours.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseStockList/fseStockList.js`, `legacy/ws/phpWs/fseStockManage.php`

### UC-STK-2 — Mise à jour du stock d'un article
- **Description**: Le gestionnaire modifie la quantité en stock d'un article ; la ligne de stock est créée si elle n'existe pas encore.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseStockGest/fseStockGest.js`, `legacy/ws/phpWs/fseStockManage.php`

## Consolidation & outils

### UC-CON-1 — Génération du PDF de consolidation
- **Description**: Le gestionnaire génère un document de synthèse de la campagne en cours, fusionné depuis un modèle HTML dédié.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseConsolidation/fseConsolidation.js`, `legacy/ws/phpWs/fseConsolidationManage.php`, `legacy/ws/phpClasses/class.FusionJsonHtml.php`

### UC-CON-2 — Clôture de la période de commande
- **Description**: Le gestionnaire ferme la période de commande en cours depuis la vue consolidation.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/fseConsolidation/fseConsolidation.js`, `legacy/ws/phpWs/fseAnneeManage.php`

### UC-CON-3 — Génération en lot des PDF de commandes réglées par CB
- **Description**: Le système récupère les commandes réglées par carte, les regroupe par lots et génère les PDF correspondants.
- **Acteur**: gestionnaire / système
- **Sources legacy**: `legacy/app/components/fseOutils/fseOutils.js`, `legacy/ws/phpWs/fseOutilsManage.php`, `legacy/ws/phpWs/fseCmdManage.php`

## Éditions & exports

### UC-EDT-1 — Paramétrage des listes d'impression
- **Description**: L'administrateur crée, modifie ou supprime des configurations de liste (colonnes, filtres, ordre) ; les lignes d'association sont synchronisées.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/impressionListeParamGest/impressionListeParamGest.js`, `legacy/app/components/impressionListeParamList/impressionListeParamList.js`, `legacy/ws/phpWs/impressionListeManage.php`

### UC-EDT-2 — Export d'une liste configurable
- **Description**: Le gestionnaire sélectionne un modèle de liste, choisit colonnes et filtres, puis génère l'export.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/impressionListeExport/impressionListeExport.js`, `legacy/app/components/impressionListeExport/impressionListeExport.html`, `legacy/ws/phpWs/impressionListeManage.php`

### UC-EDT-3 — Gestion des états de reporting
- **Description**: L'administrateur gère les états disponibles et leurs paramètres (code, type de données, modèle HTML ou requête SQL).
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/etatList/etatList.js`, `legacy/app/components/etatparamList/etatparamList.js`, `legacy/app/components/etatparamGest/etatparamGest.js`, `legacy/ws/phpWs/etatparamManage.php`

### UC-EDT-4 — Génération d'un état de reporting
- **Description**: L'utilisateur habilité sélectionne un état, renseigne les paramètres et déclenche la génération du PDF par fusion HTML.
- **Acteur**: gestionnaire / utilisateur
- **Sources legacy**: `legacy/app/components/etatList/etatList.js`, `legacy/ws/phpWs/etatManage.php`, `legacy/ws/phpClasses/class.FusionJsonHtml.php`

### UC-EDT-5 — Gestion des modèles HTML
- **Description**: L'administrateur gère les templates HTML utilisés pour la génération de PDF et d'e-mails (fusion de données).
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/modeleHtmlGest/modeleHtmlGest.js`, `legacy/app/components/modeleHtmlList/modeleHtmlList.js`, `legacy/ws/phpWs/modeleHtmlManage.php`

### UC-EDT-6 — Gestion des requêtes SQL paramétrables
- **Description**: L'administrateur gère des requêtes SQL nommées, exécutables à la demande pour alimenter états et exports.
- **Acteur**: administrateur
- **Sources legacy**: `legacy/app/components/requestGest/requestGest.js`, `legacy/app/components/requestList/requestList.js`, `legacy/ws/phpWs/requestManage.php`, `legacy/ws/phpWs/execRequest.php`

## Communication / e-mail

### UC-MAIL-1 — Prévisualisation et envoi d'un e-mail
- **Description**: Le gestionnaire rédige un e-mail, sélectionne un template HTML, prévisualise le rendu fusionné, puis envoie à un utilisateur, un groupe ou une adresse libre.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/mailGest/mailGest.js`, `legacy/ws/phpWs/mailManage.php`, `legacy/ws/phpClasses/appMail.php`

### UC-MAIL-2 — Gestion de la liste de diffusion d'un groupe
- **Description**: Le gestionnaire consulte et gère les adresses e-mail des membres d'un groupe pour les envois groupés.
- **Acteur**: gestionnaire
- **Sources legacy**: `legacy/app/components/groupListMail/groupListMail.js`, `legacy/ws/phpWs/groupManage.php`

## Navigation & accueil

### UC-NAV-1 — Chargement dynamique d'un composant métier
- **Description**: L'application charge à la demande le JS et le HTML d'un composant, après contrôle d'autorisation côté serveur.
- **Acteur**: système
- **Sources legacy**: `legacy/app/app.js`, `legacy/app/services.js`, `legacy/ws/phpWs/getComponent.php`

### UC-NAV-2 — Affichage du menu selon les droits
- **Description**: Le menu affiché est construit à partir des autorisations du groupe de l'utilisateur connecté.
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/app/menu/menu.js`, `legacy/app/menu/menu.html`, `legacy/ws/phpWs/menuManage.php`

### UC-NAV-3 — Pages d'accueil contextuelles
- **Description**: L'application présente un écran d'accueil différent selon le profil (public, administrateur, gestionnaire FSE, famille).
- **Acteur**: utilisateur
- **Sources legacy**: `legacy/app/components/default/default.js`, `legacy/app/components/defaultAdmin/defaultAdmin.js`, `legacy/app/components/defaultFse/defaultFse.js`, `legacy/app/components/defaultFseFamille/defaultFseFamille.js`

## Couverture

Domaines couverts : authentification/compte, administration & sécurité, catalogue,
commande, paiement, historique & suivi, stock, consolidation & outils, éditions &
exports, communication, navigation & accueil.

Zones legacy volontairement non cartographiées (hors corpus, cf. `docs/legacy-scope.md`) :
`legacy/ws/vendor/**`, `legacy/libs/**`, `legacy/imgs/**`, `legacy/style/**`.

Contrôle de couverture (revue d'équipe) : chaque dossier de
`legacy/app/components/**` et chaque endpoint de `legacy/ws/phpWs/*.php` est référencé
par au moins un use-case, à l'exception des éléments listés ci-dessous.

Éléments exclus de la cartographie et motif :

- `legacy/app/components/user_old/**` — composant obsolète, remplacé par
  `legacy/app/components/user/**`.
- `legacy/app/components/autorisationGest/` — dossier vide, sans code.
- `legacy/ws/phpWs/init.php`, `legacy/ws/phpWs/index.html` — amorçage technique et
  fichier de garde, sans comportement métier propre (`init.php` est toutefois cité
  par UC-HISTO-2 pour le journal d'audit).

Commande de vérification de la couverture :

```powershell
$doc = Get-Content docs/legacy-use-cases.md -Raw
Get-ChildItem legacy/app/components -Directory |
  Where-Object { $doc -notmatch [regex]::Escape("components/$($_.Name)/") } |
  Select-Object -ExpandProperty Name
```
