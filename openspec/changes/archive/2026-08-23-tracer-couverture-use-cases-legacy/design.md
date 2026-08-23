## Context

Voir `proposal.md` pour la motivation.

Le chantier dispose de quatre artefacts qui décrivent le produit à des niveaux différents, mais qui ne sont reliés qu'en partie:

```
  docs/legacy-use-cases.md          57 use-cases UC-<DOMAINE>-<n>
            |
            X  aucun lien formalisé
            v
  openspec/prd-rewrite-agnostique.md   ~90 exigences FR / FACT / COTIS
            |
            |  §13 « Traçabilité OpenSpec → PRD » (lien spec -> FR, partiel)
            v
  openspec/specs/**                 13 capacités, ~113 exigences
            ~
            ~  lien implicite, par vocabulaire uniquement
            v
  maquettes/**                      24 écrans, manifest screens.json
```

Le seul rattachement explicite vers les use-cases legacy se trouve dans `docs/legacy-migration-backlog.md`, qui référence 11 use-cases sur 57.

Constat d'entrée de la revue manuelle:

| Situation | Volume | Nature du risque |
| --- | --- | --- |
| Couvert et descendu jusqu'à la maquette | ~32 | Faible |
| Décidé au PRD, non descendu en spec ni maquette | ~6 | Perte silencieuse en cours de route |
| Absent du PRD, des specs et des maquettes | ~11 | Oubli, ou abandon jamais formulé |
| Vraisemblablement obsolète par construction | ~6 | Abandon légitime mais non tracé |

Trois divergences sémantiques ont également été relevées, où un libellé identique recouvre deux comportements différents. Elles sont plus dangereuses qu'un trou, car elles échappent à toute recherche par mot-clé.

Contraintes:
- Le contrôle doit rester exécutable sans infrastructure externe, dans la continuité de la commande de vérification déjà présente en fin de `docs/legacy-use-cases.md`.
- Les spécifications cibles sont volontairement agnostiques et reformulent le vocabulaire legacy; le contrôle ne peut donc pas reposer sur une correspondance textuelle automatique.
- La cartographie legacy est figée et fait autorité; elle n'est pas modifiée par ce changement.

## Goals / Non-Goals

**Goals:**
- Rendre visible, pour chaque use-case legacy, s'il est repris, abandonné ou non arbitré.
- Distinguer explicitement « décidé » de « descendu jusqu'à la spécification et la maquette ».
- Rendre l'abandon d'un comportement legacy possible, mais jamais silencieux.
- Fournir un contrôle automatisable qui empêche la matrice de dériver par rapport au dépôt.
- Identifier et instruire les écarts ouverts, sans les résoudre dans ce changement.

**Non-Goals:**
- Rédiger les spécifications manquantes pour les use-cases identifiés comme trous: ce changement les révèle, des changements ultérieurs les traitent.
- Modifier `docs/legacy-use-cases.md`, qui reste la source d'entrée du contrôle.
- Automatiser la détection sémantique de couverture: la décision de reprise reste un acte humain.
- Réintroduire des identifiants legacy dans les spécifications cibles, qui doivent rester agnostiques.

## Decisions

### Décision 1 - Matrice externe plutôt qu'annotations dans les spécifications
**Choix:** concentrer la traçabilité dans un document dédié, `docs/legacy-coverage-matrix.md`, plutôt que d'annoter les spécifications et les maquettes avec des identifiants `UC-*`.

**Pourquoi:** les spécifications cibles sont explicitement agnostiques et reformulées; y injecter des identifiants legacy les recouplerait au passé et contredirait l'exigence existante « Les détails d'implémentation frontend legacy MUST être traités comme legacy-only ». Un document externe isole la dette de migration et disparaîtra naturellement à la fin du chantier.

**Alternatives considérées:**
- Annotations en ligne dans chaque spécification et dans `screens.json`: détection d'orphelins immédiate et sans dérive possible, mais pollution durable des artefacts cibles et couplage au vocabulaire legacy.
- Aucun document, revue orale périodique: coût nul, mais aucune mémoire des décisions et répétition de la revue à chaque jalon.

