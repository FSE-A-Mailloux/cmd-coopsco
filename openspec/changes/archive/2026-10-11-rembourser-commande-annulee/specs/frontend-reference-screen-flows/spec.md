## ADDED Requirements

### Requirement: Les gestionnaires MUST disposer d'un parcours de remboursement manuel
Le detail d'une commande annulee avec avoir MUST presenter aux gestionnaires habilites le montant rembourse, le montant restant a rembourser et, si le solde est positif, une action de remboursement manuel. Le formulaire de remboursement MUST afficher le contexte de la commande et collecter le montant, le moyen, la date effective et la reference operationnelle. Il MUST presenter le montant restant comme plafond et empecher la soumission d'un montant nul, negatif ou superieur au plafond connu. La validation serveur reste la source d'autorite en cas de solde modifie entre l'affichage du formulaire et sa soumission.

#### Scenario: Acces au remboursement depuis une commande eligible
- **WHEN** un gestionnaire consulte le detail d'une commande annulee avec avoir et un montant restant a rembourser
- **THEN** l'interface affiche l'action `Enregistrer un remboursement`

#### Scenario: Aucun solde a rembourser
- **WHEN** un gestionnaire consulte une commande annulee avec avoir dont le montant restant a rembourser est nul
- **THEN** l'interface affiche l'etat entierement remboursee et n'affiche pas d'action pour enregistrer un nouveau remboursement

#### Scenario: Detail de remboursement reserve au gestionnaire
- **WHEN** un membre d'une famille consulte le detail de sa commande annulee
- **THEN** l'interface ne presente ni les montants internes rembourses ou restant, ni l'historique des remboursements, ni l'action ou le formulaire de remboursement

#### Scenario: Saisie d'un remboursement manuel
- **WHEN** un gestionnaire ouvre le parcours de remboursement manuel
- **THEN** l'interface affiche le numero de commande, le montant restant, le montant, le moyen de remboursement, la date et la reference a saisir

#### Scenario: Saisie au-dela du montant restant
- **WHEN** le gestionnaire saisit un montant nul, negatif ou superieur au montant restant affiche
- **THEN** l'interface signale que la valeur est invalide et empeche la soumission, sans remplacer le controle du serveur

#### Scenario: Consultation des remboursements enregistres
- **WHEN** un gestionnaire consulte une commande comportant des remboursements
- **THEN** l'interface affiche chaque remboursement avec son montant, son moyen, sa date, sa reference et son gestionnaire responsable
