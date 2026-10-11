# Matrice de couverture des use-cases legacy

Source de vérité des décisions de reprise des use-cases fonctionnels legacy.

Entrée du contrôle : `docs/legacy-use-cases.md` (57 use-cases, identifiants `UC-<DOMAINE>-<n>`).
Ce document ne modifie pas la cartographie legacy ; il en dérive.

## Convention

### Valeurs de décision autorisées

Ensemble fermé. Une cellule `Décision` vide est une anomalie, jamais un abandon implicite.

| Décision | Signification |
| --- | --- |
| `REPRIS` | Le comportement est retenu dans la solution cible. Au moins un rattachement est obligatoire. |
| `ABANDONNÉ` | Le comportement n'est pas retenu. Une justification métier en colonne `Écart / justification` est obligatoire. |
| `À CADRER` | Décision de principe rendue — le comportement n'est pas abandonné — mais son périmètre exact reste à déterminer par un travail de cadrage. La nature du cadrage attendu est obligatoire. |
| `À TRANCHER` | L'arbitrage n'est pas rendu. État transitoire, jamais un état final. |

`À CADRER` et `À TRANCHER` ne sont pas équivalents : le premier signifie « on sait qu'on le garde, on ne sait pas encore sous quelle forme », le second « on ne sait pas encore si on le garde ». Les confondre effacerait une décision prise ou en affirmerait une qui ne l'est pas.

### Colonnes de rattachement

Elles décrivent la **descente** du use-case dans les artefacts, distincte de la **décision**.

| Colonne | Contenu attendu | Valeurs spéciales |
| --- | --- | --- |
| `PRD` | Un ou plusieurs identifiants d'exigence de `openspec/prd-rewrite-agnostique.md` (`FR-nnn`, `FACT-nn`, `COTIS-nn`), séparés par des virgules | `—` si aucun |
| `Spec` | Un ou plusieurs noms de capacité présents sous `openspec/specs/<nom>/spec.md` | `—` si aucune |
| `Maquette` | Un ou plusieurs `id` d'écran déclarés dans `maquettes/manifest/screens.json` | `—` si aucune, `N/A` si le use-case est un comportement système sans interface dédiée |

`N/A` et `—` ne sont pas équivalents : `N/A` signifie « aucune maquette n'est attendue », `—` signifie « une maquette est attendue mais absente ».

### Lecture des états

- `REPRIS` avec `PRD`, `Spec` et `Maquette` renseignés : couverture descendue, aucun signal.
- `REPRIS` avec un niveau à `—` : **descente incomplète**. La décision est prise, la spécification ou la maquette reste à produire. Signalé en avertissement, pas en anomalie bloquante.
- `REPRIS` sans aucun rattachement : **anomalie bloquante**.
- `ABANDONNÉ` sans justification : **anomalie bloquante**.
- `À CADRER` sans description du cadrage attendu : **anomalie bloquante**. Restitué en avertissement sinon.
- `À TRANCHER` : point de décision ouvert, restitué au jalon.

### Limite assumée du contrôle

Le contrôle automatisé est **structurel**, jamais sémantique. Il vérifie que la matrice ne ment pas sur le dépôt : exhaustivité, validité des décisions, présence de rattachements, existence des artefacts référencés. Il ne vérifie jamais qu'un artefact référencé couvre réellement le comportement legacy. Cette garantie-là relève de la revue humaine, dont le contrôle ne dispense pas.

## Matrice

