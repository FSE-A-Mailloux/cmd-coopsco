## 1. Cadrage de l’espace maquettes

- [x] 1.1 Créer le dossier dédié `maquettes/` et définir son arborescence (workbench, écrans, assets, manifest).
- [x] 1.2 Définir la convention de nommage des écrans maquettes (id, catégorie, titre, fichier source).
- [x] 1.3 Préparer le manifest des écrans pour alimenter la navigation gauche.

## 2. Construction du workbench statique

- [x] 2.1 Implémenter la structure de page du workbench avec liste des écrans à gauche et zone d’aperçu à droite.
- [x] 2.2 Brancher la sélection d’un écran depuis la liste vers le rendu de son aperçu statique.
- [x] 2.3 Ajouter l’état par défaut et la gestion des cas d’écran absent/invalide dans l’aperçu.

## 3. Base de style neutre

- [x] 3.1 Définir une base visuelle neutre (couleurs, typographie, espacements, cartes, boutons) commune à toutes les maquettes.
- [x] 3.2 Appliquer cette base sur le shell maquette et les premiers écrans prioritaires.
- [x] 3.3 Ajouter un indicateur visible “Prévisualisation statique” pour éviter la confusion avec le produit final.

## 4. Couverture fonctionnelle initiale des écrans

- [x] 4.1 Produire les maquettes des parcours d’entrée (connexion, activation, récupération d’accès).
- [x] 4.2 Produire les maquettes des parcours cœur métier (liste commandes, création/édition commande, détail commande).
- [x] 4.3 Produire les maquettes d’administration (utilisateurs, roles/permissions) et du domaine gestionnaire (periodes, articles, tarifs, stock, consolidation).

## 5. Validation et préparation de la phase d’implémentation

- [x] 5.1 Vérifier que tous les écrans du manifest sont accessibles dans la navigation gauche.
- [x] 5.2 Vérifier que chaque sélection affiche un aperçu statique sans dépendance backend.
- [x] 5.3 Documenter l’utilisation du dossier `maquettes/` comme référence d’entrée pour les futures tâches d’implémentation frontend.

## 6. Ajustements de parcours par role (remarques ateliers)

- [x] 6.1 Mettre a jour les specs pour expliciter le regroupement des ecrans par role et la possibilite d'ecrans partages multi-roles.
- [x] 6.2 Ajouter la couverture famille du parcours commandes (liste personnelle + acces creation/detail) dans le manifest et les maquettes.

## 7. Ajustements domaine gestionnaire et articles (remarques ateliers)

- [x] 7.1 Rattacher periodes, articles, tarifs, stock et consolidation au role gestionnaire dans les specs et le manifest.
- [x] 7.2 Separer articles en deux maquettes distinctes (listing et creation) avec ecrans dedies dans le manifest.

## 8. Ajustements periodes et tarifs (remarques ateliers)

- [x] 8.1 Mettre a jour les specs pour couvrir tarifs par periode, edition prix periode en cours, detail periode, reprise des tarifs et actions d'etat de periode.
- [x] 8.2 Mettre a jour les maquettes et le manifest pour ajouter les ecrans/actions de pilotage periodes-tarifs demandes.

## 9. Ajustements structure et disponibilite des articles (remarques ateliers)

- [x] 9.1 Mettre a jour les specs pour definir la structure article (code de reference, libelle, marque) et les regles de disponibilite commandes/stock.
- [x] 9.2 Mettre a jour les maquettes articles/stock pour refleter la structure article et les filtres de visibilite selon disponibilite.

## 10. Ajustement sous-ecran tarifs article (remarque atelier)

- [x] 10.1 Mettre a jour les specs pour positionner la liste des tarifs comme sous-ecran d'un article.
- [x] 10.2 Mettre a jour le manifest et les maquettes pour refleter le parcours article -> tarifs -> edition prix.

## 11. Ajustement tri des tarifs article (remarque atelier)

- [x] 11.1 Mettre a jour les specs pour imposer un tri des periodes par date decroissante dans la liste des tarifs d'un article.
- [x] 11.2 Mettre a jour la maquette du sous-ecran tarifs pour afficher les periodes les plus recentes en premier.

## 12. Ajustements mode de prix et ciblage periode (remarques atelier)

- [x] 12.1 Mettre a jour les specs pour formaliser le prix unique TTC, les deux modes de formulation (TTC direct ou HT fournisseur + marge) et le choix de periode sans date d'effet.
- [x] 12.2 Mettre a jour les maquettes tarifs (listing + edition) pour supprimer standard/reseau, afficher les modes de formulation et cibler une periode.

## 13. Ajustement edition tarif sans motif (remarque atelier)

- [x] 13.1 Mettre a jour les specs pour expliciter l'absence de champ motif dans l'edition de prix.
- [x] 13.2 Mettre a jour la maquette d'edition de prix pour supprimer le champ motif de modification.

## 14. Ajustement persistance du mode de calcul (remarque atelier)

