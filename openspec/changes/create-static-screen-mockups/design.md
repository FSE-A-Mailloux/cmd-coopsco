## Context

Le projet démarre une réécriture complète de l’application à partir des spécifications OpenSpec existantes.  
Avant d’implémenter les écrans dynamiques, l’équipe veut valider les besoins fonctionnels via des maquettes statiques consultables dans un espace dédié, séparé du code applicatif principal.

Contraintes principales:
- les maquettes doivent vivre dans un dossier dédié;
- la navigation de consultation doit être simple: liste des écrans à gauche, aperçu à droite;
- le rendu doit rester neutre et lisible pour faciliter les arbitrages métier.

## Goals / Non-Goals

**Goals:**
- Créer un référentiel visuel statique pour les écrans de l’application.
- Structurer ce référentiel dans un dossier dédié (`maquettes/`) afin d’éviter le couplage avec l’implémentation finale.
- Offrir une navigation de revue rapide pour les ateliers fonctionnels (sélection écran → aperçu).
- Harmoniser une base de style visuel neutre pour tous les écrans maquettes.

**Non-Goals:**
- Développer les workflows métier réels ou les appels backend.
- Finaliser un design system complet.
- Décider les composants techniques définitifs de l’application cible.

## Decisions

1. **Dossier maquettes dédié (`maquettes/`)**
   - **Décision**: isoler toutes les maquettes dans un dossier autonome.
   - **Rationale**: séparer clairement exploration UX et implémentation produit.
   - **Alternative considérée**: placer les maquettes dans le frontend cible.
   - **Pourquoi non retenue**: risque de confusion entre code de référence et code de production.

2. **Architecture “workbench” statique**
   - **Décision**: un écran conteneur unique avec deux zones: navigation gauche + aperçu droite.
   - **Rationale**: facilite la comparaison d’écrans et accélère les validations métiers.
   - **Alternative considérée**: une page indépendante par maquette.
   - **Pourquoi non retenue**: moins efficace pour naviguer rapidement entre nombreux écrans.

3. **Catalogue d’écrans piloté par manifest**
   - **Décision**: centraliser la liste des écrans (id, titre, catégorie, fichier d’aperçu) dans un manifest.
   - **Rationale**: simplifie l’ajout d’écrans et stabilise la navigation.
   - **Alternative considérée**: découverte automatique des fichiers.
   - **Pourquoi non retenue**: rend le tri fonctionnel et l’ordonnancement moins explicites.

4. **Style visuel neutre**
   - **Décision**: thème clair, contrastes modérés, palette sobre, composants lisibles.
   - **Rationale**: privilégier la compréhension fonctionnelle plutôt que l’effet “branding”.
   - **Alternative considérée**: style riche/proche du rendu final.
   - **Pourquoi non retenue**: augmenterait le coût d’itération et brouillerait les décisions métier.

5. **Regroupement par role et ecrans partages**
   - **Décision**: organiser la navigation maquettes par role (`famille`, `gestionnaire`, `admin`) avec possibilite d'un meme ecran dans plusieurs roles.
   - **Rationale**: garantir une lecture metier claire des droits et parcours par persona.
   - **Alternative considérée**: forcer un role unique par ecran.
   - **Pourquoi non retenue**: certains ecrans (creation/detail commande) sont communs a plusieurs roles.

6. **Parcours commandes famille explicite**
   - **Décision**: ajouter une maquette dediee "mes commandes" pour le role famille, et partager creation/detail avec le role gestionnaire.
   - **Rationale**: expliciter la portee "propre commandes" pour la famille tout en conservant les ecrans communs.
   - **Alternative considérée**: reutiliser strictement la meme liste commandes pour tous les roles.
   - **Pourquoi non retenue**: ne permet pas de valider visuellement la restriction de perimetre pour le role famille.