### Authentification & compte utilisateur

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-AUTH-1 | Connexion par login / mot de passe | REPRIS | FR-010, FR-011 | authentication-and-session-token-lifecycle | entree-connexion | |
| UC-AUTH-2 | Activation de compte par lien e-mail | REPRIS | FR-012 | authentication-and-session-token-lifecycle | entree-activation | |
| UC-AUTH-3 | Validation de compte par code saisi | REPRIS | FR-012 | authentication-and-session-token-lifecycle, user-account-lifecycle-management | entree-activation | |
| UC-AUTH-4 | Récupération de mot de passe oublié | REPRIS | FR-033 | user-account-lifecycle-management | entree-recuperation-acces | |
| UC-AUTH-5 | Changement de son propre mot de passe | REPRIS | FR-034 | user-account-lifecycle-management | — | Descente incomplète : aucun écran de profil utilisateur n'existe dans les maquettes. |
| UC-AUTH-6 | Changement de mot de passe via lien e-mail | REPRIS | FR-033 | user-account-lifecycle-management | entree-recuperation-acces | |
| UC-AUTH-7 | Renvoi du code de validation | REPRIS | FR-015 | — | — | Repris (arbitrage 6). `FR-015` créé. Descente incomplète : spécification et maquette à produire, change `renvoyer-code-de-validation`. |
| UC-AUTH-8 | Désinscription aux communications de diffusion | REPRIS | FR-037, FR-074 | templated-mail-and-document-generation | compte-suppression | Le traitement legacy `desabo` désactive uniquement les communications de diffusion (`comGroupe_uti = 0`) et laisse le compte actif. La suppression définitive à l'initiative du titulaire est un parcours cible distinct, spécifié dans `user-account-lifecycle-management` avec récapitulatif, nouvelle validation du mot de passe, confirmation explicite et anonymisation des commandes conservées. Change `cadrer-desactivation-de-compte`. |

### Administration & sécurité

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-ADM-1 | Gestion des utilisateurs | REPRIS | FR-030, FR-032, FR-035, FR-036 | user-account-lifecycle-management | admin-utilisateurs | |
| UC-ADM-2 | Forcer le changement de mot de passe d'un utilisateur | REPRIS | FR-038 | — | — | Repris (arbitrage 6). `FR-038` créé. Descente incomplète, change `reprendre-administration-habilitations`. |
| UC-ADM-3 | Gestion des groupes | REPRIS | FR-025 | authorization-and-menu-resolution | — | Repris (arbitrage 4). Le regroupement initial classait à tort ce use-case en méta-configuration : il relève de l'administration des habilitations, portée par le persona « Administrateur fonctionnel ». `FR-025` créé. Descente incomplète côté maquette. |
| UC-ADM-4 | Affectation d'utilisateurs à des groupes | REPRIS | FR-026 | authorization-and-menu-resolution | — | Repris (arbitrage 4). `FR-026` créé, incluant la perte immédiate des droits au retrait. Descente incomplète côté maquette. |
| UC-ADM-5 | Gestion des fonctions applicatives | REPRIS | FR-027 | authorization-and-menu-resolution | admin-roles-permissions | Repris (arbitrage 4). `FR-027` créé pour combler l'exigence produit absente alors que l'écran existait déjà. |
| UC-ADM-6 | Gestion des autorisations par groupe | REPRIS | FR-020, FR-021 | authorization-and-menu-resolution | admin-roles-permissions | |
| UC-ADM-7 | Gestion du menu de navigation | ABANDONNÉ | — | — | — | **Obsolète par construction** (arbitrage 4). Legacy : le menu est une donnée administrable en base. Cible : `FR-022` calcule le menu à partir des permissions effectives, il n'y a donc plus de menu à administrer. |
| UC-ADM-8 | Gestion des modules | ABANDONNÉ | — | — | — | **Obsolète par construction** (arbitrage 4). La notion de module applicatif paramétrable en base n'a pas d'équivalent dans l'architecture cible. |
| UC-ADM-9 | Gestion des composants applicatifs | ABANDONNÉ | — | — | — | **Obsolète par construction** (arbitrage 4). Legacy : les composants sont des lignes de table. Cible : les composants sont du code, `FR-021` n'en contrôle que l'accès. |

