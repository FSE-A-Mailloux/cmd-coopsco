# batch-card-paid-order-documents Specification

## Purpose

Permet aux gestionnaires de produire et télécharger les PDF des commandes réglées par carte, en lots bornés et selon un périmètre de commandes déterministe.

## Requirements

### Requirement: Le traitement MUST sélectionner les commandes éligibles de la période en cours
Le système MUST limiter la sélection aux commandes de la période en cours qui possèdent au moins un règlement par carte enregistré et non annulé. Une commande sans règlement par carte non annulé MUST NOT être incluse.

#### Scenario: Commande avec règlement par carte non annulé
- **WHEN** le gestionnaire lance la génération et qu'une commande de la période en cours possède un règlement par carte non annulé
- **THEN** le système inclut cette commande dans les commandes à traiter

#### Scenario: Commande hors période en cours
- **WHEN** une commande possède un règlement par carte non annulé mais appartient à une période qui n'est pas en cours
- **THEN** le système exclut cette commande du traitement

#### Scenario: Commande sans règlement par carte valide
- **WHEN** une commande de la période en cours n'a aucun règlement par carte non annulé
- **THEN** le système exclut cette commande du traitement, y compris si elle possède un règlement par carte annulé ou un règlement par un autre moyen

### Requirement: Les commandes éligibles MUST être triées par premier enfant puis réparties en lots de 30 au maximum
Le système MUST trier globalement les commandes éligibles par ordre alphabétique croissant du nom puis du prénom de leur premier enfant lié à la commande, avant de les répartir dans cet ordre en lots consécutifs contenant au plus 30 commandes. Le numéro de commande MUST NOT être utilisé comme critère de tri. Chaque commande éligible MUST figurer dans un seul lot, et le dernier lot peut contenir moins de 30 commandes. Une limite de lot peut séparer des commandes liées au même enfant.

#### Scenario: Tri alphabétique par premier enfant
- **WHEN** plusieurs commandes éligibles sont traitées et que leurs premiers enfants liés ont des noms ou prénoms différents
- **THEN** le système ordonne les commandes par nom de famille croissant du premier enfant, puis par prénom croissant

#### Scenario: Sélection supérieure à 30 commandes
- **WHEN** plus de 30 commandes sont éligibles
- **THEN** le système crée des lots de 30 commandes au plus en conservant l'ordre alphabétique défini, sans omission ni doublon

#### Scenario: Dernier lot incomplet
- **WHEN** le nombre de commandes éligibles n'est pas un multiple de 30
- **THEN** le système inclut toutes les commandes restantes dans un dernier lot de moins de 30 commandes

### Requirement: Un gestionnaire MUST pouvoir lancer le traitement et télécharger un PDF par lot
Le système MUST réserver le lancement de la génération aux gestionnaires habilités et MUST générer un PDF téléchargeable pour chaque lot, contenant les commandes de ce lot. Les fichiers MUST être restitués séquentiellement avec un nom identifiant leur numéro de lot et le nombre total de lots. Le système MUST présenter un état de progression et signaler explicitement les échecs sans les présenter comme des réussites.

#### Scenario: Génération et restitution de plusieurs lots
- **WHEN** un gestionnaire habilité lance le traitement pour plusieurs lots
- **THEN** le système génère et télécharge un PDF pour chaque lot, dans l'ordre, avec un nom permettant d'identifier le numéro du lot et le nombre total de lots

#### Scenario: Aucun lot à générer
- **WHEN** un gestionnaire habilité lance le traitement et qu'aucune commande n'est éligible
- **THEN** le système indique qu'il n'y a aucune commande à traiter et ne génère aucun PDF

#### Scenario: Utilisateur non habilité
- **WHEN** un utilisateur non habilité tente de lancer le traitement
- **THEN** le système refuse l'opération et ne génère ni ne restitue aucun document

#### Scenario: Échec de génération d'un lot
- **WHEN** la génération ou la restitution d'un PDF échoue pour un lot
- **THEN** le système identifie le lot en échec, informe le gestionnaire et ne signale pas le traitement complet comme réussi