- [x] 14.1 Mettre a jour les specs pour expliciter que le mode de calcul d'un tarif est persiste en plus du total TTC.
- [x] 14.2 Mettre a jour les maquettes tarifs (listing + edition) pour rendre visible la persistance du mode de calcul avec le total TTC.

## 15. Ajustements creation compte famille, statuts commande et encaissement gestionnaire (remarques atelier)

- [x] 15.1 Mettre a jour les specs pour ajouter l'ecran de creation de compte famille et formaliser le referentiel des statuts commande.
- [x] 15.2 Mettre a jour les maquettes commandes pour utiliser exclusivement les statuts Brouillon, En attente de paiement, Paiement partiel, Paiement en cours et Confirmee.
- [x] 15.3 Ajouter au manifest et aux maquettes le parcours d'encaissement gestionnaire (recherche commande + saisie de paiements multiples cheque/espece).
- [x] 15.4 Ajouter la maquette de creation de compte dans le parcours d'entree famille et documenter ces nouveaux parcours dans le README.

## 16. Ajustements commande famille fournitures et cotisation (remarques atelier)

- [x] 16.1 Mettre a jour les specs pour decrire le parcours commande famille (nombre d'enfants, identite/niveau des enfants, fournitures de reference avec quantites recommandees et articles correspondants multiples).
- [x] 16.2 Mettre a jour la maquette de creation de commande pour afficher la saisie des enfants, la liste des fournitures par niveau et le choix des quantites souhaitees.
- [x] 16.3 Mettre a jour les maquettes periodes pour afficher la configuration de la cotisation par periode avec degressivite (1er, 2e, 3e et suivants).
- [x] 16.4 Mettre a jour les maquettes commandes pour integrer la cotisation dans le total et afficher son cout des le choix du nombre d'enfants.

## 17. Ajustement formulaire commande famille multi-etapes (remarque atelier)

- [x] 17.1 Mettre a jour les specs pour formaliser le parcours en 3 etapes (validation contact, saisie enfants, selection fournitures) et l'absence de niveau principal.
- [x] 17.2 Mettre a jour la maquette de creation de commande pour afficher l'etape validation email/telephone.
- [x] 17.3 Mettre a jour la maquette de creation de commande pour afficher l'etape enfants avec pre-remplissage possible depuis la commande precedente et cout cotisation.
- [x] 17.4 Mettre a jour la maquette de creation de commande pour afficher l'etape fournitures complete avec quantites recommandees par niveau selon les enfants.

## 18. Ajustement clarte et representativite etapes 2 et 3 commande famille (remarques atelier)

- [x] 18.1 Mettre a jour les specs pour expliciter l'etape 2 "Enfants concernes par la commande" avec des zones de saisie par enfant.
- [x] 18.2 Mettre a jour la maquette de creation de commande pour presenter l'etape 2 comme un formulaire de saisie par enfant.
- [x] 18.3 Mettre a jour les specs et la maquette de l'etape 3 pour integrer des champs de saisie de quantite et representer une liste longue de 35 a 40 references.

## 19. Ajustement details etape 2 enfants (remarque atelier)

- [x] 19.1 Mettre a jour les specs pour expliciter l'absence de bouton de pre-remplissage, la limite de 8 enfants et le libelle niveau contextualise par periode.
- [x] 19.2 Mettre a jour la maquette etape 2 pour remplacer le bouton de pre-remplissage par un texte indicatif.
- [x] 19.3 Mettre a jour la maquette etape 2 pour limiter le nombre d'enfants a 8 et afficher le champ "Niveau (sur la periode ...)".

## 20. Ajustement navigation etape 2 et choix multi-articles etape 3 (remarques atelier)

- [x] 20.1 Mettre a jour les specs pour expliciter la presence des boutons etape precedente/suivante sur l'etape 2.
- [x] 20.2 Mettre a jour la maquette etape 2 pour afficher les boutons de passage a l'etape precedente et a l'etape suivante.
- [x] 20.3 Mettre a jour les specs et la maquette etape 3 pour remplacer la liste deroulante d'article par une saisie de quantite par article correspondant (choix multiple possible).

## 21. Ajustement repartition mono/multi references a l'etape 3 (remarque atelier)

- [x] 21.1 Mettre a jour les specs pour expliciter que la majorite des fournitures ont une reference unique et que seules certaines sont multi-references.
- [x] 21.2 Mettre a jour la maquette etape 3 pour illustrer des cas majoritaires mono-reference et des cas multi-references (Regle plate 30cm, Lot de surligneurs de couleurs).

## 22. Ajustement prix TTC et responsive etape 3 (remarque atelier)

- [x] 22.1 Mettre a jour les specs pour expliciter l'affichage du prix TTC des articles sur l'etape 3 et une presentation concise en vue telephone.
- [x] 22.2 Mettre a jour la maquette etape 3 pour afficher le prix TTC par article correspondant.
- [x] 22.3 Mettre a jour la maquette etape 3 pour proposer une version desktop complete et une version mobile concise responsive.

## 23. Ajustement etape 4 validation et recommandations exemples (remarques atelier)

- [x] 23.1 Mettre a jour les specs pour ajouter l'etape 4 de validation avec recapitulatif des seuls articles choisis (PU TTC, total ligne, total commande).
- [x] 23.2 Mettre a jour la maquette de creation de commande pour ajouter l'etape 4 et repositionner la validation finale.
- [x] 23.3 Mettre a jour la maquette et les specs pour fixer a 1 les quantites recommandees des exemples multi-references (regle plate, surligneurs) pour les deux niveaux.

## 24. Ajustement coherence nombre d'enfants et cartes etape 2 (remarque atelier)

- [x] 24.1 Mettre a jour les specs pour expliciter la coherence entre la valeur "Nombre d'enfants" et le nombre de cartes enfants affichees.
- [x] 24.2 Mettre a jour la maquette etape 2 pour preselectionner 2 enfants lorsque deux cartes enfants sont affichees.

## 25. Ajustement realisme des lignes detail commande (remarque atelier)

- [x] 25.1 Mettre a jour les specs pour expliciter que l'ecran Detail commande reprend des lignes de fournitures realistes issues du parcours famille.
- [x] 25.2 Mettre a jour la maquette Detail commande pour aligner les lignes articles, les quantites et les montants avec la creation/validation de commande famille.

## 26. Ajout etape 5 mode de paiement commande famille (remarque atelier)

- [x] 26.1 Mettre a jour les specs pour ajouter l'etape 5 de choix du mode de paiement (CB HelloAsso, cheque, espece) et les notices explicatives cheque/espece.
- [x] 26.2 Mettre a jour la maquette de creation de commande pour passer en 5 etapes, ajouter l'etape mode de paiement et repositionner la confirmation finale sur cette etape.

## 27. Ajustement shell de reference - commandes par statut (remarque atelier)

- [x] 27.1 Mettre a jour les specs pour imposer l'affichage du nombre de commandes sur la periode en cours par statut dans l'ecran Shell de reference.
- [x] 27.2 Mettre a jour la maquette Shell de reference pour remplacer "Demandes en attente" et "Alertes stock" par la repartition des commandes par statut.

## 28. Ajustement statut commande annulee et regle d'annulation (remarque atelier)

- [x] 28.1 Mettre a jour les specs pour ajouter le statut `Annulee` et formaliser que l'annulation est possible uniquement avant tout paiement enregistre.
- [x] 28.2 Mettre a jour les maquettes commandes (liste, detail, shell) pour afficher le statut `Annulee`, l'action d'annulation et la regle de non-annulation apres paiement.

## 29. Ajustement shell de reference - periode en cours et etat ouvert/ferme (remarque atelier)

- [x] 29.1 Mettre a jour les specs pour imposer l'affichage de la periode en cours et de son etat (`Ouverte`/`Fermee`) dans l'ecran Shell de reference.
- [x] 29.2 Mettre a jour la maquette Shell de reference pour afficher explicitement la periode en cours et son etat ouvert/ferme.

## 30. Ajustement shell de reference - metriques periode en cours et recap historique (remarque atelier)

- [x] 30.1 Mettre a jour les specs pour remplacer la zone generique de contenu metier par des metriques pertinentes de la periode en cours et un recapitulatif des annees passees.
- [x] 30.2 Mettre a jour la maquette Shell de reference pour afficher un tableau de bord periode en cours (CA, nb commandes, panier moyen, statuts) et une vue historique annuelle.

## 31. Ajustement valeurs shell de reference - panier moyen realiste (remarque atelier)

- [x] 31.1 Mettre a jour les specs pour expliciter un ordre de grandeur realiste du panier moyen (autour de 50 EUR) dans le shell.
- [x] 31.2 Mettre a jour la maquette Shell de reference pour aligner le panier moyen et les valeurs de recapitulatif annuel sur cet ordre de grandeur.

## 32. Ajustement recap shell - periodes passees au lieu d'annees scolaires (remarque atelier)

- [x] 32.1 Mettre a jour les specs pour demander un recapitulatif des periodes passees (et non des annees scolaires) dans le shell.
- [x] 32.2 Mettre a jour la maquette Shell de reference pour renommer la colonne historique en `Periode` et afficher des libelles de periodes.

## 33. Ajustement liste des commandes gestionnaire - recherche multicriteres et filtre periode (remarque atelier)

- [x] 33.1 Mettre a jour les specs pour imposer, sur la liste des commandes gestionnaire, une recherche multicriteres (email, nom/prenom parent, nom/prenom enfant) et un filtre periode.
- [x] 33.2 Mettre a jour la maquette Liste des commandes (gestionnaire) pour afficher ces filtres avec la periode en cours preselectionnee par defaut.

## 34. Ajustement filtres liste commandes gestionnaire - champ unique et statuts multi-selection (remarque atelier)

- [x] 34.1 Mettre a jour les specs pour remplacer la recherche multicriteres par plusieurs champs par un champ unique interrogeant email/nom/prenom parent/enfant.
- [x] 34.2 Mettre a jour la maquette Liste des commandes (gestionnaire) pour afficher ce champ unique et ajouter un filtre statuts en selection multiple.