### Catalogue & articles

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-ART-1 | Consultation de la liste des articles | REPRIS | FR-046 | period-catalog-and-pricing-management, frontend-reference-screen-flows | gestionnaire-articles-liste | |
| UC-ART-2 | Création / modification / suppression d'un article | REPRIS | FR-046, FR-047 | period-catalog-and-pricing-management | gestionnaire-articles-creation | |
| UC-ART-3 | Gestion des prix d'article par année et famille | REPRIS | FR-048, FR-049 | period-catalog-and-pricing-management, frontend-reference-screen-flows | gestionnaire-articles-tarifs, gestionnaire-articles-tarifs-edition-prix | |
| UC-ART-4 | Gestion des fournisseurs | ABANDONNÉ | — | — | — | **Pas de référentiel fournisseurs** (arbitrage 2). L'entité fournisseur n'est pas gérée dans la solution cible. La dénomination « prix fournisseur HT » est en revanche conservée : elle désigne le prix d'achat de l'article, indépendamment de l'identité du fournisseur. La consolidation et le mode de tarification « HT + marge » restent donc en vigueur. Retrait du rattachement fournisseur sur l'article et ajustement du vocabulaire : change `retirer-referentiel-fournisseur`. |
| UC-ART-5 | Gestion des années de campagne | REPRIS | FR-043, FR-044, FR-045 | period-catalog-and-pricing-management, frontend-reference-screen-flows | gestionnaire-periodes, gestionnaire-periodes-detail, gestionnaire-periodes-creation, gestionnaire-periodes-modification | Renommé « périodes » dans la cible. Écart de vocabulaire assumé, sans effet sur le comportement. |

### Commande FSE

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-CMD-1 | Initialisation d'une commande | REPRIS | FR-050, FR-051 | fse-order-and-payment-workflow, frontend-reference-screen-flows | commandes-formulaire | |
| UC-CMD-2 | Sélection et quantification des articles | REPRIS | FR-050 | fse-order-and-payment-workflow, frontend-reference-screen-flows | commandes-formulaire | |
| UC-CMD-3 | Confirmation et enregistrement de la commande | REPRIS | FR-050, FR-052 | fse-order-and-payment-workflow, frontend-reference-screen-flows | commandes-formulaire | |
| UC-CMD-4 | Consultation du détail d'une commande | REPRIS | FR-050 | fse-order-and-payment-workflow, frontend-reference-screen-flows | commandes-detail | |
| UC-CMD-5 | Annulation d'une commande | REPRIS | FR-052, FR-065, FACT-07, FACT-08 | fse-order-and-payment-workflow, frontend-reference-screen-flows | commandes-liste, commandes-detail | **Règle modifiée** (arbitrage 3). Une commande réglée **peut** être annulée, avec **émission d'un avoir**. La restriction posée par `frontend-reference-screen-flows` (« annulation refusée après paiement ») est **invalidée** : elle n'était pas conforme à la réglementation applicable, pas davantage que le comportement legacy qui annulait sans produire de pièce comptable. `FR-065`, `FACT-07` et `FACT-08` créés. Mise en cohérence de la spécification des statuts : change `annuler-commande-reglee-avec-avoir`. Le remboursement manuel consécutif à l'annulation fait l'objet du change `rembourser-commande-annulee`. |
| UC-CMD-6 | Liaison d'une commande à un utilisateur existant | REPRIS | FR-055 | — | — | Descente incomplète : aucune spécification ni maquette ne porte le rattachement d'une commande à un compte. |
| UC-CMD-7 | Relance d'une commande non réglée | REPRIS | FR-078, FR-079 | — | — | Descente incomplète : décidé au PRD, absent des spécifications et des maquettes. |
| UC-CMD-8 | Liste des commandes en cours (vue gestionnaire) | REPRIS | FR-052 | frontend-reference-screen-flows | commandes-liste | |
| UC-CMD-9 | Liste des commandes par utilisateur (vue famille) | REPRIS | FR-050 | frontend-reference-screen-flows | commandes-liste-famille | |
| UC-CMD-10 | Génération du PDF de commande ou de facture | REPRIS | FR-070, FACT-01, FACT-02, FACT-03, FACT-04, FACT-05, FACT-06 | fse-order-and-payment-workflow, templated-mail-and-document-generation | commandes-detail | |

