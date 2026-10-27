# Maquettes statiques

Ce dossier contient un référentiel **100% statique** des écrans de la réécriture frontend.
Il sert de base d'alignement fonctionnel avant l'implémentation dynamique.

## Arborescence

- `workbench/`: point d'entrée de consultation (navigation gauche + aperçu droite)
- `screens/`: maquettes statiques par domaine fonctionnel
- `assets/`: style partagé
- `manifest/`: catalogue des écrans

Le catalogue est déclaré dans `manifest/screens.json` et `manifest/screens.js`.
Toute modification doit être reportée dans les deux fichiers : le workbench charge
le manifeste JavaScript en priorité pour permettre aussi la consultation locale
sans serveur.

## Convention de nommage

Chaque écran respecte la convention suivante:

- **id manifest**: `<domaine>-<écran>`
- **rôles**: un ou plusieurs rôles parmi `famille` | `gestionnaire` | `admin` (utilisé pour le regroupement principal)
- **catégorie**: regroupement fonctionnel affiché sous le titre de l'écran
- **titre**: libellé humain pour les ateliers
- **fichier source**: `screens/<domaine>/<id>.html`

Exemple:

- id: `entree-connexion`
- rôles: `["famille"]`
- catégorie: `Parcours d'entrée`
- titre: `Connexion`
- source: `../screens/entree/entree-connexion.html`

## Utilisation

1. Ouvrir `workbench/index.html` dans un navigateur.
2. Sélectionner un écran dans la liste gauche.
3. Basculer entre les vues **Téléphone** et **Desktop** selon le contexte de revue.
4. Vérifier l'aperçu statique à droite.

## Publication GitHub Pages

- URL racine: `https://fse-a-mailloux.github.io/cmd-coopsco/`
- URL workbench: `https://fse-a-mailloux.github.io/cmd-coopsco/workbench/`
- Ces liens sont à communiquer aux parties prenantes des ateliers maquettes pour consultation distante.

## Principes de rendu

