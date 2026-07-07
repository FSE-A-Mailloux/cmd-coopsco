## Context

L'application est une SPA AngularJS (chargement dynamique de composants via `ocLazyLoad`) connectée à un point d'entrée backend unique (`ws/wsApp.php`) qui dispatch ensuite vers `ws/phpWs/<action>.php`.  
Le backend gère transaction SQL, validation de token, autorisations par groupe, journalisation d'anomalies, génération de documents (HTML2PDF), envoi de mails (PHPMailer) et intégration OAuth2 (HelloAsso).  
Le changement vise une **spécification as-is**: capturer le comportement actuel observé dans le code, sans refonte fonctionnelle.

## Goals / Non-Goals

**Goals:**
- Définir des capacités OpenSpec couvrant les flux fonctionnels majeurs réellement implémentés.
- Formaliser le contrat front/back (requête JSON, codes de retour, structure `response`).
- Rendre explicites les invariants métier (droits d'accès, règles de validation, cycle de vie commande FSE).
- Produire des scénarios testables (WHEN/THEN) pour servir de base de non-régression.

**Non-Goals:**
- Modifier le code applicatif, la base de données ou les endpoints existants.
- Corriger les faiblesses historiques de sécurité/architecture dans cette change.
- Introduire une nouvelle architecture (framework moderne, API REST versionnée, etc.).

## Decisions

### 1) Spécifier par domaines fonctionnels stables
**Décision:** créer 8 capacités alignées sur les frontières métier/techniques déjà présentes (shell frontend, dispatch WS, auth/session, autorisations/menu, cycle utilisateur, exécution de requêtes, workflow FSE, génération mail/PDF).  
**Pourquoi:** ces frontières existent explicitement dans les dossiers et modules (`app/*`, `ws/phpWs/*`, `ws/phpClasses/*`) et minimisent les ambiguïtés.  
**Alternative rejetée:** une unique grosse spec globale (moins maintenable, faible traçabilité).

### 2) Capturer le comportement observé, pas un comportement cible
**Décision:** écrire des exigences normatives qui reflètent strictement l'existant (codes `statusCode`, contexts `action/context`, règles de contrôle).  
**Pourquoi:** l'objectif est la documentation fiable de l'état courant pour sécuriser les évolutions ultérieures.  
**Alternative rejetée:** écrire une spec “idéale” qui mélange correction et documentation.

### 3) Utiliser des exigences “contract-first”
**Décision:** privilégier les exigences orientées contrat entre frontend et backend (payload, erreurs, transitions de composant, obligations de contrôle d'accès).  
**Pourquoi:** c'est le point de couplage principal du système et la zone la plus exposée aux régressions.

### 4) Décrire explicitement les intégrations externes
**Décision:** isoler les comportements dépendant de reCAPTCHA, SMTP/PHPMailer, HTML2PDF et HelloAsso OAuth2 dans les specs concernées.  
**Pourquoi:** ces dépendances portent des préconditions et des cas d'échec spécifiques qui influencent le comportement métier.

## Risks / Trade-offs

- **[Couverture partielle involontaire de certains contexts PHP]** → Mitigation: inclure les contexts structurants et les cas transverses; compléter ensuite par itérations ciblées.
- **[Comportements legacy parfois implicites]** → Mitigation: formuler les exigences à partir de points d'entrée et branches de code vérifiables.
- **[Écart possible entre données de prod et logique lue]** → Mitigation: cadrer la change comme “specification from code”, pas comme validation opérationnelle de données.
- **[Volume de specs élevé]** → Mitigation: structurer une capacité par domaine, avec scénarios concis et testables.

## Migration Plan

1. Ajouter `proposal.md`, `design.md`, `tasks.md` et les `specs/**/spec.md` de la change.
2. Valider la cohérence de la change OpenSpec (`openspec status`, puis `openspec validate`).
3. Utiliser ces specs comme base de futures changes d'amélioration/refonte.

Rollback: suppression de la change `document-existing-system-specs` sans impact runtime (artefacts documentaires uniquement).

## Open Questions

- Faut-il, dans une change suivante, distinguer explicitement les exigences “legacy connues à risque” (ex: secrets en configuration, options SSL permissives) des exigences métier stables ?
- Le périmètre des contexts secondaires non couverts (ex: variantes admin ponctuelles) doit-il être traité dans cette change ou dans des changes thématiques séparées ?