### Paiement

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-PAY-1 | Enregistrement d'un règlement | REPRIS | FR-053, FR-054 | fse-order-and-payment-workflow, frontend-reference-screen-flows | gestionnaire-paiements-saisie | |
| UC-PAY-2 | Annulation d'un règlement | À CADRER | — | — | — | **Cadrage attendu** : l'arbitrage 3 établit que la correction d'une commande réglée passe par l'annulation avec émission d'un avoir. Il n'est pas établi que la correction d'une **saisie d'encaissement erronée** (montant ou banque incorrects, sans annulation commerciale) relève du même mécanisme. À instruire dans le change `annuler-commande-reglee-avec-avoir`. |
| UC-PAY-3 | Initialisation d'un paiement par carte bancaire | REPRIS | FR-060, FR-062, FR-063 | fse-order-and-payment-workflow | commandes-formulaire | |
| UC-PAY-4 | Traitement du retour de paiement CB | REPRIS | FR-061, FR-064 | fse-order-and-payment-workflow | — | Descente incomplète : aucun écran de retour de paiement dans les maquettes. |
| UC-PAY-5 | Notification serveur de confirmation de paiement | REPRIS | FR-061 | fse-order-and-payment-workflow | N/A | Comportement serveur à serveur, sans interface. |
| UC-PAY-6 | Suppression d'une cotisation sur une commande | REPRIS | COTIS-01, COTIS-02, COTIS-03, COTIS-04, COTIS-05 | fse-order-and-payment-workflow | — | **Restriction nouvelle assumée** (arbitrage PRD §14.1 point 9) : suppression interdite dès que la commande est validée et facturée. Descente incomplète côté maquette. |

### Historique & suivi

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-HISTO-1 | Consultation de l'historique des commandes d'une campagne | REPRIS | FR-052 | frontend-reference-screen-flows | commandes-liste | Couvert par le filtre période et le filtre statuts de la liste gestionnaire, sans écran d'historique dédié. |
| UC-HISTO-2 | Consultation du journal des actions applicatives | REPRIS | FR-083, FR-084 | — | — | **Règle modifiée** (arbitrage 5). La consultation est ouverte aux **gestionnaires**, et non réservée aux administrateurs comme dans le legacy. Informations restituées : domaine, action, contexte, date-heure. `FR-084` créé, `FR-083` ne couvrait que la production de la trace. Descente incomplète : change `consulter-journal-actions-et-anomalies`. |
| UC-HISTO-3 | Consultation des anomalies applicatives | REPRIS | FR-082, FR-085 | — | — | Repris sur le même principe que UC-HISTO-2 (arbitrage 5). `FR-085` créé, `FR-082` ne couvrait que la journalisation. Descente incomplète : change `consulter-journal-actions-et-anomalies`. |

### Stock

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-STK-1 | Consultation de la liste des stocks | REPRIS | FR-056 | stock-and-supplier-consolidation, frontend-reference-screen-flows | gestionnaire-stock | |
| UC-STK-2 | Mise à jour du stock d'un article | REPRIS | FR-056, FR-057 | stock-and-supplier-consolidation, frontend-reference-screen-flows | gestionnaire-stock | **Restriction nouvelle assumée** : `FR-057` impose un stock entier positif ou nul, là où le legacy ne posait pas la contrainte. |

