## 1. Initialisation de la matrice de couverture

- [x] 1.1 Créer `docs/legacy-coverage-matrix.md` avec une ligne par use-case de `docs/legacy-use-cases.md` et vérifier que les 57 identifiants `UC-*` y figurent chacun exactement une fois
- [x] 1.2 Définir dans l'en-tête du document les valeurs de décision autorisées, la convention de rattachement vers le PRD, les spécifications et les maquettes, ainsi que la valeur de non-applicabilité maquette, et vérifier que la convention est compréhensible sans explication orale
- [x] 1.3 Reporter le classement issu de la revue croisée (reprise descendue, reprise non descendue, absence de trace, obsolescence probable) et vérifier que chaque ligne porte une décision non vide
- [x] 1.4 Renseigner les rattachements des use-cases repris vers les exigences `FR-*`, `FACT-*` ou `COTIS-*`, les capacités de `openspec/specs/**` et les écrans de `maquettes/manifest/screens.json`, et vérifier que chaque ligne en reprise référence au moins un artefact

## 2. Traçabilité des divergences

- [x] 2.1 Documenter dans la matrice l'écart sur la désinscription (désactivation de compte legacy contre désinscription de communication cible) et vérifier que la divergence de périmètre est explicite
- [x] 2.2 Documenter l'écart sur l'annulation de commande (restriction nouvelle en cas de paiement enregistré) et vérifier que la conséquence sur le parcours de correction est énoncée
- [x] 2.3 Documenter l'écart sur les requêtes paramétrables (perte d'autonomie d'édition côté administrateur) et vérifier qu'il est relié à l'arbitrage sur le domaine des éditions et exports

## 3. Contrôle automatisable de la matrice

- [x] 3.1 Implémenter un contrôle vérifiant l'exhaustivité de la matrice par rapport aux identifiants de `docs/legacy-use-cases.md` et vérifier qu'un use-case retiré de la matrice fait échouer le contrôle
- [x] 3.2 Étendre le contrôle à la validité des décisions et vérifier qu'une décision vide ou hors des valeurs autorisées fait échouer le contrôle
- [x] 3.3 Étendre le contrôle aux rattachements et vérifier qu'une ligne en reprise sans aucun artefact référencé fait échouer le contrôle
- [x] 3.4 Étendre le contrôle à l'existence des artefacts référencés (fichier de spécification, écran du manifeste, identifiant d'exigence du PRD) et vérifier qu'une référence rompue est détectée
- [x] 3.5 Produire une restitution des descentes incomplètes (reprise décidée sans spécification ou sans maquette applicable) et vérifier que les use-cases concernés sont listés distinctement des anomalies bloquantes
- [x] 3.6 Documenter la commande d'exécution du contrôle dans `docs/legacy-coverage-matrix.md` et vérifier qu'elle s'exécute sans dépendance externe, sur le modèle de la commande de vérification existante de `docs/legacy-use-cases.md`

## 4. Revue d'arbitrage des écarts ouverts

- [x] 4.1 Instruire l'arbitrage sur le domaine « Éditions & exports » au niveau du domaine entier et vérifier que la décision retenue est reportée sur les six use-cases concernés avec sa justification
- [x] 4.2 Instruire l'arbitrage sur le référentiel fournisseurs et vérifier que la décision traite la cohérence avec les exigences qui consomment déjà l'entité fournisseur
- [x] 4.3 Instruire conjointement l'annulation de commande payée et l'annulation d'un règlement, et vérifier que la décision décrit un parcours de correction complet ou assume explicitement son absence
- [x] 4.4 Instruire l'arbitrage sur la méta-configuration applicative (menu, modules, référentiel de composants) et vérifier que l'abandon éventuel est justifié par le modèle d'architecture cible
- [x] 4.5 Instruire l'arbitrage sur la consultation des historiques d'audit et d'anomalies et vérifier que la décision est cohérente avec le persona « Support » défini dans le PRD
- [x] 4.6 Instruire les use-cases restants sans trace (forcer le changement de mot de passe, renvoi du code de validation, génération en lot des documents de commandes réglées par carte, pages d'accueil contextuelles, gestion de la liste de diffusion d'un groupe) et vérifier qu'aucun use-case ne reste en arbitrage en attente à l'issue de la revue

> **Réserve sur 4.6** : le critère « aucun use-case ne reste en arbitrage en attente »
> n'est pas atteint. `UC-AUTH-8` (désinscription / désactivation de compte) n'a pas été
> instruit par la revue et reste en `À TRANCHER`. Il est reporté comme question ouverte
> au PRD §14.2 et signalé par le contrôle de couverture.

## 5. Consolidation des décisions dans les artefacts

- [x] 5.1 Reporter dans `openspec/prd-rewrite-agnostique.md` les exigences produit issues des décisions de reprise nouvelles et vérifier que la section de traçabilité du PRD reste cohérente avec les capacités
- [x] 5.2 Aligner `docs/legacy-migration-backlog.md` sur les décisions de la matrice et vérifier qu'aucun ticket ne référence un use-case décidé comme abandonné
- [x] 5.3 Ouvrir un changement OpenSpec distinct par ensemble cohérent de use-cases repris non encore spécifiés et vérifier que chaque changement ouvert est référencé depuis la matrice

## 6. Ancrage du contrôle dans le cycle de travail

- [x] 6.1 Rattacher le contrôle de couverture à l'intégration continue et vérifier qu'une anomalie bloquante fait échouer la vérification automatisée
- [x] 6.2 Définir la règle de revue au jalon (restitution des arbitrages en attente avant clôture) et vérifier qu'elle est documentée dans la matrice

## 7. Validation du changement

- [x] 7.1 Exécuter `openspec validate "tracer-couverture-use-cases-legacy" --type change` et vérifier que la validation est sans erreur
- [x] 7.2 Vérifier la readiness avec `openspec status --change tracer-couverture-use-cases-legacy` et confirmer que `proposal`, `specs`, `design` et `tasks` sont présents
