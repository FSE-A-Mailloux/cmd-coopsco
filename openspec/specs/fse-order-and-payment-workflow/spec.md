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

### Requirement: Le remboursement manuel d'une commande annulee MUST etre trace et coherent
Le systeme MUST permettre a un gestionnaire habilite d'enregistrer un ou plusieurs remboursements manuels uniquement pour une commande annulee disposant d'un avoir rattache a cette meme commande. Chaque remboursement MUST etre conserve comme un mouvement distinct des paiements d'origine et MUST enregistrer la commande, l'avoir, le montant, la devise de la commande, le moyen de remboursement, la date effective, une reference non vide et le gestionnaire responsable.

Le montant remboursable de la commande MUST etre egal au total des paiements effectivement encaisses et conserves dans l'historique ; un paiement refuse, annule ou encore en cours MUST NOT etre inclus. Le solde disponible MUST etre calcule comme le montant remboursable moins la somme des remboursements deja enregistres.

Un remboursement MUST avoir un montant strictement positif et exprime avec la precision monetaire de la commande. Les liens vers la commande, l'avoir et le gestionnaire MUST respecter l'integrite referentielle ; l'avoir MUST appartenir a la commande remboursee. Le systeme MUST refuser un remboursement si l'une de ces references est absente ou invalide. La reference operationnelle MUST etre non vide apres suppression des espaces de debut et de fin.

Les remboursements enregistres MUST etre immuables : le systeme MUST conserver leur montant, moyen, date, reference et gestionnaire tels qu'enregistres, sans modifier ni supprimer le mouvement ou les paiements historiques. Toute procedure corrective ulterieure MUST produire une nouvelle trace plutot que reecrire silencieusement l'historique.

L'enregistrement et le calcul du montant restant MUST etre atomiques. Dans la transaction, le systeme MUST recalculer le total des paiements historiques et des remboursements deja enregistres, puis verifier que le nouveau remboursement ne depasse pas le solde disponible. La verification et l'ecriture MUST etre protegees contre deux enregistrements concurrents qui depasseraient ensemble ce solde. En cas d'echec d'une validation ou d'une ecriture, le systeme MUST annuler l'operation sans laisser de remboursement partiel persiste.

Le systeme MUST verifier l'habilitation cote serveur a chaque enregistrement. Le gestionnaire responsable MUST provenir de l'identite authentifiee et autorisee, et non d'une valeur librement choisie ou fournie par le formulaire. Une tentative non autorisee MUST etre refusee et MUST laisser les paiements et remboursements inchanges. La consultation des montants et de l'historique des remboursements MUST etre reservee aux gestionnaires habilites ; une famille MUST NOT pouvoir consulter les donnees internes de remboursement ni declencher un remboursement.

#### Scenario: Enregistrement d'un remboursement manuel
- **WHEN** un gestionnaire habilite enregistre un remboursement valide pour une commande annulee avec avoir
- **THEN** le systeme conserve le remboursement avec ses informations de tracabilite et met a jour le montant restant a rembourser

#### Scenario: Persistance avec liens coherents
- **WHEN** un remboursement est enregistre avec une commande, un avoir ou un gestionnaire absent, inexistant, ou avec un avoir rattache a une autre commande
- **THEN** le systeme refuse l'enregistrement et ne conserve aucun mouvement de remboursement

#### Scenario: Montant positif et precision monetaire
- **WHEN** un gestionnaire soumet un montant nul, negatif ou comportant une precision superieure a celle de la devise de la commande
- **THEN** le systeme refuse l'enregistrement et indique que le montant doit etre strictement positif et respecte la precision monetaire autorisee

#### Scenario: Calcul du montant remboursable
- **WHEN** le systeme calcule le solde disponible d'une commande
- **THEN** il additionne uniquement les paiements effectivement encaisses, exclut les paiements refuses, annules ou en cours, puis soustrait les remboursements deja enregistres

#### Scenario: Remboursement partiel
- **WHEN** le montant cumule des remboursements est inferieur au montant total regle sur la commande
- **THEN** le systeme indique que la commande est partiellement remboursee et affiche le montant restant a rembourser

#### Scenario: Remboursement total
- **WHEN** le montant cumule des remboursements atteint le montant total regle sur la commande
- **THEN** le systeme indique que la commande est entierement remboursee

#### Scenario: Depassement du montant remboursable
- **WHEN** un gestionnaire soumet un remboursement dont le montant cumule depasse le montant total regle sur la commande
- **THEN** le systeme refuse l'enregistrement et signale explicitement le montant encore remboursable

#### Scenario: Deux remboursements concurrents
- **WHEN** deux gestionnaires soumettent simultanement des remboursements dont la somme depasserait le solde disponible
- **THEN** le systeme n'enregistre que les remboursements qui respectent le plafond et refuse explicitement celui ou ceux qui le depassent en indiquant le solde restant actualise

#### Scenario: Remboursement sur commande non eligible
- **WHEN** un gestionnaire demande un remboursement pour une commande non annulee ou ne disposant pas d'un avoir
- **THEN** le systeme refuse l'operation avec une erreur fonctionnelle explicite

#### Scenario: Gestionnaire non habilite
- **WHEN** un utilisateur non habilite tente d'enregistrer un remboursement ou fournit l'identifiant d'un autre gestionnaire comme responsable
- **THEN** le systeme refuse l'operation, ne cree aucun remboursement et n'accepte pas une identite de gestionnaire qui differe de l'identite authentifiee autorisee

#### Scenario: Famille consultant une commande remboursee
- **WHEN** un membre d'une famille consulte sa commande annulee
- **THEN** le systeme ne lui expose ni l'historique interne des remboursements, ni l'identite du gestionnaire, ni une action permettant d'enregistrer un remboursement

#### Scenario: Conservation des mouvements historiques
- **WHEN** un remboursement valide est enregistre
- **THEN** le systeme ajoute un mouvement distinct et ne modifie ni ne supprime aucun paiement historique ni remboursement anterieur

#### Scenario: Moyen de remboursement
- **WHEN** un gestionnaire enregistre un remboursement
- **THEN** le systeme lui permet de selectionner un moyen de remboursement manuel applicable a la carte bancaire, au cheque ou aux especes

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