### Consolidation & outils

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-CON-1 | Génération du PDF de consolidation | REPRIS | FR-058, FR-059 | stock-and-supplier-consolidation, frontend-reference-screen-flows | gestionnaire-consolidation | **Divergence de nature assumée** (arbitrage PRD §14.1 point 8) : le legacy produit un PDF, la cible expose une consolidation consultable en continu sous forme de snapshot, sans workflow d'approbation. |
| UC-CON-2 | Clôture de la période de commande | REPRIS | FR-043, FR-045 | period-catalog-and-pricing-management, frontend-reference-screen-flows | gestionnaire-periodes, gestionnaire-periodes-detail | Rattaché au domaine périodes dans la cible, au lieu de l'écran de consolidation. |
| UC-CON-3 | Génération en lot des PDF de commandes réglées par CB | À CADRER | — | — | — | **Cadrage attendu** : traitement métier concret, à distinguer du moteur d'éditions abandonné par l'arbitrage 1. `FR-070` ne couvre que la génération documentaire unitaire. Reste à préciser le déclenchement, le critère de regroupement en lots et la restitution attendue. Change `cadrer-generation-documents-en-lot`. |

### Éditions & exports

Le domaine est **abandonné** (arbitrage 1, révisé). Les six use-cases décrivent un **moteur** de reporting — listes paramétrables, états configurés en base, gabarits HTML administrables, requêtes SQL stockées — et non un besoin métier. Ils relèvent de la même nature que la méta-configuration applicative de l'arbitrage 4 : du framework générique piloté par la base de données.

L'abandon porte sur le moteur, **pas sur le besoin d'éditions**. La solution cible exprime ses éditions comme des fonctionnalités métier concrètes, directement dans les exigences — ainsi de l'édition de bordereaux de chèques en fin de période, portée par `frontend-reference-screen-flows`. Toute édition future suivra ce mode d'expression, sans passer par un mécanisme configurable.

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-EDT-1 | Paramétrage des listes d'impression | ABANDONNÉ | — | — | — | Moteur de configuration de listes, sans équivalent dans une solution où les listings sont des écrans métier spécifiés. |
| UC-EDT-2 | Export d'une liste configurable | ABANDONNÉ | — | — | — | Export piloté par le paramétrage de UC-EDT-1, abandonné avec lui. Un besoin d'export métier identifié sera exprimé comme fonctionnalité dédiée. |
| UC-EDT-3 | Gestion des états de reporting | ABANDONNÉ | — | — | — | Administration d'états définis en base (code, type de données, modèle HTML ou requête SQL) : méta-configuration sans équivalent cible. |
| UC-EDT-4 | Génération d'un état de reporting | ABANDONNÉ | — | — | — | Exécution du moteur d'états de UC-EDT-3, abandonnée avec lui. |
| UC-EDT-5 | Gestion des modèles HTML | ABANDONNÉ | — | — | — | `FR-071` conserve la **fusion** de gabarits, mais leur administration par l'utilisateur relève du moteur abandonné. Les gabarits deviennent des artefacts de développement. |
| UC-EDT-6 | Gestion des requêtes SQL paramétrables | ABANDONNÉ | — | — | — | L'édition de requêtes SQL stockées en base est abandonnée. La cible conserve un mécanisme distinct et non administrable — exécution de requêtes identifiées par code fonctionnel, définies par le développement (`FR-040` à `FR-042`, capacité `request-catalog-execution`) — qui ne couvre pas ce use-case et n'est donc pas référencé ici. |

### Communication / e-mail

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-MAIL-1 | Prévisualisation et envoi d'un e-mail | REPRIS | FR-071, FR-072, FR-075, FR-076, FR-077, FR-079 | templated-mail-and-document-generation | — | Descente incomplète : aucun écran de composition et de prévisualisation dans les maquettes. |
| UC-MAIL-2 | Gestion de la liste de diffusion d'un groupe | ABANDONNÉ | — | — | — | **Non repris à ce stade** (arbitrage 6). `FR-076` conserve le **ciblage** d'un groupe comme destinataire ; la **gestion** de la liste de diffusion n'est pas retenue. |

### Navigation & accueil

