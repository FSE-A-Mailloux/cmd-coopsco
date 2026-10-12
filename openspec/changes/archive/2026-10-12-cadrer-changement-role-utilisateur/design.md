## Context

Voir `proposal.md` pour la motivation. Le changement introduit un nouveau contrat metier (`user-role-administration-and-audit`) et fait evoluer le parcours `frontend-reference-screen-flows` pour integrer le changement de role dans le listing utilisateurs avec recherche.  
La solution doit rester simple cote UX, garantir un controle d'habilitation strict cote serveur et conserver une trace exploitable de chaque changement de role.

## Goals / Non-Goals

**Goals:**
- Proposer un parcours admin unique, integre au listing utilisateurs, pour rechercher un compte et changer son role.
- Garantir que seul un acteur `Admin` peut executer et consulter les changements de role.
- Assurer une trace d'audit persistante, lisible et ordonnee pour consultation ulterieure.
- Cadrer les points techniques minimaux (contrat API, validation, persistance, consultation) pour une implementation coherente.

**Non-Goals:**
- Revoir l'ensemble de la matrice de permissions de l'application au-dela du changement de role.
- Introduire de nouveaux roles metier au-dela de `Famille`, `Gestionnaire`, `Admin`.
- Definir un moteur d'audit generique pour tous les domaines applicatifs.

## Decisions

### Decision 1 - Parcours unique integre au listing utilisateurs

Le changement de role est declenche depuis le listing utilisateurs (ligne ou action contextuelle) avec recherche en entree de parcours.  
Ce choix repond a la contrainte de simplicite et limite les allers-retours ecran.

Alternative ecartee: ecran dedie de changement de role. Cette option est plus lourde pour un besoin cible et ajoute une navigation supplementaire.

### Decision 2 - Autorisation appliquee en defense en profondeur

L'interface n'expose l'action qu'aux admins, mais la verification determinante reste cote serveur sur l'operation de mutation et sur la consultation d'historique.  
Le serveur refuse toute requete hors role `Admin` avec une erreur d'autorisation explicite.

Alternative ecartee: filtrage uniquement front. Cette option est insuffisante face aux appels directs d'API.

### Decision 3 - Referentiel de roles borne et validation centralisee

Le role cible est valide contre un enum canonique ferme: `Famille`, `Gestionnaire`, `Admin`.  
La meme definition est reutilisee pour la mutation et pour les retours d'historique afin d'eviter les divergences d'affichage.

Alternative ecartee: liste libre ou configurable sans gouvernance. Cette option augmente le risque d'incoherence et de regressions d'habilitation.

### Decision 4 - Changement de role et audit dans une meme transaction

Une operation de changement effectif met a jour le role et cree la trace d'audit dans une unite atomique.  
En cas d'echec de journalisation, le role n'est pas modifie.

Alternative ecartee: journalisation asynchrone apres mutation. Cette option cree un risque de changement non trace.

### Decision 5 - Historique consultable par API dediee orientee administration

La consultation des traces passe par une lecture dediee (filtre minimal par utilisateur cible, tri decroissant par horodatage, pagination).  
Le front admin peut ainsi afficher un historique lisible sans charger l'ensemble du journal.

Alternative ecartee: lecture brute du stockage d'audit cote interface. Cette option couple fortement la maquette aux details de persistance.

## Risks / Trade-offs

- **[Risque]** Surcharge du listing utilisateurs si recherche et actions sont mal agencees. **-> Mitigation:** garder une action de role concise, limiter les informations affichees par defaut, et reserver le detail aux panneaux secondaires.
- **[Risque]** Doubles soumissions provoquant des traces en doublon. **-> Mitigation:** desactiver l'action pendant soumission et traiter les requetes de maniere idempotente lorsque possible.
- **[Risque]** Volume d'audit croissant avec le temps. **-> Mitigation:** imposer pagination, index sur cible+date, et politique de retention a cadrer avant mise en production.
- **[Risque]** Erreurs de comprehension sur les droits reels. **-> Mitigation:** messages explicites en cas de refus et alignement strict des roles exposes UI/API.

## Migration Plan

1. Mettre a jour les maquettes du parcours utilisateurs (listing avec recherche, action de changement de role, consultation d'historique).
2. Introduire le contrat d'API de changement de role avec validation du role cible et controle `Admin`.
3. Ajouter la persistance de trace d'audit et son endpoint de consultation admin.
4. Verifier la coherence front/back via scenarios OpenSpec et validations de maquettes avant implementation complete.

## Open Questions

Aucune.