### Décision 2 - Matrice à quatre colonnes, séparant décision et descente
**Choix:** chaque ligne porte une décision humaine et trois colonnes de rattachement (exigence produit, spécification, maquette).

**Pourquoi:** c'est la seule structure qui distingue les deux natures de risque observées. Un use-case décidé au PRD mais absent des spécifications n'est ni couvert ni oublié: il est en cours de descente, et c'est exactement l'état qui se perd sans outil. Une matrice binaire « couvert / non couvert » aurait masqué les six cas de ce type.

**Alternatives considérées:**
- Matrice à deux colonnes (use-case, statut): plus simple à tenir, mais incapable de distinguer une intention d'une réalisation.
- Matrice reliant directement use-case et maquette: ignore le PRD, qui s'est révélé être l'artefact le plus complet du chantier.

### Décision 3 - Ensemble fermé de décisions, l'absence valant anomalie
**Choix:** quatre valeurs de décision uniquement — reprise, abandon, cadrage en cours, arbitrage en attente — et traitement de la valeur vide comme une anomalie bloquante.

**Pourquoi:** le mode de défaillance principal n'est pas la mauvaise décision, c'est la décision jamais prise. Interdire le vide force l'arbitrage à devenir un objet visible dans le pilotage plutôt qu'un non-dit.

La valeur de cadrage a été ajoutée après la première revue d'arbitrage: plusieurs décisions rendues étaient des décisions de principe à périmètre ouvert (« le domaine est retenu, mais une expression de besoin doit déterminer ce qui est conservé »). Les enregistrer comme arbitrages en attente aurait effacé la décision prise; les enregistrer comme reprises aurait affirmé une couverture inexistante. Ces deux erreurs sont exactement celles que la matrice doit prévenir.

**Alternatives considérées:**
- Statut libre en texte: plus souple, mais non contrôlable et sujet aux formulations divergentes.
- Ajout d'un statut « partiellement repris »: recouvre en réalité la combinaison décision de reprise et descente incomplète, déjà exprimable par les colonnes de rattachement.
- Conserver trois valeurs et noter le cadrage en commentaire: rend l'information invisible au contrôle et donc non pilotable.

### Décision 4 - Contrôle structurel uniquement, jamais sémantique
**Choix:** le contrôle vérifie l'exhaustivité, la validité des décisions, la présence de rattachements sur les reprises et l'existence des artefacts référencés. Il ne juge jamais si un artefact couvre réellement le comportement.

**Pourquoi:** la vérification sémantique est hors de portée d'un script et donnerait une fausse assurance. Le contrôle garantit que la matrice ne ment pas sur le dépôt; la revue humaine garantit qu'elle ne ment pas sur le métier. Confondre les deux rôles affaiblirait les deux.

**Alternatives considérées:**
- Correspondance par mots-clés entre use-cases et spécifications: expérimentée pendant l'exploration, elle a produit à la fois des faux positifs et des faux négatifs, précisément parce que les spécifications reformulent le vocabulaire.

### Décision 5 - Colonne maquette explicitement non applicable
**Choix:** autoriser une valeur « non applicable » sur le rattachement maquette, pour les use-cases purement système.

**Pourquoi:** plusieurs use-cases legacy sont des comportements serveur sans interface. Sans cette valeur, ils apparaîtraient en permanence comme descentes incomplètes et le bruit finirait par faire ignorer le contrôle.

**Alternatives considérées:**
- Exiger une maquette pour tout: bruit permanent, perte de confiance dans le signal.
- Laisser la case vide: indistinguable d'un oubli de saisie.

### Décision 6 - Les écarts ouverts sont instruits, pas tranchés ici
**Choix:** la matrice est initialisée avec des arbitrages en attente sur les points non tranchés, et ce changement organise la revue plutôt que d'imposer les décisions.

**Pourquoi:** ces arbitrages ont une portée produit réelle. Les trancher unilatéralement dans un changement d'outillage reviendrait à décider du périmètre fonctionnel sous couvert de traçabilité.

## Points d'arbitrage identifiés

Ces points sont issus de la revue croisée. Ils ont été instruits en revue et les décisions rendues figurent ci-dessous. Ils sont classés par valeur décroissante.

