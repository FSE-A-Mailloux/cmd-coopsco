## Why

Le besoin legacy UC-ADM-2 est repris dans le PRD (FR-038), mais n'est pas encore décliné en règles fonctionnelles ni en parcours de maquette. Il faut cadrer comment un administrateur impose un changement de mot de passe à la prochaine connexion, sans confondre cette action avec la réinitialisation du mot de passe ni rouvrir les périmètres abandonnés.

## What Changes

- Définir l'autorisation et les résultats fonctionnels de l'action d'imposer un changement de mot de passe à un utilisateur lors de sa prochaine connexion.
- Décrire le traitement attendu à la connexion et après le changement de mot de passe, y compris les refus et erreurs sans état incohérent.
- Produire les maquettes correspondantes dans le parcours d'administration des utilisateurs.
- Garder les rôles `Famille`, `Gestionnaire` et `Admin` à permissions fixes; ne pas reprendre la gestion des groupes ni du référentiel de rôles.
- Ne pas modifier l'affectation d'un rôle existant, déjà cadrée séparément.

## Capabilities

### New Capabilities

Aucune.

### Modified Capabilities

- `user-account-lifecycle-management` : ajouter le comportement d'imposition administrative d'un changement de mot de passe à la prochaine connexion.
- `frontend-reference-screen-flows` : ajouter le parcours de maquette de cette action dans l'administration des utilisateurs.

## Impact

Les artefacts concernés sont les specs OpenSpec du cycle de vie des comptes et des maquettes de référence, la maquette statique existante d'administration des utilisateurs, ainsi que la matrice de couverture legacy. Le catalogue existant doit continuer à référencer cette maquette; aucun nouvel écran, changement de code applicatif ou dépendance n'est prévu. La couverture du besoin legacy sera alignée avec UC-ADM-2 et FR-038.
