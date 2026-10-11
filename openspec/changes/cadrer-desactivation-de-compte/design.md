## Context

Voir `proposal.md` pour la motivation et `specs/user-account-lifecycle-management/spec.md` pour les comportements attendus. Le contrôleur legacy `desabo` appelle `userManage` avec le contexte `desabo`; le traitement correspondant met `comGroupe_uti` à zéro. Le corpus documentaire décrit UC-AUTH-8 différemment en parlant de désactivation de compte. La suppression administrative et l’anonymisation sont déjà couvertes par la capacité de cycle de vie utilisateur.

## Goals / Non-Goals

**Goals:**
- Séparer les opérations et les interfaces de suppression de compte et de désinscription aux communications.
- Réutiliser les mécanismes existants d’authentification, de contrôle d’accès et de cycle de vie plutôt que créer un second mécanisme de gestion de compte.
- Préserver l’historique des commandes tout en empêchant son rattachement à une personne identifiée après suppression.
- Aligner les sources de cadrage et les maquettes sur les comportements confirmés.

**Non-Goals:**
- Modifier les règles de désinscription aux communications au-delà de clarifier et préserver leur effet actuel.
- Supprimer les commandes historiques ou rendre le compte réactivable.
- Redéfinir la suppression administrative des comptes.

## Decisions

- **Garder le désabonnement comme une opération de préférence de communication.** Le traitement legacy observé ne fait que désactiver `comGroupe_uti`; il reste séparé du nouveau parcours de suppression. Le corpus UC-AUTH-8 devra être corrigé pour refléter cette divergence plutôt que de perpétuer l’affirmation que ce traitement désactive le compte.
- **Faire de la suppression une action authentifiée du titulaire dans le parcours de compte.** Le serveur détermine le compte depuis l’identité de session, vérifie le mot de passe courant, puis n’exécute la suppression qu’après la confirmation explicite de l’écran récapitulatif. Un identifiant de compte fourni par le client ne constitue pas une preuve d’autorisation.
- **Traiter l’anonymisation et la suppression des accès comme une seule opération cohérente.** Réutiliser les règles de suppression/anonymisation du cycle de vie existant; conserver les commandes et leurs données métier nécessaires, mais retirer les données qui identifient le titulaire. Révoquer également les accès actifs au compte. Les détails des champs à anonymiser doivent être déterminés en traçant les relations de données pendant l’implémentation.
- **Concevoir des maquettes distinctes pour l’action de compte et l’action de communication.** La maquette de suppression couvre le récapitulatif, la saisie du mot de passe, la confirmation explicite et le résultat; le parcours de désabonnement reste clairement présenté comme une préférence de communication.

## Spécification technique

Cette section formalise le contrat d’implémentation cible à partir des usages de données observés dans le legacy. Elle ne prescrit ni framework ni noms d’API propres à une future architecture. Les contrôles de schéma et les relations de données réels devront être confirmés contre le modèle de données de l’application cible avant toute migration.

### Contrat de sécurité et d’opération

1. Exposer une opération de suppression dédiée et autorisée uniquement pour une session utilisateur active. L’identité cible provient de la session côté serveur; aucun identifiant de compte fourni par le client ne peut remplacer ce lien d’identité.
2. N’accepter l’opération qu’après validation serveur du mot de passe courant avec le mécanisme de vérification d’identifiants de la solution cible et réception d’une confirmation explicite. Ne jamais journaliser ni persister le mot de passe soumis.
3. En cas d’absence de session, d’identifiants invalides, de confirmation absente ou d’erreur de traitement, retourner une erreur fonctionnelle/sécurité explicite sans mutation persistante.
4. Dans une transaction cohérente : anonymiser les commandes, supprimer les associations d’accès et journaux identifiés ci-dessous, révoquer toutes les sessions, puis supprimer l’enregistrement du compte. Toute erreur avant validation de transaction annule l’ensemble.
5. Après validation, invalider aussi le jeton de la session courante et terminer le parcours sur un état non authentifié; ne pas retourner d’informations personnelles du compte supprimé.

### Inventaire de données observé et traitement cible