- Les maquettes sont construites en **mobile-first** (mise en page mobile par défaut, enrichie en desktop via media queries).
- Le workbench permet de changer le mode de prévisualisation sans backend.
- Les trois rôles **Famille**, **Gestionnaire** et **Admin** sont fixes, avec des permissions prédéfinies non modifiables, y compris par un admin. Aucun écran de gestion des rôles ou permissions n'est proposé.
- Dans le listing **Utilisateurs**, un admin peut affecter un rôle existant à un compte, consulter l'historique de ses changements de rôle et imposer au titulaire un changement de mot de passe à sa prochaine connexion, sans modifier les permissions des rôles.
- Le rôle **famille** couvre les parcours d'entrée (connexion, création de compte, activation, récupération d'accès) et le parcours authentifié de suppression de compte.
- L'écran **Suppression de mon compte** distingue la suppression définitive de la désinscription aux communications de diffusion.
- Le domaine gestionnaire couvre périodes, articles, tarifs, stock et consolidation.
- Le domaine articles est séparé en deux écrans: **listing** et **création**.
- Le listing **Articles** propose une **recherche multicritères unique** sur la référence article, le libellé et la marque.
- Un article est défini par: **code de référence**, **libellé**, **marque**.
- Un article porte deux disponibilités distinctes: **commandes** (visibilité en création de commande) et **stock** (visibilité en inventaire).
- L'écran **Stock** conserve l'indicateur **articles en rupture** et n'affiche plus **articles en alerte** ni **entrepôts suivis**.
- Le tableau de l'écran **Stock** ne contient pas de colonne **seuil mini**.
- Chaque ligne article du **Stock** propose une action **Voir courbe** pour visualiser l'évolution du stock selon les **dates de modification de stock**.
- Chaque ligne article du **Stock** propose une action de mise à jour du **nombre disponible**; l'**état de stock** est calculé automatiquement (**Rupture** si disponible = 0, sinon **Disponible**).
- L'écran **Consolidation** est un **snapshot horodaté** sur la **période en cours** (pas un simple tableau de synthèse global) et n'expose pas de sélecteur de période.
- Le tableau de **Consolidation** expose par article le **stock disponible**, la **quantité commandée familles**, la saisie du **besoin FSE (stock à conserver)** et la **quantité à commander** calculée avec `max(0, qte commandée familles + besoin FSE - stock disponible)`.
- Une consolidation peut être **enregistrée** puis **consultée ultérieurement** dans un historique horodaté.
- L'écran **Consolidation** affiche `CA estimé`, `Marge estimée` et `Coût du stock prévu (besoin FSE)` pour la période en cours, avec indication explicite en cas d'**estimation partielle** (prix fournisseur HT manquants).
- L'écran **Consolidation** n'affiche pas de **taux de service**.
- Les commandes utilisent le référentiel de statuts: **Brouillon**, **En attente de paiement**, **Paiement partiel**, **Paiement en cours** (CB en traitement), **Confirmée**, **Annulée**.
- Un **gestionnaire habilité** peut annuler une commande en **paiement partiel** ou **confirmée**; un avoir est émis et, si nécessaire, la facture est produite avant l'avoir. Cette annulation n'effectue pas de remboursement.
- Dans **Mes commandes**, une famille peut annuler une commande **sans paiement enregistré**; l'annulation après paiement reste réservée au gestionnaire habilité.
- La maquette **Liste des commandes (gestionnaire)** propose un **champ de recherche multicritères unique** (numéro de commande, email, nom/prénom parent, nom/prénom enfant), un filtre **Période** avec la **période en cours** par défaut, et un filtre **Statuts** en sélection multiple.
- Le **Shell de référence** n'affiche pas de zone d'écrans dynamiques: il présente un tableau de bord de la **période en cours** (état, **CA**, **nb commandes**, **panier moyen** autour de **50 EUR**, statuts) et un récapitulatif des **périodes passées**.
- Le formulaire de commande famille est **multi-étapes**: (1) validation email/téléphone, (2) saisie du nombre d'enfants puis des informations **nom / prénom / niveau** pour chaque enfant (sans niveau principal), (3) saisie des quantités sur la liste complète des fournitures, (4) validation de la commande, (5) choix du mode de paiement.
- Les enfants peuvent être pré-renseignés depuis la commande précédente.
- Le formulaire de commande est basé sur la liste de fournitures de référence du collège (par niveau 6e à 3e), avec quantités recommandées et quantités choisies.
- L'étape 2 est intitulée **Enfants concernés par la commande** et présente des zones de saisie par enfant.
- L'étape 2 affiche un texte indicatif de pré-remplissage (sans bouton dédié), limite le nombre d'enfants à **8 maximum**, et contextualise le champ niveau avec la période en cours.
- Quand deux cartes enfants sont visibles dans l'étape 2, la valeur **Nombre d'enfants** est présélectionnée à **2**.
- L'étape 2 expose des boutons de navigation vers l'étape précédente et l'étape suivante.
- L'étape 3 comporte des champs de saisie de quantité et reste représentative d'une liste longue (35 à 40 références).
- L'étape 3 affiche le **prix TTC** de chaque article correspondant.
- L'étape 3 est responsive avec un mode **desktop tabulaire** et un mode **téléphone concis** en cartes.
- La majorité des fournitures ont une **référence article unique**; seules certaines fournitures proposent plusieurs articles correspondants.
- Une fourniture multi-références permet de répartir les quantités entre plusieurs articles (ex: 2 de A + 3 de B), notamment sur des cas comme **Règle plate 30cm** et **Lot de surligneurs de couleurs**.
- Sur les exemples multi-références (règle plate, surligneurs), les quantités recommandées par niveau sont volontairement fixées à **1** pour garder une lecture simple.
- Le formulaire ajoute une **Étape 4 - Validation de votre commande** qui reprend uniquement les articles choisis avec quantité, prix unitaire TTC, total ligne et total global.
- Le formulaire ajoute une **Étape 5 - Mode de paiement** avec choix **CB (HelloAsso)**, **chèque** ou **espèce**.
- Les paiements **chèque** et **espèce** affichent une notice succincte: chèque avec nom enfant/niveau/numéro de commande au dos + bon de commande imprimé; espèce avec appoint obligatoire + bon de commande imprimé.
- L'écran **Détail commande** reprend des lignes de commande réalistes cohérentes avec les articles choisis dans le parcours de création famille.
- La cotisation est configurée par période avec dégressivité: prix 1er enfant, 2e enfant, 3e et suivants.
- Le coût de cotisation est affiché dès le choix du nombre d'enfants et intégré au total de commande.
- Le gestionnaire dispose d'un parcours d'**encaissement** dédié déclenché depuis une action sur la commande (liste ou détail), ouvrant directement la saisie d'un ou plusieurs paiements chèque/espèce.
- L'écran de saisie paiements affiche le bénéficiaire de la commande, positionne l'ajout de ligne au plus proche du tableau des paiements et n'affiche pas de bouton de recherche.
- Le détail d'une commande annulée avec avoir présente les remboursements manuels déjà saisis, le montant restant et l'action qui ouvre l'écran `commandes-remboursement-saisie`; ces maquettes illustrent le parcours cible sans exécuter ni persister un remboursement.
- Pour un paiement par chèque, la saisie inclut **montant**, **banque** et **titulaire du compte** en contexte particulier/famille pour l'édition des bordereaux de fin de période.
- Les tarifs sont un **sous-écran d'un article**: liste des prix de l'article selon les périodes, avec un écran de **modification du prix par période**.
- La liste des tarifs d'un article est triée par date de période **décroissante** (plus récentes en premier).
- Le prix article est unique en **TTC** (pas de prix standard/réseau) et peut être formulé soit en **TTC direct**, soit via **prix fournisseur HT + marge (%)**.
- Le prix fournisseur HT est le prix d'achat de l'article; aucun fournisseur n'est géré, sélectionné ou rattaché.
- Le mode de calcul d'un tarif est persisté en plus du total TTC (par période et par article).
- La modification de prix cible une **période** (pas de date d'effet) et ne comporte pas de champ **motif de modification**.
- Le domaine périodes expose le **listing avec actions** (ouvrir, fermer, définir en cours, reprise de tarifs), un écran **détail période**, un écran **création période** et un écran **modification période**.
- Dans `Périodes - Listing et actions`, une seule période est marquée **en cours**; seul cet enregistrement peut utiliser l'action **Ouvrir**; l'action **Définir en cours** est active uniquement pour les périodes non courantes.
- Le statut d'une période est **En cours** ou **Non en cours** (période passée ou en préparation) et son état d'ouverture est **Ouverte** ou **Fermée**.
- Une période ne peut être **Ouverte** que si son statut est **En cours**.
- Dans `Périodes - Détail`, un bouton de modification ouvre l'écran **Périodes - Modification**.
- Écrans de listing tabulaires identifiés dans le référentiel: `Périodes - Listing et actions`, `Liste des commandes (gestionnaire)`, `Mes commandes`, `Articles - Listing`, `Stock`, `Consolidation`, `Utilisateurs`.
- Les tableaux des écrans de listing sont rendus en mode responsive via un conteneur à défilement horizontal en vue téléphone, tout en conservant la lecture tabulaire complète en desktop.

### Journaux applicatifs

Les écrans **Journal des actions applicatives** et **Anomalies applicatives** sont des consultations partagées entre les groupes Gestionnaire et Admin, en lecture seule. Leurs variantes (liste renseignée, liste vide, accès refusé, session expirée et erreur de chargement) utilisent exclusivement des exemples fictifs ; les états de refus, d’expiration et d’erreur n’affichent aucune trace. Aucune action de modification ou de traitement n’est proposée.

Sur téléphone, les entrées sont présentées en cartes avec domaine, action, contexte et date-heure, sans défilement horizontal de la page. À partir de 768 px, le tableau reprend les mêmes entrées dans le même ordre.

Ces écrans statiques n’appellent aucun service et ne constituent pas un contrôle d’accès réel. La déclaration d’un rôle dans le catalogue du workbench organise uniquement la navigation ; tout accès aux données devra être contrôlé côté serveur.

Ce dossier est la référence d'entrée pour les futures tâches d'implémentation frontend.