| UC | Libellé | Décision | PRD | Spec | Maquette | Écart / justification |
| --- | --- | --- | --- | --- | --- | --- |
| UC-NAV-1 | Chargement dynamique d'un composant métier | REPRIS | FR-003, FR-021 | frontend-shell-and-dynamic-components | N/A | **Divergence de nature assumée** : le chargement dynamique depuis la base devient une navigation applicative sur du code. Mécanisme technique, sans maquette attendue. |
| UC-NAV-2 | Affichage du menu selon les droits | REPRIS | FR-022, FR-024 | authorization-and-menu-resolution, frontend-shell-and-dynamic-components | shell-reference | |
| UC-NAV-3 | Pages d'accueil contextuelles | À CADRER | — | frontend-shell-and-dynamic-components | shell-reference | **Cadrage attendu : à préciser dans le PRD** (arbitrage 6). Le legacy propose quatre accueils distincts (public, administrateur, gestionnaire, famille). La cible ne dispose que de `shell-reference`, orienté gestionnaire. Question ouverte reportée au PRD §14.2. |

## Écarts et divergences sémantiques

Les cas ci-dessous sont ceux où un libellé identique recouvre deux comportements différents. Ils échappent par nature à toute recherche par mot-clé et sont donc consignés explicitement.

| Réf. | Use-cases | Nature de l'écart |
| --- | --- | --- |
| D1 | UC-AUTH-8 | Désinscription : désactivation de compte (legacy) contre désinscription des communications de diffusion (cible). Deux comportements distincts sous un même mot. La désactivation est reprise, son périmètre reste à cadrer. |
| D2 | UC-CMD-5, UC-PAY-2 | Annulation : le legacy annulait sans produire de pièce comptable, la cible impose l'émission d'un avoir. La restriction intermédiaire « annulation refusée après paiement » est invalidée. |
| D3 | UC-EDT-6 | Requêtes paramétrables : moteur SQL administrable (legacy) contre requêtes identifiées par code fonctionnel et définies par le développement (cible). Deux mécanismes distincts, le premier abandonné. |
| D4 | UC-ADM-7, UC-ADM-9, UC-NAV-1, UC-EDT-1 à UC-EDT-6 | Méta-configuration : données administrables en base (legacy) contre code et permissions (cible). Le moteur d'éditions relève de la même nature que le menu et les composants. |
| D5 | UC-CON-1 | Consolidation : document PDF ponctuel (legacy) contre vue consultable en continu (cible). Écart assumé au PRD §14.1 point 8. |
| D6 | UC-ART-5 | Renommage « années de campagne » en « périodes ». Vocabulaire uniquement, sans effet sur le comportement. |
| D7 | UC-ART-4 | Fournisseur : entité gérée (legacy) contre simple qualification du prix d'achat (cible). « Prix fournisseur HT » subsiste sans référentiel derrière. |
| D8 | UC-HISTO-2 | Journal des actions : réservé aux administrateurs (legacy) contre ouvert aux gestionnaires (cible). |

## Arbitrages rendus

Décisions issues de la revue d'arbitrage. Le détail de l'instruction figure dans `openspec/changes/tracer-couverture-use-cases-legacy/design.md`.

| N° | Objet | Décision rendue | Use-cases |
| --- | --- | --- | --- |
| 1 | Domaine « Éditions & exports » | **Abandonné** (décision révisée). Les six use-cases décrivent un moteur de reporting configurable, pas un besoin métier : même nature que la méta-configuration de l'arbitrage 4. Le besoin d'éditions subsiste, mais s'exprime désormais comme fonctionnalité métier concrète. | UC-EDT-1 à UC-EDT-6 |
| 2 | Référentiel fournisseurs | Pas de référentiel. La dénomination « prix fournisseur HT » est conservée comme prix d'achat. | UC-ART-4 |
| 3 | Annulation d'une commande payée | Annulation possible, avec émission d'un avoir. Le legacy n'était pas conforme à la réglementation. | UC-CMD-5, UC-PAY-2 |
| 4 | Méta-configuration applicative | Menu, modules et composants abandonnés, obsolètes par construction. L'administration des habilitations est en revanche conservée. | UC-ADM-7, UC-ADM-8, UC-ADM-9 / UC-ADM-3, UC-ADM-4, UC-ADM-5 |
| 5 | Audit et anomalies | Repris avec règles modifiées : consultation ouverte aux gestionnaires, restituant domaine, action, contexte et date-heure. | UC-HISTO-2, UC-HISTO-3 |
| 6 | Use-cases isolés | Renvoi du code et forçage de mot de passe repris ; liste de diffusion non reprise ; accueils contextuels à préciser au PRD. | UC-AUTH-7, UC-ADM-2, UC-MAIL-2, UC-NAV-3 |
| 7 | Désactivation de compte à l'initiative de l'utilisateur | Reprise. Périmètre à cadrer : réversibilité, articulation avec l'anonymisation, sort des commandes rattachées, et séparation d'avec la désinscription des communications. | UC-AUTH-8 |
| 8 | Génération de documents en lot | À cadrer. Traitement métier concret, détaché du moteur d'éditions abandonné par l'arbitrage 1. | UC-CON-3 |

