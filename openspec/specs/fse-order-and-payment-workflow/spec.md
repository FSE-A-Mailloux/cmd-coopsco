## Purpose

Définir le cycle de vie de la commande FSE, les parcours de règlement, les sorties documentaires et le flux de paiement par carte.

## Requirements

### Requirement: Le cycle de vie de la cotisation MUST suivre l’état de commande et les contraintes réglementaires
La cotisation MUST suivre les règles métier de la commande, rester traçable, et devenir non supprimable une fois la commande validée et facturée.

#### Scenario: Suppression de cotisation avant validation et facturation
- **WHEN** une commande n’est ni validée ni facturée et qu’un utilisateur habilité demande la suppression de cotisation
- **THEN** le système autorise la suppression après confirmation explicite et trace l’action

#### Scenario: Suppression de cotisation après validation et facturation
- **WHEN** une commande est validée et facturée
- **THEN** le système refuse toute suppression de cotisation

### Requirement: Le workflow de facturation MUST respecter la réglementation française
Le processus de facturation MUST respecter les obligations réglementaires françaises applicables.

#### Scenario: Génération de facture sur commande éligible
- **WHEN** une facture est demandée pour une commande éligible
- **THEN** le document généré respecte les règles réglementaires applicables

### Requirement: La création de commande FSE MUST persister la commande, les enfants et les articles sélectionnés
La création de commande MUST enregistrer l’ensemble des données métier nécessaires (responsable, bénéficiaires, lignes, période) et produire un identifiant de commande traçable.

#### Scenario: Soumission d’une nouvelle commande
- **WHEN** un utilisateur soumet une commande valide
- **THEN** le système enregistre la commande avec ses détails et son identifiant métier

### Requirement: Le workflow FSE MUST prendre en charge les transitions de règlement et validation
Le workflow commande MUST prendre en charge les règlements, les transitions d’état et les validations de cohérence financière.

#### Scenario: Enregistrement d’un règlement valide
- **WHEN** un règlement valide est enregistré
- **THEN** le système met à jour l’état de commande conformément aux règles métier

#### Scenario: Payload de règlement invalide
- **WHEN** un règlement invalide est soumis
- **THEN** le système refuse l’opération avec une erreur fonctionnelle explicite

### Requirement: Les documents FSE MUST être générés à partir des données de commande
Le système MUST générer les documents de commande et de facturation à partir des données métier validées et les rendre disponibles au téléchargement.

#### Scenario: Récupération PDF par utilisateur autorisé
- **WHEN** un utilisateur autorisé demande le document d’une commande éligible
- **THEN** le système retourne un document téléchargeable correspondant

### Requirement: Le checkout carte FSE MUST intégrer les intents de paiement OAuth2
Le paiement en ligne MUST intégrer un prestataire externe, retourner un statut explicite selon l’éligibilité de commande, et permettre la reprise d’un paiement déjà initié.

#### Scenario: Première demande de checkout carte
- **WHEN** un paiement en ligne est initié pour une commande éligible
- **THEN** le système crée un parcours de paiement et retourne les informations de redirection nécessaires

#### Scenario: Demande de checkout existante
- **WHEN** un paiement déjà initié est reconsulté
- **THEN** le système retourne l’état courant du paiement ou le statut explicite correspondant

