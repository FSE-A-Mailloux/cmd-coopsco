## ADDED Requirements

### Requirement: Les maquettes MUST couvrir les deux journaux pour les gestionnaires et les admins
Le référentiel MUST proposer deux écrans distincts `suivi-journal-actions` et `suivi-anomalies`, déclarés pour les groupes `gestionnaire` et `admin` dans les deux manifestes. Chaque écran MUST représenter les colonnes domaine, action, contexte et date-heure, avec des exemples fictifs triés du plus récent au plus ancien. Les écrans MUST rester consultables localement et à distance sans backend, en reprenant les styles partagés et la présentation responsive des listings.

#### Scenario: Navigation des deux rôles
- **WHEN** un participant ouvre le catalogue sous le groupe gestionnaire ou admin
- **THEN** il peut ouvrir les deux journaux avec leurs exemples fictifs et leurs quatre informations minimales

#### Scenario: Aperçu téléphone et desktop
- **WHEN** un participant consulte chacun des deux journaux en mode téléphone puis desktop
- **THEN** les quatre informations sont lisibles en cartes sur téléphone sans défilement horizontal de la page, et en tableau sur desktop, avec les mêmes entrées dans le même ordre

### Requirement: Les maquettes des journaux MUST distinguer données et états de consultation
Chaque écran MUST représenter explicitement les états liste renseignée, liste vide, refus d’autorisation, session expirée et erreur de chargement définis dans `application-journal-consultation`. Les exemples MUST être identifiés comme fictifs, sans appel réseau, persistance ni action de modification ou traitement des traces. Les états d’erreur MUST NOT afficher les exemples comme s’il s’agissait de résultats réels.

#### Scenario: Revue des états sans backend
- **WHEN** un participant consulte les variantes de chacun des deux écrans hors connexion
- **THEN** il peut examiner les cinq états identifiés sans service métier ni donnée réelle

#### Scenario: Accès refusé ou session expirée
- **WHEN** une variante représente un utilisateur non autorisé ou une session expirée
- **THEN** elle affiche le message correspondant sans trace visible et sans suggérer qu’un masquage de navigation suffit à protéger les données

#### Scenario: Absence de résultat et erreur technique
- **WHEN** un participant compare les variantes vide et erreur de chargement
- **THEN** la première indique l’absence d’entrée et la seconde un échec explicite, sans les confondre ni proposer des exemples de substitution
