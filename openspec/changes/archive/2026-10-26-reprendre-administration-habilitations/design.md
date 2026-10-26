## Context

Voir `proposal.md` pour la motivation. Les comptes et la récupération de mot de passe sont déjà cadrés dans `user-account-lifecycle-management`; le listing administrateur et le changement de rôle existant sont couverts par `frontend-reference-screen-flows`. Le présent changement ajoute une obligation de changement au prochain accès et son illustration statique, sans réintroduire de gestion de groupes ou de permissions.

## Goals / Non-Goals

**Goals:**
- Faire porter l'obligation par l'état du compte côté serveur afin que le contrôle ne dépende pas de l'interface.
- Assurer que l'obligation est levée uniquement après un changement valide du mot de passe.
- Intégrer l'action dans le listing utilisateurs sans confondre celle-ci avec une réinitialisation.

**Non-Goals:**
- Définir les règles de composition des mots de passe ni remplacer le parcours existant de récupération.
- Modifier les permissions fixes des rôles ou le parcours d'affectation de rôle.
- Implémenter un nouveau mécanisme d'authentification ou créer une interface dynamique dans ce changement de cadrage.

## Decisions

- **Persister une obligation de changement sur le compte, pas le mot de passe fourni par un admin.** L'action admin ne choisit ni ne consulte le nouveau secret; le titulaire en définit un dans le parcours authentifié déjà prévu pour le changement de mot de passe. Une notification seulement dans le navigateur ou la session ne garantirait pas le contrôle à la prochaine connexion.
- **Appliquer l'obligation après l'authentification réussie, avant l'accès aux autres fonctionnalités.** Le contrôle est imposé côté serveur et reste actif si le titulaire abandonne ou échoue à valider un nouveau mot de passe. Il est levé avec la mise à jour valide du mot de passe afin d'éviter un état intermédiaire où l'accès normal serait ouvert sans satisfaire l'obligation.
- **Ajouter l'action au listing d'administration existant.** Le parcours statique montre le compte visé, une confirmation, puis les états de succès et d'erreur; il ne crée pas un écran séparé et ne modifie pas l'action de changement de rôle.

## Risks / Trade-offs

- [Un contrôle uniquement visuel pourrait être contourné] → exiger la vérification d'autorisation de l'action admin et le blocage des autres fonctionnalités côté serveur.
- [Une interruption durant le changement pourrait laisser le compte dans un état incomplet] → conserver l'obligation jusqu'à la validation réussie du nouveau mot de passe.
- [Un libellé ambigu pourrait être pris pour une réinitialisation effectuée par l'admin] → préciser dans la maquette que le titulaire changera lui-même son mot de passe à la prochaine connexion.
