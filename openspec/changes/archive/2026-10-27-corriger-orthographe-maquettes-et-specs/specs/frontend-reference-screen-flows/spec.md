## ADDED Requirements

### Requirement: Les libellés visibles des maquettes MUST respecter l'orthographe française
Les textes affichés aux utilisateurs dans les maquettes de référence MUST utiliser l'orthographe française correcte, y compris les accents, la cédille (`ç`), les apostrophes et la casse attendue.

#### Scenario: Libellé nécessitant un accent ou une cédille
- **WHEN** un libellé de maquette contient un mot français dont la graphie correcte comporte un accent ou une cédille
- **THEN** la maquette affiche ce mot avec sa graphie correcte, sans suppression du signe diacritique

#### Scenario: Validation d'un écran corrigé
- **WHEN** l'équipe relit un écran du référentiel de maquettes
- **THEN** aucun libellé utilisateur de cet écran n'emploie une variante orthographique fautive liée à l'absence d'accent ou de cédille

### Requirement: Les libellés partagés entre maquettes et specs MUST rester cohérents
Lorsqu'un même libellé fonctionnel est référencé dans une maquette et dans les specs OpenSpec de flux d'écrans, la même orthographe française MUST être utilisée dans les deux sources.

#### Scenario: Alignement d'un libellé entre artefacts
- **WHEN** un libellé fonctionnel est présent à la fois dans une maquette et dans une spec OpenSpec associée
- **THEN** les deux occurrences utilisent la même graphie française correcte