7. **Rattachement du referentiel au role gestionnaire**
   - **Décision**: rattacher periodes, articles, tarifs, stock et consolidation au domaine gestionnaire.
   - **Rationale**: aligner le regroupement des maquettes avec la responsabilite metier attendue.
   - **Alternative considérée**: maintenir ces ecrans dans le domaine admin.
   - **Pourquoi non retenue**: ne correspond pas au decoupage des responsabilites demande.

8. **Separation des ecrans articles**
   - **Décision**: separer la maquette articles en deux ecrans distincts: listing et creation.
   - **Rationale**: rendre explicite le parcours metier et faciliter les arbitrages UX.
   - **Alternative considérée**: fusionner listing et formulaire dans un seul ecran.
   - **Pourquoi non retenue**: ne permet pas de valider clairement les deux intentions d'usage.

9. **Tarifs comme sous-ecran d'article**
   - **Décision**: rattacher la consultation des tarifs a un article donne, avec ses prix sur plusieurs periodes.
   - **Rationale**: ancrer la lecture tarifaire dans le contexte produit et eviter une vue trop transverse.
   - **Alternative considérée**: listing global multi-articles des tarifs.
   - **Pourquoi non retenue**: ne correspond pas a l'exigence d'un sous-ecran article.

10. **Parcours tarifs article en deux ecrans (consultation + edition prix)**
   - **Décision**: separer le sous-ecran de consultation des tarifs d'un article et l'ecran de modification de prix sur la periode en cours.
   - **Rationale**: clarifier l'intention de consultation vs action d'edition.
   - **Alternative considérée**: edition inline dans le listing.
   - **Pourquoi non retenue**: surcharge la lecture et complique la validation fonctionnelle.

11. **Pilotage de periode et reprise de tarifs**
   - **Décision**: exposer des actions de periode (ouvrir, fermer, definir en cours), un ecran detail periode, et une action de reprise des tarifs d'une periode precedente vers la periode en cours.
   - **Rationale**: rendre visible le cycle de vie metier d'une periode et le mecanisme de reprise tarifaire.
   - **Alternative considérée**: ne montrer que la liste des periodes sans actions.
   - **Pourquoi non retenue**: insuffisant pour valider les operations attendues par le gestionnaire.

12. **Modele article explicite**
   - **Décision**: modeliser l'article avec trois attributs fonctionnels visibles: code de reference, libelle, marque.
   - **Rationale**: aligner la maquette sur les donnees metier minimales attendues.
   - **Alternative considérée**: conserver des champs generiques (famille/description) dans la fiche principale.
   - **Pourquoi non retenue**: ne correspond pas au cadrage metier demande pour l'article.

13. **Double disponibilite article (commandes vs stock)**
   - **Décision**: separer la disponibilite d'un article pour la creation de commande et pour la gestion de stock.
   - **Rationale**: permettre des regles de visibilite distinctes selon le contexte metier.
   - **Alternative considérée**: une disponibilite unique pour tous les usages.
   - **Pourquoi non retenue**: ne couvre pas les besoins de filtrage differencies entre commandes et inventaire.

14. **Tri chronologique des tarifs article**
   - **Décision**: trier les lignes de tarifs d'un article par date de periode decroissante (plus recentes en premier).
   - **Rationale**: faciliter la lecture immediate des prix applicables recents.
   - **Alternative considérée**: tri croissant ou tri alphabetique par libelle de periode.
   - **Pourquoi non retenue**: moins lisible pour les usages operationnels centres sur la periode active/recente.

15. **Prix unique TTC par periode**
   - **Décision**: ne pas distinguer prix standard et prix reseau; conserver un prix TTC unique par article et par periode.
   - **Rationale**: simplifier la lecture metier et aligner la maquette sur la regle tarifaire cible.
   - **Alternative considérée**: maintenir deux colonnes de prix.
   - **Pourquoi non retenue**: contraire au besoin exprime.

16. **Double mode de formulation du prix**
   - **Décision**: permettre deux modes de saisie du prix: TTC direct, ou HT fournisseur + marge (%) avec calcul TTC.
   - **Rationale**: couvrir les deux pratiques de gestion tarifaire attendues.
   - **Alternative considérée**: n'autoriser qu'un seul mode de saisie.
   - **Pourquoi non retenue**: ne couvre pas les usages reels du gestionnaire.

