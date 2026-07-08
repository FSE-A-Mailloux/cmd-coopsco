## ADDED Requirements

### Requirement: Le suivi de stock article MUST exposer une evolution temporelle consultable
Le systeme MUST permettre de consulter, pour un article donne, l'evolution du niveau de stock basee sur les dates de modification de stock.

#### Scenario: Consultation de l'historique de stock d'un article
- **WHEN** un utilisateur autorise demande la visualisation de l'evolution de stock d'un article
- **THEN** le systeme retourne une representation temporelle du stock pour cet article, indexee par les dates de modification de stock

### Requirement: La mise a jour de stock article MUST porter sur la quantite disponible avec etat derive
La mise a jour du stock d'un article MUST permettre de definir la quantite disponible, avec tracabilite.
L'etat de stock MUST etre derive automatiquement de la quantite disponible: `Rupture` si la quantite disponible est egale a 0, sinon `Disponible`.

#### Scenario: Mise a jour complete d'un article de stock
- **WHEN** un utilisateur autorise modifie le stock d'un article
- **THEN** le systeme enregistre la quantite disponible de l'article avec tracabilite
- **AND** le systeme met a jour l'etat de stock derive en fonction de la quantite disponible
