## MODIFIED Requirements

### Requirement: La création d’utilisateur MUST appliquer les préconditions d’unicité et de validation
La création de compte MUST garantir l’unicité des identifiants attendus et appliquer les contrôles de validation/anti-abus définis par la politique produit.

#### Scenario: Compte dupliqué à la création
- **WHEN** un compte est soumis avec un identifiant déjà utilisé
- **THEN** le système rejette la création avec une erreur fonctionnelle explicite

#### Scenario: Auto-inscription publique avec captcha activé
- **WHEN** une inscription publique est demandée et qu’un contrôle anti-abus est actif
- **THEN** le système valide ce contrôle avant création du compte

### Requirement: Les contextes du cycle de vie utilisateur MUST supporter l’activation et la récupération d’identifiants
Le cycle de vie compte MUST couvrir activation, récupération d’accès, réinitialisation de mot de passe et changement de mot de passe authentifié.

#### Scenario: Réinitialisation de mot de passe via lien de récupération
- **WHEN** un parcours de récupération valide est complété
- **THEN** le système met à jour les identifiants de sécurité du compte

#### Scenario: Code de récupération ou d’activation invalide
- **WHEN** un code invalide est utilisé dans un parcours d’activation ou de récupération
- **THEN** le système rejette l’opération sans modifier l’état du compte

### Requirement: La suppression d’utilisateur MUST appliquer les contrôles de privilèges et l’anonymisation
La suppression d’un compte MUST respecter les règles de privilèges et MUST anonymiser les données historiques personnelles selon la politique de conformité.

#### Scenario: Suppression non autorisée d’un compte à privilège élevé
- **WHEN** un utilisateur non autorisé tente de supprimer un compte protégé
- **THEN** le système refuse l’opération avec une erreur d’autorisation

#### Scenario: Suppression réussie
- **WHEN** une suppression autorisée est réalisée
- **THEN** le système applique l’anonymisation requise et nettoie les rattachements d’accès nécessaires
