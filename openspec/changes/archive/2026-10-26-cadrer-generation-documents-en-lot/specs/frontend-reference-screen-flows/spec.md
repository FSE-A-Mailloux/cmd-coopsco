## ADDED Requirements

### Requirement: L'ecran de generation en lot MUST representer le parcours gestionnaire UC-CON-3
La maquette de reference UC-CON-3 MUST etre rattachee au role `gestionnaire` et MUST presenter la periode en cours, le critere d'eligibilite des commandes (reglement par carte non annule), le tri alphabetique par nom puis prenom du premier enfant lie a chaque commande, la limite de 30 commandes par lot et l'ordre de generation. Elle MUST illustrer la progression sequentielle, le cas sans commande eligible et l'echec d'un lot. L'ecran de reference MUST rester statique et MUST NOT laisser croire qu'une generation ou un telechargement reel est execute.

#### Scenario: Consultation du parcours de generation
- **WHEN** un gestionnaire consulte l'ecran de reference UC-CON-3
- **THEN** il voit que le traitement concerne uniquement la periode en cours et les commandes ayant un reglement par carte non annule, avec au plus 30 commandes par lot

#### Scenario: Visualisation de la progression
- **WHEN** un gestionnaire consulte l'exemple de traitement en cours
- **THEN** la maquette montre les lots ordonnes alphabetiquement par nom puis prenom du premier enfant, le rang du lot courant et l'etat de restitution du PDF attendu

#### Scenario: Aucune commande eligible
- **WHEN** le gestionnaire consulte l'etat de resultat sans commande eligible
- **THEN** la maquette montre un message explicite et aucun lot ni document telecharge

#### Scenario: Echec d'un lot
- **WHEN** le gestionnaire consulte l'etat d'un lot dont la generation a echoue
- **THEN** la maquette identifie le lot en echec et montre un message d'erreur sans afficher le traitement complet comme reussi

#### Scenario: Apercu statique d'un bon de commande
- **WHEN** le gestionnaire consulte l'exemple de bon de commande associe a la generation en lot
- **THEN** la maquette illustre les donnees assemblees par le traitement legacy pour le modele de lot : informations de commande et de periode, commanditaire, enfants avec leur classe, articles avec reference, marque, quantite, prix et total, ainsi que le total de commande
- **AND** l'exemple est explicitement fictif et ne pretend pas reproduire une mise en page dont le modele HTML n'est pas disponible dans le depot

#### Scenario: Maquette sans execution reelle
- **WHEN** un utilisateur consulte ou actionne les controles visuels de la maquette
- **THEN** aucune requete metier, generation de PDF ou telechargement reel n'est declenche
