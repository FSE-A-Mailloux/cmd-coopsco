## ADDED Requirements

### Requirement: Les permissions effectives MUST être recalculées après un changement de rôle
Après un changement de rôle validé, le système MUST appliquer le nouveau profil d’autorisation de l’utilisateur cible afin que les contrôles d’accès suivants utilisent les permissions mises à jour.

#### Scenario: Restriction après rétrogradation
- **WHEN** un utilisateur perd une permission via un changement de rôle validé
- **THEN** la première vérification d’accès suivante refuse l’action devenue non autorisée

#### Scenario: Autorisation après élévation légitime
- **WHEN** un utilisateur obtient une permission via un changement de rôle validé
- **THEN** la première vérification d’accès suivante autorise l’action désormais permise

### Requirement: Le menu retourné MUST refléter le rôle mis à jour
Le menu calculé pour l’utilisateur cible MUST refléter son nouveau rôle et ne MUST pas exposer d’entrées qui ne sont plus autorisées après la mise à jour.

#### Scenario: Menu épuré après perte de droits
- **WHEN** un utilisateur est reconnecté après un changement de rôle qui retire des permissions
- **THEN** le menu retourné n’inclut plus les destinations associées aux permissions retirées

#### Scenario: Menu enrichi après ajout de droits
- **WHEN** un utilisateur est reconnecté après un changement de rôle qui ajoute des permissions
- **THEN** le menu retourné inclut les destinations nouvellement autorisées
