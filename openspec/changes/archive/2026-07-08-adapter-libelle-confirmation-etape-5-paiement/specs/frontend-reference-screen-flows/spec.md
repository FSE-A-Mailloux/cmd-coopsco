## ADDED Requirements

### Requirement: L'etape 5 MUST adapter le libelle du bouton final au mode de paiement selectionne
Dans `Etape 5 - Mode de paiement`, le libelle du bouton d'action final MUST refleter l'intention metier du mode choisi: paiement effectif pour CB, confirmation pour cheque/espece.  
L'etape MUST afficher un message explicite indiquant que la commande est prise en compte a reception du paiement.

#### Scenario: Libelle oriente paiement pour CB
- **WHEN** la famille selectionne le radio bouton `Paiement CB (via HelloAsso)` dans `Etape 5 - Mode de paiement`
- **THEN** le bouton final affiche un libelle de paiement explicite, de type `Payer la commande`

#### Scenario: Libelle de confirmation pour cheque
- **WHEN** la famille selectionne le radio bouton `Paiement par cheque` dans `Etape 5 - Mode de paiement`
- **THEN** le bouton final affiche un libelle de confirmation de commande (et non un libelle de paiement CB)

#### Scenario: Libelle de confirmation pour espece
- **WHEN** la famille selectionne le radio bouton `Paiement en espece` dans `Etape 5 - Mode de paiement`
- **THEN** le bouton final affiche un libelle de confirmation de commande (et non un libelle de paiement CB)

#### Scenario: Mise a jour immediate du libelle lors du changement de mode
- **WHEN** la famille change de radio bouton de mode de paiement au sein de l'etape 5
- **THEN** le libelle du bouton final est mis a jour immediatement pour rester coherent avec le mode actuellement selectionne

#### Scenario: Information de prise en compte de commande
- **WHEN** la famille consulte `Etape 5 - Mode de paiement`
- **THEN** l'interface affiche un texte explicite indiquant que la commande n'est prise en compte qu'a reception du paiement
