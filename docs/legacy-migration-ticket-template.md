# Format standard d'un ticket de refonte

Tout ticket de migration doit être traçable vers ses sources legacy.
Le périmètre autorisé est défini par `docs/legacy-scope.md`.

## Règles obligatoires

1. Le ticket référence **1 à 3 fichiers legacy source** (jamais 0, jamais plus de 3).
   Au-delà de 3 fichiers, le ticket est trop large : le découper.
2. Les chemins cités sont relatifs à la racine du dépôt et existent réellement.
3. Aucun chemin issu des zones exclues (`legacy/ws/vendor/**`, `legacy/libs/**`,
   `legacy/imgs/**`, `legacy/assets/**`, `legacy/style/**`) sauf exception validée.
4. Le ticket cite le ou les use-cases correspondants de `docs/legacy-use-cases.md`.
5. Le ticket appartient à une verticale métier du backlog
   (`docs/legacy-migration-backlog.md`).

## Modèle

```markdown
### [<VERTICALE>] <Titre orienté comportement métier>

- **Use-cases couverts**: UC-<CODE>-<n>
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/<composant>/<composant>.js`
  - `legacy/app/components/<composant>/<composant>.html`
  - `legacy/ws/phpWs/<endpoint>.php`
- **Comportement historique attendu**: <règles métier observées dans les sources ci-dessus>
- **Cible de refonte**: <module / écran / service cible>
- **Critères d'acceptation**:
  - [ ] <comportement 1 reproduit>
  - [ ] <comportement 2 reproduit>
  - [ ] <écart assumé documenté, le cas échéant>
- **Écarts assumés vs legacy**: <aucun | description + justification>
```

## Exemple rempli (lot pilote)

```markdown
### [COMMANDE] Initialiser une commande sur l'année en cours

- **Use-cases couverts**: UC-CMD-1
- **Sources legacy (1 à 3)**:
  - `legacy/app/components/fseCmdInit/fseCmdInit.js`
  - `legacy/app/components/fseCmdInit/fseCmdInit.html`
  - `legacy/ws/phpWs/fseCmdManage.php`
- **Comportement historique attendu**: création/reprise d'une commande rattachée à
  l'année FSE active et à l'utilisateur connecté, avant saisie des articles.
- **Cible de refonte**: module `commande` de `coopsco-v2`.
- **Critères d'acceptation**:
  - [ ] Une commande en cours existante est reprise plutôt que dupliquée.
  - [ ] L'année de rattachement provient du paramétrage et non d'une saisie libre.
  - [ ] Un utilisateur non autorisé ne peut pas initialiser de commande.
- **Écarts assumés vs legacy**: aucun.
```

## Contrôle d'adoption

Un ticket est recevable en revue de lot si, et seulement si :

- [ ] la section « Sources legacy » contient entre 1 et 3 chemins existants ;
- [ ] aucun chemin ne provient d'une zone exclue ;
- [ ] au moins un use-case de `docs/legacy-use-cases.md` est référencé ;
- [ ] les critères d'acceptation décrivent un comportement, pas une implémentation.
