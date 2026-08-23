## Purpose

Garantir que chaque use-case fonctionnel legacy fait l'objet d'une décision explicite et vérifiable dans les artefacts de refonte, afin qu'aucun comportement historique ne disparaisse par omission.

## Requirements

### Requirement: La couverture legacy MUST être matérialisée par une matrice unique
Le chantier de refonte MUST maintenir une matrice de couverture unique recensant l'intégralité des use-cases de la cartographie legacy, et MUST la traiter comme source de vérité des décisions de reprise.

#### Scenario: Exhaustivité de la matrice
- **WHEN** la matrice de couverture est évaluée par rapport à la cartographie des use-cases legacy
- **THEN** chaque identifiant de use-case legacy figure dans la matrice exactement une fois

#### Scenario: Use-case legacy ajouté à la cartographie
- **WHEN** un nouvel identifiant de use-case est ajouté à la cartographie legacy
- **THEN** le contrôle de couverture signale la matrice comme incomplète tant que ce use-case n'y est pas décidé

### Requirement: Chaque use-case legacy MUST porter une décision de reprise explicite
Chaque entrée de la matrice MUST porter une décision parmi un ensemble fermé de valeurs distinguant la reprise, l'abandon assumé, la décision de principe à périmètre ouvert et l'arbitrage en attente. L'absence de décision MUST être traitée comme une anomalie et MUST NOT être interprétée comme un abandon implicite.

#### Scenario: Décision de reprise
- **WHEN** un use-case legacy est repris dans la solution cible
- **THEN** son entrée porte la décision de reprise et référence au moins un artefact cible qui le couvre

#### Scenario: Décision d'abandon
- **WHEN** un use-case legacy n'est pas repris dans la solution cible
- **THEN** son entrée porte la décision d'abandon accompagnée d'une justification métier lisible

#### Scenario: Décision de principe rendue avec périmètre à cadrer
- **WHEN** il est décidé qu'un use-case legacy n'est pas abandonné, mais que son périmètre exact reste à déterminer par un travail de cadrage
- **THEN** son entrée porte une décision de cadrage accompagnée de la nature du cadrage attendu, et MUST NOT être confondue avec un arbitrage non rendu

#### Scenario: Arbitrage non encore rendu
- **WHEN** la reprise d'un use-case legacy n'est pas encore arbitrée
- **THEN** son entrée porte une décision d'arbitrage en attente, qui MUST NOT être considérée comme un état final

#### Scenario: Décision absente
- **WHEN** une entrée de la matrice ne porte aucune décision
- **THEN** le contrôle de couverture signale une anomalie

### Requirement: La traçabilité de reprise MUST couvrir les quatre niveaux d'artefacts
Un use-case repris MUST être traçable à travers la chaîne complète des artefacts de refonte: exigence produit, spécification de capacité, et représentation d'interface lorsque le use-case comporte un parcours utilisateur. Un niveau non renseigné MUST rester visible comme descente incomplète.

#### Scenario: Reprise entièrement descendue
- **WHEN** un use-case repris est couvert à la fois par une exigence produit, une spécification et une maquette
- **THEN** son entrée référence les trois niveaux et le contrôle ne signale aucune descente incomplète

#### Scenario: Reprise décidée mais non descendue
- **WHEN** un use-case repris est couvert par une exigence produit sans spécification ni maquette correspondante
- **THEN** le contrôle de couverture signale une descente incomplète pour ce use-case

#### Scenario: Reprise sans aucun rattachement
- **WHEN** un use-case porte la décision de reprise sans référencer aucun artefact cible
- **THEN** le contrôle de couverture signale une anomalie bloquante

#### Scenario: Use-case sans parcours utilisateur
- **WHEN** un use-case repris relève d'un comportement système sans interface dédiée
- **THEN** l'absence de maquette est déclarée comme non applicable et n'est pas signalée comme descente incomplète

### Requirement: Les références de la matrice MUST pointer des artefacts existants
Les rattachements déclarés dans la matrice MUST désigner des artefacts réellement présents dans le dépôt, afin que la matrice ne puisse pas certifier une couverture disparue.

#### Scenario: Référence valide
- **WHEN** une entrée référence une exigence produit, une spécification ou une maquette
- **THEN** le contrôle vérifie l'existence effective de l'artefact référencé

#### Scenario: Référence rompue après suppression d'un artefact
- **WHEN** un artefact référencé par la matrice est supprimé ou renommé
- **THEN** le contrôle de couverture signale la référence comme rompue

### Requirement: Le contrôle de couverture MUST être exécutable et reproductible
Le chantier MUST fournir un moyen d'exécution du contrôle de couverture, sans dépendance à un service externe, produisant un verdict exploitable et un statut d'échec en cas d'anomalie.

#### Scenario: Exécution locale du contrôle
- **WHEN** un contributeur exécute le contrôle de couverture
- **THEN** il obtient la liste des anomalies détectées par catégorie et un statut de sortie en échec si au moins une anomalie bloquante est présente

#### Scenario: Couverture saine
- **WHEN** aucune anomalie n'est détectée
- **THEN** le contrôle se termine en succès et confirme que chaque use-case legacy porte une décision cohérente

### Requirement: Les divergences de comportement MUST être tracées comme écarts assumés
Lorsqu'un use-case est repris sous un comportement différent du comportement historique, la matrice MUST expliciter cet écart, afin qu'une reprise partielle ne soit pas confondue avec une reprise à l'identique.

#### Scenario: Reprise avec restriction nouvelle
- **WHEN** un use-case repris introduit une restriction absente du comportement legacy
- **THEN** son entrée documente l'écart et sa justification

#### Scenario: Libellé identique recouvrant un comportement différent
- **WHEN** un use-case cible porte une dénomination proche du legacy mais un périmètre fonctionnel différent
- **THEN** son entrée signale explicitement la divergence de périmètre

### Requirement: La couverture legacy MUST être revue avant clôture d'un jalon de refonte
La matrice MUST être revue périodiquement afin de convertir les arbitrages en attente en décisions, et l'existence d'arbitrages non rendus MUST être visible dans le pilotage du chantier.

#### Scenario: Arbitrages en attente au jalon
- **WHEN** un jalon de refonte est évalué pour clôture
- **THEN** les use-cases en arbitrage en attente sont restitués comme points de décision ouverts

#### Scenario: Décision d'arbitrage rendue
- **WHEN** une revue tranche la reprise ou l'abandon d'un use-case en attente
- **THEN** la matrice est mise à jour avec la décision finale et sa justification