17. **Ciblage par periode plutot que date d'effet**
   - **Décision**: dans l'edition tarifaire, selectionner une periode cible au lieu d'une date d'effet.
   - **Rationale**: coherer avec le modele metier pilote par periodes.
   - **Alternative considérée**: conserver une date d'effet libre.
   - **Pourquoi non retenue**: ajoute une granularite non souhaitee.

18. **Edition tarifaire sans motif obligatoire**
   - **Décision**: ne pas afficher de champ "motif de modification" dans l'ecran de modification de prix.
   - **Rationale**: simplifier la saisie et rester aligne avec le besoin exprime.
   - **Alternative considérée**: conserver un motif texte libre.
   - **Pourquoi non retenue**: champ non attendu dans le parcours cible.

19. **Persistance du mode de calcul tarifaire**
   - **Décision**: persister, pour chaque article et periode, le mode de calcul choisi (TTC direct ou HT fournisseur + marge) en plus du total TTC.
   - **Rationale**: conserver la tracabilite de la methode de calcul qui a produit le prix TTC.
   - **Alternative considérée**: ne persister que le total TTC.
   - **Pourquoi non retenue**: ne permet pas de reconstituer la logique de construction du prix.

20. **Creation de compte explicite pour le role famille**
   - **Décision**: ajouter un ecran dedie "Creation de compte" dans les parcours d'entree famille.
   - **Rationale**: couvrir completement l'onboarding famille en complement de connexion, activation et recuperation d'acces.
   - **Alternative considérée**: deduire la creation de compte via l'ecran d'activation uniquement.
   - **Pourquoi non retenue**: ne permet pas de valider un parcours de creation autonome.

21. **Referentiel unique des statuts de commande**
   - **Décision**: normaliser les maquettes commandes sur cinq statuts: Brouillon, En attente de paiement, Paiement partiel, Paiement en cours, Confirmee.
   - **Rationale**: aligner les vues de suivi commande avec le cycle de validation/paiement attendu.
   - **Alternative considérée**: conserver des statuts heterogenes (Validee, Livree, En attente).
   - **Pourquoi non retenue**: ambiguite metier et incoherence entre ecrans.

22. **Parcours d'encaissement gestionnaire dedie**
   - **Décision**: separer l'encaissement en un parcours complet (recherche commande + saisie de paiements multiples) incluant cheque et espece.
   - **Rationale**: rendre explicite le travail operationnel de saisie et de rapprochement des paiements hors CB.
   - **Alternative considérée**: ajouter un simple bouton de paiement dans le detail commande.
   - **Pourquoi non retenue**: insuffisant pour couvrir la recherche, le multi-paiement et les donnees cheque necessaires aux bordereaux.

23. **Formulaire commande famille aligne sur la liste fournitures college**
   - **Décision**: remodeler la creation de commande autour des fournitures de reference par niveau scolaire, avec saisie des enfants concernes (nom, prenom, niveau).
   - **Rationale**: coller au parcours reel des familles qui commandent a partir d'une liste predefinie par l'association ou le college.
   - **Alternative considérée**: conserver un formulaire commande generique de type B2B.
   - **Pourquoi non retenue**: ne correspond pas au contexte metier des commandes familles.

24. **Cotisation configuree par periode avec grille degressive**
   - **Décision**: configurer la cotisation au niveau de chaque periode avec trois tranches explicites: 1er enfant, 2e enfant, 3e et suivants.
   - **Rationale**: rendre visible le reglage metier de la cotisation et sa degressivite.
   - **Alternative considérée**: cotisation fixe unique, hors contexte de periode.
   - **Pourquoi non retenue**: ne couvre pas les regles de tarification demandees.

