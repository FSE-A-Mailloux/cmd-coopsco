# Périmètre legacy de référence (convention de chantier)

Ce document est la source de vérité du corpus legacy utilisé pendant la refonte et
pendant les travaux assistés par GitHub Copilot. Il est référencé par le changement
OpenSpec `scope-legacy-context` et par la capability `legacy-refactor-context-curation`.

## 1. Périmètre inclus

Le corpus de référence couvre le code métier réellement exploitable pour la migration.

| Zone | Chemin | Contenu |
| --- | --- | --- |
| Application frontend | `legacy/app/**` | Écrans métier, composants AngularJS, routage, services applicatifs |
| Services backend | `legacy/ws/**` (hors exclusions) | Points d'entrée HTTP, logique applicative PHP, classes techniques métier |

Détail des sous-ensembles inclus :

- `legacy/app/app.js`, `legacy/app/services.js` — amorçage, routage, services transverses.
- `legacy/app/components/**` — un dossier par écran métier (`.js` comportement, `.html` vue).
- `legacy/app/login/**`, `legacy/app/menu/**` — authentification et navigation.
- `legacy/ws/phpWs/**` — endpoints métier (`*Manage.php`, `login.php`, `execRequest.php`, …).
- `legacy/ws/phpClasses/**` — classes support utilisées par les endpoints.
- `legacy/ws/mail/**` — gabarits de communication transactionnelle.
- `legacy/ws/wsApp.php`, `legacy/ws/notifHelloAsso.php`, `legacy/ws/configuration.php` (hors secrets).

## 2. Exclusions obligatoires

Les chemins suivants sont **exclus sans exception implicite** du corpus de référence.
Ils n'apportent pas de signal métier et diluent le contexte fourni à Copilot.

| Chemin exclu | Motif |
| --- | --- |
| `legacy/ws/vendor/**` | Dépendances tierces installées par Composer |
| `legacy/libs/**` | Bibliothèques frontend packagées (AngularJS, Bootstrap, jQuery, CKEditor, …) |
| `legacy/imgs/**` | Assets images |
| `legacy/assets/**` (si présent) | Assets statiques divers |
| `legacy/style/**` | Feuilles de style et thèmes, sans logique métier |

Règle de lecture : tout chemin non listé en section 1 est hors corpus par défaut ;
tout chemin listé en section 2 est hors corpus même s'il est situé sous une zone incluse.

## 3. Garde-fous outillage et dépôt

- `.gitignore` exclut du versionnement les dépendances installées : `legacy/ws/vendor`
  et `legacy/libs/`.
- `legacy/imgs/**` et `legacy/style/**` restent versionnés (assets nécessaires à
  l'exécution de l'application legacy) mais sont hors corpus de refonte.
- `.copilotignore` et `.continueignore` reprennent l'intégralité des exclusions de la
  section 2 afin que les zones exclues ne soient jamais proposées comme contexte.
- Aucun ticket de refonte ne doit citer un fichier des zones exclues comme source de
  référence. Un ticket qui en a besoin passe par la procédure d'exception (section 4).

Vérification rapide avant ouverture d'un lot :

```bash
# Doit ne rien retourner : aucune source exclue référencée par le backlog
grep -rniE "legacy/(ws/vendor|libs|imgs|assets|style)/" docs/legacy-migration-backlog.md
```

## 4. Procédure d'exception de périmètre

Toute évolution du corpus (ajout ou retrait d'un chemin) suit cette procédure.

1. **Demande** — ouvrir une demande d'exception au format ci-dessous.
2. **Analyse** — un second membre de l'équipe de refonte évalue l'impact contexte/bruit.
3. **Décision** — validation ou refus explicite en revue d'équipe de refonte.
4. **Historisation** — la décision acceptée est ajoutée au journal (section 5) et les
   sections 1/2 ainsi que les fichiers d'ignore sont mis à jour dans le même commit.

### Modèle de demande d'exception

```markdown
## Demande d'exception de périmètre legacy

- **Chemin concerné**: legacy/<chemin>
- **Sens**: ajout au corpus | retrait du corpus
- **Demandeur**: <nom>
- **Justification métier**: <pourquoi ce chemin est (in)dispensable à la migration>
- **Use-cases impactés**: <UC-xxx-n, …>
- **Risque si refusé / accepté**: <bruit contextuel, perte de compréhension, régression>
- **Alternative évaluée**: <résumé fonctionnel, capture, doc existante…>
- **Décision**: acceptée | refusée — le <date> par <validateur>
```

### Exemple de demande d'exception

```markdown
## Demande d'exception de périmètre legacy

- **Chemin concerné**: legacy/libs/ckeditor
- **Sens**: ajout au corpus
- **Demandeur**: équipe refonte — lot « Éditions & modèles HTML »
- **Justification métier**: l'écran `modeleHtmlGest` dépend de la configuration
  d'initialisation de l'éditeur pour reproduire les contraintes de saisie.
- **Use-cases impactés**: UC-EDI-1, UC-EDI-2
- **Risque si accepté**: réintroduction d'un volume important de code tiers dans le contexte.
- **Alternative évaluée**: documenter la configuration utilisée depuis
  `legacy/app/components/modeleHtmlGest/modeleHtmlGest.js`, qui porte l'appel
  d'initialisation réel.
- **Décision**: refusée — alternative retenue, l'exclusion `legacy/libs/**` est maintenue.
```

## 5. Journal des décisions de périmètre

| Date | Chemin | Sens | Décision | Validé par |
| --- | --- | --- | --- | --- |
| 2026-08-23 | `legacy/app/**`, `legacy/ws/**` | ajout | Acceptée — périmètre initial | Équipe refonte |
| 2026-08-23 | `legacy/ws/vendor/**`, `legacy/libs/**`, `legacy/imgs/**`, `legacy/assets/**`, `legacy/style/**` | retrait | Acceptée — exclusions minimales obligatoires | Équipe refonte |

## 6. Documents liés

- `docs/legacy-use-cases.md` — cartographie des use-cases fonctionnels legacy.
- `docs/legacy-migration-ticket-template.md` — format standard de ticket de refonte.
- `docs/legacy-migration-backlog.md` — backlog lotti par verticale métier.
- `openspec/changes/scope-legacy-context/` — proposal, design et spec de ce cadrage.
