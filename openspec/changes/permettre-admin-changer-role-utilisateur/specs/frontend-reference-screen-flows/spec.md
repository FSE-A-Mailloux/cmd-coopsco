## ADDED Requirements

### Requirement: Les maquettes d'administration MUST couvrir le parcours de changement de rôle utilisateur
Le référentiel d'écrans de référence MUST inclure un parcours administrateur permettant de consulter un utilisateur, sélectionner un rôle cible autorisé et confirmer la modification.

#### Scenario: Ouverture du parcours de changement de rôle
- **WHEN** un utilisateur consulte les maquettes avec le rôle `admin`
- **THEN** il peut naviguer vers un écran de gestion des utilisateurs exposant le rôle actuel et une action de changement de rôle

#### Scenario: Confirmation visuelle du nouveau rôle
- **WHEN** l'administrateur sélectionne un rôle cible valide et confirme l'action dans la maquette
- **THEN** le parcours affiche un état de succès avec rappel de l'ancien rôle et du nouveau rôle

### Requirement: Les maquettes MUST représenter les états d'erreur du changement de rôle
Les écrans de référence MUST matérialiser les cas d'échec fonctionnels du changement de rôle afin de cadrer le comportement attendu en revue.

#### Scenario: Rôle cible invalide
- **WHEN** la maquette représente une tentative avec un rôle cible hors référentiel autorisé
- **THEN** l'écran affiche un message d'erreur explicite et conserve le rôle courant inchangé dans le récapitulatif visuel

#### Scenario: Refus d'autorisation sur action sensible
- **WHEN** la maquette représente une tentative de changement de rôle par un acteur non habilité
- **THEN** le parcours affiche un état de refus d'autorisation et n'expose pas de confirmation de modification