25. **Cotisation integree au total commande et visible des la saisie enfants**
   - **Décision**: afficher le cout de cotisation au moment du choix du nombre d'enfants et integrer ce montant dans le total de commande.
   - **Rationale**: donner une vision immediate du cout total et eviter les surprises en fin de saisie.
   - **Alternative considérée**: calculer la cotisation uniquement a la confirmation finale.
   - **Pourquoi non retenue**: manque de transparence pendant la saisie.

26. **Creation commande famille decoupee en formulaire multi-etapes**
   - **Décision**: structurer la creation de commande en 3 etapes: (1) validation email/telephone, (2) saisie nombre d'enfants et identites (nom, prenom, niveau) avec affichage cotisation et pre-remplissage possible depuis la commande precedente, (3) selection des quantites sur toute la liste de fournitures avec recommandations par niveau selon les enfants saisis.
   - **Rationale**: reduire la complexite de saisie et suivre le parcours reel attendu pour les familles.
   - **Alternative considérée**: conserver un ecran unique avec tous les champs.
   - **Pourquoi non retenue**: lisibilite insuffisante et parcours trop dense.

27. **Etape 2 orientee formulaire par enfant**
   - **Décision**: renommer l'etape 2 en "Enfants concernes par la commande" et presenter les informations enfants sous forme de zones de saisie dediees par enfant (nom, prenom, niveau).
   - **Rationale**: rendre l'etape plus explicite et plus proche d'une saisie reelle.
   - **Alternative considérée**: conserver un tableau de lecture des enfants.
   - **Pourquoi non retenue**: manque de clarte sur l'action attendue de saisie.

28. **Etape 3 representative d'une liste longue de fournitures**
   - **Décision**: afficher des champs de saisie de quantite sur les lignes de fournitures et expliciter le volume courant (35 a 40 references).
   - **Rationale**: mieux representer la charge de saisie reelle des familles.
   - **Alternative considérée**: garder un echantillon court sans champs de saisie.
   - **Pourquoi non retenue**: maquette insuffisamment representative du parcours reel.

29. **Ajustements de l'etape 2 enfants (indication, limite et contexte periode)**
   - **Décision**: supprimer le bouton de pre-remplissage au profit d'un texte indicatif, limiter le nombre d'enfants a 8 maximum, et contextualiser le champ niveau avec la mention de la periode en cours.
   - **Rationale**: clarifier l'interface et coller aux contraintes metier exprimees pour la saisie enfants.
   - **Alternative considérée**: conserver un bouton explicite et un libelle niveau generique.
   - **Pourquoi non retenue**: ajoute une action non souhaitee et manque de contexte sur la periode.

30. **Navigation explicite a l'etape 2**
   - **Décision**: ajouter, dans l'etape 2, des boutons de passage vers l'etape precedente et l'etape suivante.
   - **Rationale**: rendre le parcours multi-etapes plus lisible et actionnable.
   - **Alternative considérée**: navigation implicite uniquement par progression globale.
   - **Pourquoi non retenue**: manque de clarte sur le passage entre etapes.

31. **Saisie multi-articles non exclusive par fourniture**
   - **Décision**: remplacer le choix d'article via liste deroulante unique par une saisie de quantite par article correspondant, permettant de commander plusieurs references pour une meme fourniture.
   - **Rationale**: couvrir le besoin de repartition des quantites sur plusieurs articles (ex: 2 de A + 3 de B).
   - **Alternative considérée**: conserver une liste deroulante avec un seul article selectionnable.
   - **Pourquoi non retenue**: ne permet pas de commander simultanement plusieurs articles correspondants.

32. **Cas majoritaire mono-reference avec exceptions multi-references**
   - **Décision**: representer explicitement que la majorite des fournitures ont une seule reference article, et ne conserver des blocs multi-references que pour quelques fournitures specifiques.
   - **Rationale**: coller a la realite metier des listes fournitures (majorite simple, minorite composee).
   - **Alternative considérée**: modeliser toutes les fournitures comme multi-references.
   - **Pourquoi non retenue**: complexifie inutilement la saisie et ne reflete pas l'usage principal.

