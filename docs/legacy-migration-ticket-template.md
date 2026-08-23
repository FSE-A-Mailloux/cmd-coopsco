# Format standard d'un ticket de refonte

Tout ticket doit être traçable vers ses sources legacy.
Le périmètre autorisé est défini par `docs/legacy-scope.md`.

Le format dépend de l'horizon de travail, défini dans
`docs/legacy-migration-backlog.md` :

- **H1 — en cours** : produire la spécification et les maquettes. Le ticket livre des
  exigences, des scénarios et des écrans. Les sources legacy sont des **preuves
  comportementales**, pas des modèles à reproduire.
- **H2 — ultérieur** : construire l'application à partir de la spécification et des
  maquettes, devenues source de vérité. Le ticket livre du code, et les sources legacy
  redeviennent une référence de comparaison en recette.

## Règles obligatoires

1. Le ticket référence **1 à 3 fichiers legacy source** (jamais 0, jamais plus de 3).
   Au-delà de 3 fichiers, le ticket est trop large : le découper.
2. Les chemins cités sont relatifs à la racine du dépôt et existent réellement.
3. Aucun chemin issu des zones exclues (`legacy/ws/vendor/**`, `legacy/libs/**`,
   `legacy/imgs/**`, `legacy/assets/**`, `legacy/style/**`) sauf exception validée.
4. Le ticket cite le ou les use-cases correspondants de `docs/legacy-use-cases.md`.
5. Le ticket appartient à une verticale métier du backlog
   (`docs/legacy-migration-backlog.md`).
6. Le ticket ne porte aucun use-case décidé `ABANDONNÉ` dans
   `docs/legacy-coverage-matrix.md`, et reste suspendu si un use-case est `À CADRER`.

## Modèle H1 — spécification et maquettes

```markdown
### [<VERTICALE>] <Titre orienté comportement métier>

- **Use-cases couverts**: UC-<CODE>-<n>
- **Sources legacy (entrée, 1 à 3)**:
  - `legacy/app/components/<composant>/<composant>.js`
  - `legacy/ws/phpWs/<endpoint>.php`
- **Comportement historique observé**: <règles métier constatées dans les sources ci-dessus>
- **Livrables**:
  - Spec: <capacité de `openspec/specs/`> — <exigence à rédiger ou à compléter>
  - Maquette: <écran du manifeste, ou écran à créer>
- **Critères d'acceptation (phase spec & maquettes)**:
  - [ ] L'exigence est rédigée avec ses scénarios `WHEN` / `THEN`
  - [ ] Chaque règle métier retenue est couverte par un scénario
  - [ ] L'écran figure au manifeste `maquettes/manifest/screens.json` et illustre les scénarios
  - [ ] La matrice ne signale plus de descente incomplète pour ce use-case
- **Écarts assumés vs legacy**: <aucun | description + renvoi à l'arbitrage>
```

Le dernier critère d'acceptation est **vérifiable automatiquement** :

```powershell
pwsh -File scripts/check-legacy-coverage.ps1
```

Un ticket ne peut donc pas être déclaré terminé si la matrice de couverture le contredit.

## Modèle H2 — construction

Réservé à la phase de construction. Le champ « Cible de refonte » désigne le module ou le
service cible, et les critères d'acceptation redeviennent des critères de recette
comportementale.

```markdown
### [<VERTICALE>] <Titre orienté comportement métier>

- **Use-cases couverts**: UC-<CODE>-<n>
- **Sources legacy (référence de recette, 1 à 3)**: …
- **Spécification de référence**: <capacité + exigence de `openspec/specs/`>
- **Maquette de référence**: <écran du manifeste>
- **Cible de refonte**: <module / écran / service cible>
- **Critères d'acceptation**:
  - [ ] <comportement conforme à l'exigence spécifiée>
- **Écarts assumés vs legacy**: <aucun | description + justification>
```

En H2, la conformité s'apprécie **par rapport à la spécification**, et non par rapport au
legacy : ce dernier ne sert qu'à détecter un comportement qui aurait été oublié en H1.

## Exemple rempli (lot H1)

```markdown
### [COM] Composer et envoyer un courriel depuis un gabarit

- **Use-cases couverts**: UC-MAIL-1
- **Sources legacy (entrée, 1 à 3)**:
  - `legacy/app/components/mailGest/mailGest.js`
  - `legacy/ws/phpWs/mailManage.php`
  - `legacy/ws/phpClasses/appMail.php`
- **Comportement historique observé**: le gestionnaire rédige un message, choisit un
  gabarit, prévisualise le rendu fusionné puis envoie à un utilisateur, un groupe ou une
  adresse libre.
- **Livrables**:
  - Spec: `templated-mail-and-document-generation` — couverte par `FR-075` à `FR-077`.
  - Maquette: écran de composition et de prévisualisation, inexistant à ce jour.
- **Critères d'acceptation (phase spec & maquettes)**:
  - [ ] Les trois modes de ciblage sont représentés.
  - [ ] La prévisualisation du rendu fusionné précède l'envoi.
  - [ ] La matrice ne signale plus de descente incomplète pour `UC-MAIL-1`.
- **Écarts assumés vs legacy**: exclusion des destinataires désinscrits et plafond annuel,
  arbitrés au PRD §14.1 point 4.
```

## Contrôle d'adoption

Un ticket est recevable en revue de lot si, et seulement si :

- [ ] la section « Sources legacy » contient entre 1 et 3 chemins existants ;
- [ ] aucun chemin ne provient d'une zone exclue ;
- [ ] au moins un use-case de `docs/legacy-use-cases.md` est référencé ;
- [ ] aucun use-case référencé n'est décidé `ABANDONNÉ` ni `À CADRER` ;
- [ ] en H1, les livrables désignent une capacité et un écran ;
- [ ] les critères d'acceptation décrivent un comportement, pas une implémentation.
