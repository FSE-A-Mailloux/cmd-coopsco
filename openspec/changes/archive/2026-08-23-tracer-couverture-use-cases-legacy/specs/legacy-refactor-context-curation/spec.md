## MODIFIED Requirements

### Requirement: Chaque tâche de refonte MUST être traçable vers ses sources legacy
Toute tâche de migration MUST référencer explicitement les fichiers legacy sources qui justifient son comportement attendu.
La traçabilité MUST également être vérifiable dans le sens inverse: chaque use-case fonctionnel legacy MUST être rattaché à une décision de reprise, afin que la justification descendante s'accompagne d'une garantie de complétude ascendante.

#### Scenario: Ticket de migration vérifiable
- **WHEN** une tâche de refonte est créée ou affinée
- **THEN** elle documente une liste de fichiers legacy de référence permettant de relier l'implémentation cible au comportement historique

#### Scenario: Complétude ascendante des use-cases legacy
- **WHEN** l'état d'avancement de la refonte est évalué
- **THEN** il est possible d'établir, pour chaque use-case de la cartographie legacy, s'il est repris, abandonné ou en attente d'arbitrage

#### Scenario: Use-case legacy sans rattachement
- **WHEN** un use-case de la cartographie legacy n'est rattaché à aucune décision de reprise
- **THEN** il est signalé comme trou de couverture et MUST NOT être considéré comme abandonné par défaut