33. **Affichage explicite du prix TTC a l'etape 3**
   - **Décision**: afficher le prix TTC pour chaque article correspondant dans l'etape 3, en plus du champ de quantite.
   - **Rationale**: permettre a la famille d'arbitrer ses choix de quantites avec la visibilite prix.
   - **Alternative considérée**: n'afficher que les quantites sans prix.
   - **Pourquoi non retenue**: informations insuffisantes pour une decision de commande.

34. **Presentation responsive concise de l'etape 3**
   - **Décision**: proposer une vue desktop tabulaire complete et une vue telephone concise en cartes, avec les memes informations essentielles (recommandations, prix TTC, quantites).
   - **Rationale**: conserver la lisibilite sur petit ecran sans perdre la capacite de saisie.
   - **Alternative considérée**: garder une seule table large sur tous les formats.
   - **Pourquoi non retenue**: lecture et saisie degradees sur mobile.

35. **Ajout d'une etape 4 de validation commande**
   - **Décision**: ajouter une quatrieme etape dediee a la validation finale, affichant uniquement les articles effectivement choisis, avec PU TTC, total par ligne et total global.
   - **Rationale**: clarifier la confirmation avant envoi et separer saisie (etape 3) et validation finale.
   - **Alternative considérée**: conserver le recapitulatif dans l'etape 3.
   - **Pourquoi non retenue**: melange des intentions de saisie et de confirmation.

36. **Exemples multi-references avec recommandations non perturbantes**
   - **Décision**: dans les exemples multi-references (regle plate, surligneurs), fixer les quantites recommandees a 1 pour chaque niveau.
   - **Rationale**: eviter des valeurs de demonstration trompeuses et maintenir une lecture simple.
   - **Alternative considérée**: conserver des recommandations elevees dans les exemples.
   - **Pourquoi non retenue**: perception confuse des besoins reels par niveau.

37. **Coherence visuelle nombre d'enfants / cartes affichees**
   - **Décision**: lorsque deux cartes enfants sont affichees a l'etape 2, la valeur `Nombre d'enfants` est preselectionnee a 2.
   - **Rationale**: supprimer l'incoherence visuelle entre la valeur selectionnee et le formulaire affiche.
   - **Alternative considérée**: laisser la valeur a 1 avec deux cartes visibles.
   - **Pourquoi non retenue**: comprehension perturbee pour l'utilisateur.

38. **Lignes detail commande alignees avec le parcours famille**
   - **Décision**: remplacer les lignes generiques du detail commande par des lignes de fournitures scolaires realistes, coherentes avec les articles choisis dans la creation de commande famille.
   - **Rationale**: renforcer la continuite visuelle entre creation, validation et consultation de commande.
   - **Alternative considérée**: conserver des lignes B2B agregees et peu representatives.
   - **Pourquoi non retenue**: incoherence metier avec le parcours famille.

39. **Ajout d'une etape 5 pour le mode de paiement**
   - **Décision**: ajouter une cinquieme etape `Mode de paiement` apres la validation de commande, avec un choix entre CB (HelloAsso), cheque et espece.
   - **Rationale**: expliciter le parcours de reglement famille et les consignes operationnelles avant confirmation finale.
   - **Alternative considérée**: conserver le choix de paiement implicite ou externe a la creation.
   - **Pourquoi non retenue**: manque de clarte utilisateur et absence des notices metier attendues.

## Risks / Trade-offs

- **[Risque]** Les parties prenantes confondent maquette statique et comportement final.  
  **→ Mitigation**: afficher explicitement un badge “Prévisualisation statique”.

- **[Risque]** Le nombre d’écrans augmente vite et dégrade la lisibilité de la liste gauche.  
  **→ Mitigation**: structurer par catégories fonctionnelles et ordre explicite dans le manifest.

- **[Risque]** Divergence entre maquettes validées et futur développement frontend.  
  **→ Mitigation**: imposer la maquette comme référence d’entrée des user stories d’implémentation.

- **[Trade-off]** Travail en double (maquette puis implémentation).  
  **→ Justification**: coût assumé pour réduire les régressions fonctionnelles pendant la réécriture.