### Arbitrage 1 - Le domaine « Éditions & exports » est-il volontairement supprimé ?
Les six use-cases du domaine (paramétrage de listes d'impression, export de liste configurable, gestion et génération d'états de reporting, gestion des modèles HTML, requêtes paramétrables) n'ont aucune exigence produit correspondante, à l'exception des requêtes référencées, reprises sous une forme reformulée où l'autonomie de l'administrateur disparaît.

C'est le seul domaine legacy qui disparaît intégralement. Pris isolément chaque abandon se défend; cumulés, ils suppriment toute capacité de reporting ad hoc dans la solution cible. La question à trancher n'est pas use-case par use-case, mais au niveau du domaine.

**Décision rendue:** le domaine est **repris en partie**. Une phase d'expression de besoin doit déterminer quelles éditions sont conservées. Les six use-cases passent en cadrage, aucun n'est abandonné à ce stade.

### Arbitrage 2 - Faut-il un référentiel fournisseurs ?
Le fournisseur est omniprésent comme attribut: rattachement d'article, prix fournisseur, lot fournisseur, consolidation par fournisseur, écrans de tarifs et de consolidation. Mais aucune exigence produit, aucune spécification et aucune maquette ne couvrent la gestion du référentiel lui-même. Le legacy disposait d'un use-case dédié.

L'incohérence est frappante: la solution cible consomme partout une entité qu'elle ne sait pas créer.

**Décision rendue:** **pas de référentiel fournisseurs**. L'entité fournisseur n'est pas gérée dans la solution cible. En revanche, la dénomination « prix fournisseur HT » est conservée: elle désigne le prix d'achat de l'article, indépendamment de l'identité du fournisseur. La consolidation et le mode de tarification « HT + marge » restent donc en vigueur. Le retrait du rattachement fournisseur sur l'article et l'ajustement du vocabulaire des exigences concernées relèvent d'un changement dédié.

### Arbitrage 3 - Comment annule-t-on une commande déjà payée ?
Deux constats se combinent. D'une part, la spécification des statuts pose que l'annulation est refusée dès qu'un paiement est enregistré, ce qui est une restriction nouvelle par rapport au legacy. D'autre part, l'annulation d'un règlement enregistré, qui existait dans le legacy, n'apparaît dans aucun artefact cible.

Ensemble, ces deux éléments ferment le parcours de correction d'une erreur d'encaissement. Le sujet doit être traité comme un tout, et non comme deux use-cases indépendants.

**Décision rendue:** une commande réglée **peut** être annulée, avec **émission d'un avoir**. La restriction posée par la spécification des statuts est donc invalidée: elle n'était pas conforme à la réglementation applicable, pas davantage que le comportement legacy qui annulait sans produire de pièce comptable. Le traitement d'une saisie d'encaissement erronée reste à cadrer: il n'est pas établi qu'il relève du même mécanisme que l'annulation commerciale.

### Arbitrage 4 - La méta-configuration applicative est-elle abandonnée ?
Le legacy est structuré comme un framework générique piloté par la base de données: le menu, les modules et le référentiel des composants y sont des données administrables. La solution cible calcule le menu à partir des permissions et traite les composants comme du code.

Ces use-cases disparaissent donc vraisemblablement par construction, mais aucun artefact ne le formule. Il s'agit ici de tracer un abandon assumé plutôt que d'ouvrir un développement.

**Décision rendue:** menu, modules et référentiel de composants sont **abandonnés, obsolètes par construction**.

Le regroupement initial de cet arbitrage était erroné: il incluait la gestion des groupes, l'affectation des utilisateurs aux groupes et la gestion des fonctions. Ces trois use-cases ne relèvent pas du framework générique mais de l'administration des habilitations, portée par le persona « Administrateur fonctionnel » et déjà représentée par un écran de maquette. Ils sont **repris**, et les exigences produit correspondantes, absentes du PRD, doivent être ajoutées.

### Arbitrage 5 - Consultation des historiques d'audit et d'anomalies
La production des traces d'audit et la journalisation des anomalies sont couvertes par des exigences produit. Leur consultation ne l'est pas, alors que le legacy proposait deux écrans dédiés. Le persona « Support », défini dans le PRD comme traitant les incidents via traces et historiques, ne dispose d'aucun écran pour le faire.

