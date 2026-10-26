## ADDED Requirements

### Requirement: Les permissions effectives MUST être recalculées après un changement de rôle
Après un changement de rôle validé, le système MUST appliquer à l’utilisateur cible les permissions prédéfinies de son nouveau rôle dès le premier contrôle d’accès suivant, y compris pour une session déjà ouverte et sans exiger de reconnexion. Ce changement MUST NOT modifier la définition des rôles ou de leurs permissions.

#### Scenario: Restriction après rétrogradation
- **WHEN** un utilisateur cible ayant une session ouverte perd une permission via un changement de rôle validé par un acteur `Admin`
- **THEN** la première vérification d’accès suivante refuse l’action devenue non autorisée

#### Scenario: Autorisation après élévation légitime
- **WHEN** un utilisateur cible ayant une session ouverte obtient une permission via un changement de rôle validé par un acteur `Admin`
- **THEN** la première vérification d’accès suivante autorise l’action désormais permise

### Requirement: Le menu retourné MUST refléter le rôle mis à jour
À la reconnexion après un changement de rôle validé, le menu calculé pour l’utilisateur cible MUST refléter les permissions prédéfinies de son nouveau rôle : retirer les destinations devenues interdites et inclure les destinations nouvellement autorisées. Cette exigence ne prescrit pas de rafraîchissement automatique du menu d’une page déjà ouverte et ne remplace pas les contrôles d’accès fonctionnels.

#### Scenario: Menu épuré après perte de droits
- **WHEN** un utilisateur est reconnecté après un changement de rôle qui retire des permissions
- **THEN** le menu retourné n’inclut plus les destinations associées aux permissions retirées

#### Scenario: Menu enrichi après ajout de droits
- **WHEN** un utilisateur est reconnecté après un changement de rôle qui ajoute des permissions
- **THEN** le menu retourné inclut les destinations nouvellement autorisées
