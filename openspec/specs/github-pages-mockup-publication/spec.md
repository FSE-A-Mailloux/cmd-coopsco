## Purpose

Définir les exigences de publication des maquettes statiques sur GitHub Pages pour une consultation distante fiable du workbench.

## Requirements

### Requirement: Le référentiel maquettes MUST être publié automatiquement sur GitHub Pages
Le dépôt MUST fournir un workflow de publication qui déploie le contenu statique des maquettes vers GitHub Pages afin de produire une URL de consultation partageable.

#### Scenario: Publication automatique lors d'une mise à jour des maquettes
- **WHEN** une modification est poussée sur la branche principale dans le dossier `maquettes/`
- **THEN** le workflow GitHub Actions de publication se déclenche et déploie la version mise à jour sur GitHub Pages

#### Scenario: Publication manuelle à la demande
- **WHEN** un mainteneur déclenche manuellement le workflow de publication
- **THEN** le contenu courant du dossier `maquettes/` est redéployé sur GitHub Pages

### Requirement: Le déploiement GitHub Pages MUST publier le contenu brut du dossier maquettes
Le workflow de publication MUST charger `maquettes/` comme artefact Pages sans build applicatif supplémentaire afin de conserver une prévisualisation strictement statique.

#### Scenario: Point d'entrée workbench disponible après déploiement
- **WHEN** la publication GitHub Pages est terminée
- **THEN** la page `workbench/index.html` est accessible depuis l'URL GitHub Pages du dépôt

#### Scenario: Aucune dépendance backend lors de la publication
- **WHEN** le workflow exécute le déploiement Pages
- **THEN** aucune étape de compilation backend ou de connexion à des services métier n'est requise
