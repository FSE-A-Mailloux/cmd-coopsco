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

### Requirement: Le remboursement manuel d'une commande annulée MUST être tracé et cohérent
Le système MUST permettre à un gestionnaire habilité d'enregistrer un ou plusieurs remboursements manuels uniquement pour une commande annulée disposant d'un avoir rattaché à cette même commande. Chaque remboursement MUST être conservé comme un mouvement distinct des paiements d'origine et MUST enregistrer la commande, l'avoir, le montant, la devise de la commande, le moyen de remboursement, la date effective, une référence non vide et le gestionnaire responsable.

Le montant remboursable de la commande MUST être égal au total des paiements effectivement encaissés et conservés dans l'historique ; un paiement refusé, annulé ou encore en cours MUST NOT être inclus. Le solde disponible MUST être calculé comme le montant remboursable moins la somme des remboursements déjà enregistrés.

Un remboursement MUST avoir un montant strictement positif et exprimé avec la précision monétaire de la commande. Les liens vers la commande, l'avoir et le gestionnaire MUST respecter l'intégrité référentielle ; l'avoir MUST appartenir à la commande remboursée. Le système MUST refuser un remboursement si l'une de ces références est absente ou invalide. La référence opérationnelle MUST être non vide après suppression des espaces de début et de fin.

Les remboursements enregistrés MUST être immuables : le système MUST conserver leur montant, moyen, date, référence et gestionnaire tels qu'enregistrés, sans modifier ni supprimer le mouvement ou les paiements historiques. Toute procédure corrective ultérieure MUST produire une nouvelle trace plutôt que réécrire silencieusement l'historique.

L'enregistrement et le calcul du montant restant MUST être atomiques. Dans la transaction, le système MUST recalculer le total des paiements historiques et des remboursements déjà enregistrés, puis vérifier que le nouveau remboursement ne dépasse pas le solde disponible. La vérification et l'écriture MUST être protégées contre deux enregistrements concurrents qui dépasseraient ensemble ce solde. En cas d'échec d'une validation ou d'une écriture, le système MUST annuler l'opération sans laisser de remboursement partiel persisté.

Le système MUST vérifier l'habilitation côté serveur à chaque enregistrement. Le gestionnaire responsable MUST provenir de l'identité authentifiée et autorisée, et non d'une valeur librement choisie ou fournie par le formulaire. Une tentative non autorisée MUST être refusée et MUST laisser les paiements et remboursements inchangés. La consultation des montants et de l'historique des remboursements MUST être réservée aux gestionnaires habilités ; une famille MUST NOT pouvoir consulter les données internes de remboursement ni déclencher un remboursement.

#### Scenario: Enregistrement d'un remboursement manuel
- **WHEN** un gestionnaire habilité enregistre un remboursement valide pour une commande annulée avec avoir
- **THEN** le système conserve le remboursement avec ses informations de traçabilité et met à jour le montant restant à rembourser

#### Scenario: Persistance avec liens cohérents
- **WHEN** un remboursement est enregistré avec une commande, un avoir ou un gestionnaire absent, inexistant, ou avec un avoir rattaché à une autre commande
- **THEN** le système refuse l'enregistrement et ne conserve aucun mouvement de remboursement

#### Scenario: Montant positif et précision monétaire
- **WHEN** un gestionnaire soumet un montant nul, négatif ou comportant une précision supérieure à celle de la devise de la commande
- **THEN** le système refuse l'enregistrement et indique que le montant doit être strictement positif et respecter la précision monétaire autorisée

#### Scenario: Calcul du montant remboursable
- **WHEN** le système calcule le solde disponible d'une commande
- **THEN** il additionne uniquement les paiements effectivement encaissés, exclut les paiements refusés, annulés ou en cours, puis soustrait les remboursements déjà enregistrés

#### Scenario: Remboursement partiel
- **WHEN** le montant cumulé des remboursements est inférieur au montant total réglé sur la commande
- **THEN** le système indique que la commande est partiellement remboursée et affiche le montant restant à rembourser

#### Scenario: Remboursement total
- **WHEN** le montant cumulé des remboursements atteint le montant total réglé sur la commande
- **THEN** le système indique que la commande est entièrement remboursée

#### Scenario: Dépassement du montant remboursable
- **WHEN** un gestionnaire soumet un remboursement dont le montant cumulé dépasse le montant total réglé sur la commande
- **THEN** le système refuse l'enregistrement et signale explicitement le montant encore remboursable

#### Scenario: Deux remboursements concurrents
- **WHEN** deux gestionnaires soumettent simultanément des remboursements dont la somme dépasserait le solde disponible
- **THEN** le système n'enregistre que les remboursements qui respectent le plafond et refuse explicitement celui ou ceux qui le dépassent en indiquant le solde restant actualisé

#### Scenario: Remboursement sur commande non éligible
- **WHEN** un gestionnaire demande un remboursement pour une commande non annulée ou ne disposant pas d'un avoir
- **THEN** le système refuse l'opération avec une erreur fonctionnelle explicite

#### Scenario: Gestionnaire non habilité
- **WHEN** un utilisateur non habilité tente d'enregistrer un remboursement ou fournit l'identifiant d'un autre gestionnaire comme responsable
- **THEN** le système refuse l'opération, ne crée aucun remboursement et n'accepte pas une identité de gestionnaire qui diffère de l'identité authentifiée autorisée

#### Scenario: Famille consultant une commande remboursée
- **WHEN** un membre d'une famille consulte sa commande annulée
- **THEN** le système ne lui expose ni l'historique interne des remboursements, ni l'identité du gestionnaire, ni une action permettant d'enregistrer un remboursement

#### Scenario: Conservation des mouvements historiques
- **WHEN** un remboursement valide est enregistré
- **THEN** le système ajoute un mouvement distinct et ne modifie ni ne supprime aucun paiement historique ni remboursement antérieur

#### Scenario: Moyen de remboursement
- **WHEN** un gestionnaire enregistre un remboursement
- **THEN** le système lui permet de sélectionner un moyen de remboursement manuel applicable à la carte bancaire, au chèque ou aux espèces

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