**Aucun arbitrage en attente.** Les 57 use-cases portent une décision. Les décisions `À CADRER` restent à instruire dans leur change respectif, mais leur principe est acquis.

## Changements de suite ouverts

Chaque ensemble cohérent de use-cases repris ou à cadrer, non encore spécifié, fait l'objet d'un changement OpenSpec distinct.

| Changement | Use-cases couverts |
| --- | --- |
| `retirer-referentiel-fournisseur` | UC-ART-4 |
| `cadrer-editions-et-exports` | *(clos — le domaine est abandonné, arbitrage 1 révisé)* |
| `cadrer-generation-documents-en-lot` | UC-CON-3 |
| `reprendre-administration-habilitations` | UC-ADM-2, UC-ADM-3, UC-ADM-4 |
| `annuler-commande-reglee-avec-avoir` | UC-CMD-5, UC-PAY-2 |
| `rembourser-commande-annulee` | UC-CMD-5 |
| `consulter-journal-actions-et-anomalies` | UC-HISTO-2, UC-HISTO-3 |
| `renvoyer-code-de-validation` | UC-AUTH-7 |
| `cadrer-desactivation-de-compte` | UC-AUTH-8 |

`UC-NAV-3` ne fait pas l'objet d'un change : son cadrage consiste à préciser les accueils contextuels dans le PRD (§14.2), en amont de toute spécification.

Les descentes incomplètes restantes (UC-AUTH-5, UC-CMD-6, UC-CMD-7, UC-PAY-4, UC-MAIL-1) portent sur des use-cases déjà décidés au PRD et relèvent de la production de spécifications et de maquettes, sans arbitrage préalable.

## Règle de revue au jalon

La clôture d'un jalon de refonte MUST être précédée d'une restitution des use-cases en `À TRANCHER`. Un jalon ne peut être clos tant que des arbitrages ouverts n'ont pas été instruits, c'est-à-dire tranchés ou explicitement reportés avec échéance. Le nombre d'arbitrages ouverts est l'indicateur de pilotage de cette matrice : il doit décroître.

## Contrôle de cohérence

Vérification structurelle, sans dépendance externe, sur le modèle de la commande de couverture de `docs/legacy-use-cases.md`.

```powershell
pwsh -File scripts/check-legacy-coverage.ps1
```

Le contrôle se termine en échec si au moins une anomalie bloquante est détectée :

- un identifiant `UC-*` de la cartographie est absent de la matrice, ou y figure plusieurs fois ;
- une matrice référence un identifiant `UC-*` inconnu de la cartographie ;
- une décision est vide ou hors des valeurs autorisées ;
- une ligne `REPRIS` ne référence aucun artefact ;
- une ligne `ABANDONNÉ` ne porte aucune justification ;
- une référence pointe un artefact inexistant (capacité absente de `openspec/specs/`, écran absent de `maquettes/manifest/screens.json`, exigence absente du PRD).

Les descentes incomplètes et les arbitrages ouverts sont restitués séparément, en avertissement, et ne font pas échouer le contrôle.