**Décision rendue:** **repris avec règles modifiées**. La consultation du journal des actions applicatives est ouverte aux gestionnaires, et non réservée aux administrateurs comme dans le legacy. Les informations restituées sont le domaine, l'action, le contexte et la date-heure. La consultation des anomalies est reprise sur le même principe.

### Arbitrage 6 - Divergences sémantiques et use-cases isolés
Trois cas où un libellé identique recouvre des comportements différents:

- **Désinscription**: dans le legacy, le lien reçu par courriel désactive le compte. Dans la cible, la désinscription porte sur les communications de diffusion. La désactivation de compte à l'initiative de l'utilisateur n'a pas d'équivalent identifié.
- **Annulation de commande**: traité par l'arbitrage 3.
- **Requêtes paramétrables**: le legacy permet à l'administrateur d'éditer des requêtes stockées, la cible expose des requêtes identifiées par code fonctionnel, définies par le développement. Cet écart est rattaché au cadrage de l'arbitrage 1.

**Décisions rendues** sur les use-cases isolés:

| Use-case | Décision |
| --- | --- |
| Renvoi du code de validation | Repris. Exigence produit à créer. |
| Forcer le changement de mot de passe d'un utilisateur | Repris. Exigence produit à créer. |
| Génération en lot des documents de commandes réglées par carte | Rattaché au domaine « Éditions & exports », donc en cadrage. |
| Gestion de la liste de diffusion d'un groupe | Non repris à ce stade. |
| Pages d'accueil contextuelles | En cadrage: à préciser dans le PRD. |

**Point non instruit:** la désinscription reste en arbitrage en attente. La revue a porté sur l'audit et les anomalies, sans trancher le sort de la désactivation de compte à l'initiative de l'utilisateur. C'est le seul use-case du corpus dans cet état.

## Risks / Trade-offs

- **[Risque]** La matrice devient un document mort, mis à jour une fois puis oublié.
  **→ Mitigation** contrôle exécutable en échec dur, rattaché à l'intégration continue, de sorte que l'ajout d'un use-case ou la suppression d'un artefact référencé casse la vérification.

- **[Risque]** Le contrôle structurel donne un faux sentiment de sécurité, la couverture réelle n'étant pas vérifiée.
  **→ Mitigation** énoncer explicitement la limite dans le document lui-même, et maintenir la revue humaine comme condition de passage d'un jalon.

- **[Risque]** Les arbitrages en attente s'accumulent sans jamais être tranchés.
  **→ Mitigation** rendre leur nombre visible au jalon et interdire la clôture d'un jalon sans instruction de ces points.

- **[Trade-off]** Deux endroits à tenir à jour, la cartographie et la matrice.
  **→ Mitigation** la cartographie est figée et la matrice en dérive; le contrôle d'exhaustivité rend toute désynchronisation immédiatement visible.

- **[Trade-off]** L'initialisation de la matrice demande une revue complète des 57 use-cases.
  **→ Mitigation** la revue croisée déjà réalisée fournit un premier classement; l'effort résiduel porte sur les arbitrages, qui ont de la valeur en eux-mêmes.

## Migration Plan

1. Initialiser la matrice à partir de la cartographie legacy, en reportant le classement issu de la revue croisée.
2. Mettre en place le contrôle structurel et le faire passer sur l'état initial, arbitrages en attente compris.
3. Conduire la revue d'arbitrage sur les points identifiés, en commençant par le domaine « Éditions & exports » et le référentiel fournisseurs.
4. Convertir les décisions en changements OpenSpec distincts pour les use-cases retenus, et en justifications d'abandon pour les autres.
5. Rattacher le contrôle à l'intégration continue une fois la matrice stabilisée.

## Open Questions

- Le contrôle doit-il traiter une descente incomplète comme un simple avertissement, ou comme un échec dur une fois la phase de spécification déclarée terminée ?
- La matrice a-t-elle vocation à être archivée à la fin du chantier de refonte, ou à être conservée comme document d'histoire fonctionnelle du produit ?
