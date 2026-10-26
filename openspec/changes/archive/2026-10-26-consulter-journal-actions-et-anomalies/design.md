## Context

Voir `proposal.md` pour la motivation. Le PRD FR-084/085 et l’arbitrage 5 de la matrice ouvrent les consultations aux gestionnaires ; l’utilisateur a confirmé le maintien de l’accès `Admin`. FR-082/083 concernent la production des traces, pas ce parcours de consultation.

Les specs actuelles normalisent les erreurs et les permissions mais ne décrivent aucun journal général. `user-role-administration-and-audit` protège séparément l’historique détaillé des rôles. Le workbench sait déjà classer un écran dans plusieurs groupes depuis `screens.json` et `screens.js`, sans backend.

Le design est nécessaire pour fixer les frontières entre consultation, audit protégé et représentation statique, sans confondre maquettes et contrôle d’accès réel.

## Goals / Non-Goals

**Goals:**
- Définir un contrat observable indépendant d’une technologie de collecte ou de stockage.
- Préparer deux maquettes cohérentes avec les listings et les manifestes existants.
- Assurer une traçabilité vérifiable PRD → specs → maquettes pour UC-HISTO-2/3.

**Non-Goals:**
- Réaliser une API, une collecte, une migration de traces ou une authentification dynamique.
- Étendre l’accès à l’historique détaillé des rôles réservé aux admins.
- Ajouter filtres, recherche, export, temps réel, politique de conservation ou actions de traitement des anomalies.
- Modifier le catalogue legacy des use-cases pour lui attribuer rétroactivement les règles cibles.

## Decisions

### 1. Une capacité dédiée à la consultation
Créer `application-journal-consultation` plutôt que placer les exigences métier dans le contrat de routage webservice. Les erreurs suivent la taxonomie existante sans la redéfinir ; les règles de production FR-082/083 restent inchangées. Les exigences de maquettes sont ajoutées à `frontend-reference-screen-flows`.
Alternative rejetée : étendre le journal des rôles à toutes les traces, ce qui mélangerait des périmètres d’accès différents.

### 2. Deux listes simples, sans mécanisme métier dynamique
Prévoir `maquettes/screens/suivi/suivi-journal-actions.html` et `maquettes/screens/suivi/suivi-anomalies.html`, catégorie « Historique et suivi », rôles `["gestionnaire", "admin"]`. Utiliser le style partagé et une présentation en cartes sur téléphone, conformément au choix de revue, puis en tableau sur desktop. Les deux présentations conservent les mêmes entrées, les quatre informations et l’ordre décroissant, sans débordement horizontal de la page.
Les variantes statiques des cinq états seront explicitement séparées et nommées ; aucune requête serveur ni fausse action de relance n’est nécessaire.
Alternative rejetée : un écran fusionné avec filtres et chargement simulé, plus complexe et susceptible de masquer la distinction actions/anomalies.

### 3. Distinguer contexte métier et données techniques brutes
Utiliser uniquement des exemples fictifs et minimisés (par exemple « Commandes — Modification — Statut mis à jour — date-heure »). Aucun secret ni renseignement personnel réel n’entre dans les maquettes. La cible exige une restitution textuelle inerte et une projection du contexte, pas une exposition du stockage brut.
Les détails des changements de rôle ne sont pas reproduits dans la vue gestionnaire. Aucun lien du journal général ne doit contourner la restriction de leur historique.
Alternative rejetée : afficher la trace brute, qui exposerait des informations sans utilité pour le gestionnaire.

### 4. Tracer la livraison réelle, pas la seule intention
À l’application, préciser dans le PRD FR-084/085 les deux rôles confirmés et les rattachements de la nouvelle capacité, sans réécrire les règles de production. Mettre à jour UC-HISTO-2/3 dans la matrice avec les deux capacités et l’écran correspondant uniquement après création effective des specs principales et maquettes.
Les deux manifestes doivent déclarer les mêmes entrées. Le workbench sert de validation locale et distante ; son catalogue n’est pas un mécanisme d’autorisation serveur.

## Risks / Trade-offs

- [Risque] Assimiler un prototype consultable par tous à une protection des traces → messages explicites et séparation entre contrat cible et maquette fictive.
- [Risque] Ouvrir indirectement l’audit des rôles aux gestionnaires → exclusion explicite de cet historique et absence de lien contournant ses règles.
- [Risque] Confondre absence de données et panne → variantes distinctes sans données de secours.
- [Compromis] Les filtres et la pagination ne sont pas spécifiés dans ce cadrage minimal → aucun volume ou comportement de chargement massif n’est promis ; toute extension nécessite un cadrage ultérieur.

## Migration Plan

Lors d’un apply explicite : créer les maquettes, compléter les deux manifestes et leur README, aligner le PRD, synchroniser les deltas puis actualiser la matrice sur les fichiers réellement présents. Vérifier les deux rôles et les cinq états pour chaque écran, puis les modes téléphone/desktop et la couverture structurelle.

Il n’y a pas de migration applicative ou de données. Un retour arrière retire uniquement les nouveaux écrans, leurs entrées de catalogue et les ajouts documentaires correspondants, sans toucher au legacy ni aux autres journaux.
