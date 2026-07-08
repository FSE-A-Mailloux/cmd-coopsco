# Maquettes statiques

Ce dossier contient un référentiel **100% statique** des écrans de la réécriture frontend.
Il sert de base d'alignement fonctionnel avant l'implémentation dynamique.

## Arborescence

- `workbench/`: point d'entrée de consultation (navigation gauche + aperçu droite)
- `screens/`: maquettes statiques par domaine fonctionnel
- `assets/`: style partagé
- `manifest/`: catalogue des écrans

## Convention de nommage

Chaque écran respecte la convention suivante:

- **id manifest**: `<domaine>-<ecran>`
- **roles**: un ou plusieurs roles parmi `famille` | `gestionnaire` | `admin` (utilise pour le regroupement principal)
- **catégorie**: regroupement fonctionnel affiché sous le titre de l'écran
- **titre**: libellé humain pour les ateliers
- **fichier source**: `screens/<domaine>/<id>.html`

Exemple:

- id: `entree-connexion`
- roles: `["famille"]`
- catégorie: `Parcours d'entrée`
- titre: `Connexion`
- source: `../screens/entree/entree-connexion.html`

## Utilisation

1. Ouvrir `workbench/index.html` dans un navigateur.
2. Sélectionner un écran dans la liste gauche.
3. Basculer entre les vues **Telephone** et **Desktop** selon le contexte de revue.
4. Vérifier l'aperçu statique a droite.

## Principes de rendu

- Les maquettes sont construites en **mobile-first** (mise en page mobile par defaut, enrichie en desktop via media queries).
- Le workbench permet de changer le mode de previsualisation sans backend.
- Le role **famille** couvre les parcours d'entree: connexion, creation de compte, activation et recuperation d'acces.
- Le domaine gestionnaire couvre periodes, articles, tarifs, stock et consolidation.
- Le domaine articles est separe en deux ecrans: **listing** et **creation**.
- Un article est defini par: **code de reference**, **libelle**, **marque**.
- Un article porte deux disponibilites distinctes: **commandes** (visibilite en creation de commande) et **stock** (visibilite en inventaire).
- Les commandes utilisent le referentiel de statuts: **Brouillon**, **En attente de paiement**, **Paiement partiel**, **Paiement en cours** (CB en traitement), **Confirmee**, **Annulee**.
- Une commande peut etre **annulee tant qu'aucun paiement n'a ete enregistre**; l'annulation n'est plus possible apres paiement.
- La maquette **Liste des commandes (gestionnaire)** propose un **champ de recherche multicriteres unique** (numero de commande, email, nom/prenom parent, nom/prenom enfant), un filtre **Periode** avec la **periode en cours** par defaut, et un filtre **Statuts** en selection multiple.
- Le **Shell de reference** n'affiche pas de zone d'ecrans dynamiques: il presente un tableau de bord de la **periode en cours** (etat, **CA**, **nb commandes**, **panier moyen** autour de **50 EUR**, statuts) et un recapitulatif des **periodes passees**.
- Le formulaire de commande famille est **multi-etapes**: (1) validation email/telephone, (2) saisie du nombre d'enfants puis des informations **nom / prenom / niveau** pour chaque enfant (sans niveau principal), (3) saisie des quantites sur la liste complete des fournitures, (4) validation de la commande, (5) choix du mode de paiement.
- Les enfants peuvent etre pre-renseignes depuis la commande precedente.
- Le formulaire de commande est base sur la liste de fournitures de reference du college (par niveau 6e a 3e), avec quantites recommandees et quantites choisies.
- L'etape 2 est intitulee **Enfants concernes par la commande** et presente des zones de saisie par enfant.
- L'etape 2 affiche un texte indicatif de pre-remplissage (sans bouton dedie), limite le nombre d'enfants a **8 maximum**, et contextualise le champ niveau avec la periode en cours.
- Quand deux cartes enfants sont visibles dans l'etape 2, la valeur **Nombre d'enfants** est preselectionnee a **2**.
- L'etape 2 expose des boutons de navigation vers l'etape precedente et l'etape suivante.
- L'etape 3 comporte des champs de saisie de quantite et reste representative d'une liste longue (35 a 40 references).
- L'etape 3 affiche le **prix TTC** de chaque article correspondant.
- L'etape 3 est responsive avec un mode **desktop tabulaire** et un mode **telephone concis** en cartes.
- La majorite des fournitures ont une **reference article unique**; seules certaines fournitures proposent plusieurs articles correspondants.
- Une fourniture multi-references permet de repartir les quantites entre plusieurs articles (ex: 2 de A + 3 de B), notamment sur des cas comme **Regle plate 30cm** et **Lot de surligneurs de couleurs**.
- Sur les exemples multi-references (regle plate, surligneurs), les quantites recommandees par niveau sont volontairement fixees a **1** pour garder une lecture simple.
- Le formulaire ajoute une **Etape 4 - Validation de votre commande** qui reprend uniquement les articles choisis avec quantite, prix unitaire TTC, total ligne et total global.
- Le formulaire ajoute une **Etape 5 - Mode de paiement** avec choix **CB (HelloAsso)**, **cheque** ou **espece**.
- Les paiements **cheque** et **espece** affichent une notice succincte: cheque avec nom enfant/niveau/numero de commande au dos + bon de commande imprime; espece avec appoint obligatoire + bon de commande imprime.
- L'ecran **Detail commande** reprend des lignes de commande realistes coherentes avec les articles choisis dans le parcours de creation famille.
- La cotisation est configuree par periode avec degressivite: prix 1er enfant, 2e enfant, 3e et suivants.
- Le cout de cotisation est affiche des le choix du nombre d'enfants et integre au total de commande.
- Le gestionnaire dispose d'un parcours d'**encaissement** dedie declenche depuis une action sur la commande (liste ou detail), ouvrant directement la saisie d'un ou plusieurs paiements cheque/espece.
- L'ecran de saisie paiements affiche le beneficiaire de la commande, positionne l'ajout de ligne au plus proche du tableau des paiements et n'affiche pas de bouton de recherche.
- Pour un paiement par cheque, la saisie inclut **montant**, **banque** et **titulaire du compte** en contexte particulier/famille pour l'edition des bordereaux de fin de periode.
- Les tarifs sont un **sous-ecran d'un article**: liste des prix de l'article selon les periodes, avec un ecran de **modification du prix par periode**.
- La liste des tarifs d'un article est triee par date de periode **decroissante** (plus recentes en premier).
- Le prix article est unique en **TTC** (pas de prix standard/reseau) et peut etre formule soit en **TTC direct**, soit via **prix fournisseur HT + marge (%)**.
- Le mode de calcul d'un tarif est persiste en plus du total TTC (par periode et par article).
- La modification de prix cible une **periode** (pas de date d'effet) et ne comporte pas de champ **motif de modification**.
- Le domaine periodes expose le **listing avec actions** (ouvrir, fermer, definir en cours, reprise de tarifs) et un ecran **detail periode**.

Ce dossier est la référence d'entrée pour les futures tâches d'implémentation frontend.