| Donnée ou relation observée | Usage observé dans le legacy | Traitement technique cible |
| --- | --- | --- |
| Compte (`utilisateur`: identifiant, mot de passe, courriel, nom, prénom, code de validation, préférence de communication) | Authentification, contact et profil | Supprimer l’enregistrement après traitement des dépendances; ne pas conserver ses identifiants. |
| Sessions (`token`, rattaché par `id_uti`) | Authentification et accès | Révoquer tous les jetons du compte, y compris la session demandeuse. |
| Affectations (`gro_uti`) | Rôles et groupes | Supprimer toutes les affectations du compte. |
| Journal d’actions (`actionhisto`, auteur `id_uti`) | Historique d’activité associé à l’utilisateur | Supprimer les événements rattachés au compte, conformément au traitement de suppression déjà observé; ne pas les conserver sous un compte ré-identifiable. |
| Commande (`fse_commande`: référence `id_uti`, courriel, téléphone, nom et prénom du responsable) | Coordonnées du responsable et lien avec le compte | Conserver la commande et ses données de suivi métier; mettre la référence utilisateur à vide/null et anonymiser courriel, téléphone, nom et prénom. |
| Enfants d’une commande (`fse_cmdenf`: nom et prénom; classe) | Bénéficiaires liés à la commande | Conserver la structure métier de la commande mais anonymiser noms et prénoms; vérifier si d’autres attributs ou pièces jointes permettent une ré-identification avant mise en production. |
| Lignes et règlements (`fse_artcmd`, `fse_cmdrglt`, utilisés par les parcours de commande) | Détail des articles, montants et paiements | Conserver les éléments financiers et opérationnels nécessaires à l’historique; vérifier qu’ils ne contiennent pas de données personnelles directes ou de référence restaurable vers le compte. |
| Journal d’anomalies et documents dérivés | Une référence d’utilisateur apparaît dans l’écriture des anomalies; la persistance de documents/PDF n’est pas déterminable à partir du dépôt | Avant implémentation, déterminer leur durée de conservation et le traitement des références ou copies personnelles. Ils ne doivent pas annuler l’anonymisation des commandes conservées. |

Les valeurs d’anonymisation exactes doivent être neutres et non uniques (les exemples legacy `anonyme` et chaîne vide ne sont pas une obligation de stockage). La liste constitue un inventaire des références trouvées dans le code source, pas une preuve exhaustive des contraintes de la base ni des systèmes externes.

### Vérifications d’acceptation technique

- Session absente, mot de passe erroné, confirmation manquante ou cible différente de l’utilisateur connecté : erreur explicite et comparaison avant/après montrant qu’aucune donnée n’a changé.
- Succès : le compte n’est plus authentifiable; toutes ses sessions et affectations ont disparu; aucune commande n’est supprimée; les champs personnels de commande et d’enfant sont anonymisés; lignes, montants et paiements restent consultables.
- Erreur injectée à chaque étape de suppression : transaction annulée, compte et commandes dans leur état initial.
- Désinscription d’un courriel : seule la préférence de communications est modifiée; compte, sessions, affectations et commandes restent présents.
- Inspection des documents dérivés et des journaux : aucune copie ou référence conservée ne ré-expose les données personnelles supprimées.

## Risks / Trade-offs

- [Anonymisation incomplète dans des relations ou journaux secondaires] → Inventorier les références au compte et tester que les commandes conservées ne révèlent plus l’identité.
- [Écart entre documentation historique et comportement effectivement codé] → Mettre à jour UC-AUTH-8 et la matrice de couverture à partir du comportement vérifié du traitement `desabo`.
- [Suppression partiellement appliquée en cas d’échec] → Appliquer les changements de compte, d’accès et d’anonymisation de façon atomique lorsque le stockage le permet; vérifier l’absence d’état intermédiaire exploitable.
- [Le code legacy ne démontre pas les contraintes de schéma, les documents conservés ni les exigences légales applicables à chaque donnée] → Confirmer les relations et délais de conservation dans l’application cible avant d’implémenter ou déployer la suppression.
