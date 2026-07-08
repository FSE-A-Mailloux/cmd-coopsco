## ADDED Requirements

### Requirement: Le listing articles MUST proposer une recherche multicriteres
L'ecran `Articles - Listing` MUST proposer une zone de recherche permettant de filtrer les lignes du tableau sur le libelle de l'article, sa reference et sa marque.

#### Scenario: Recherche article depuis un champ unique
- **WHEN** un utilisateur saisit une valeur dans la zone de recherche de `Articles - Listing`
- **THEN** l'interface indique que la recherche est appliquee simultanement sur `libelle`, `reference article` et `marque`
