## Purpose

Définir les exigences de publication des maquettes statiques sur GitHub Pages pour une consultation distante fiable du workbench.

## Requirements

### Requirement: Le referentiel maquettes MUST etre publie automatiquement sur GitHub Pages
Le depot MUST fournir un workflow de publication qui deploie le contenu statique des maquettes vers GitHub Pages afin de produire une URL de consultation partageable.

#### Scenario: Publication automatique lors d'une mise a jour des maquettes
- **WHEN** une modification est poussee sur la branche principale dans le dossier `maquettes/`
- **THEN** le workflow GitHub Actions de publication se declenche et deploie la version mise a jour sur GitHub Pages

#### Scenario: Publication manuelle a la demande
- **WHEN** un mainteneur declenche manuellement le workflow de publication
- **THEN** le contenu courant du dossier `maquettes/` est redeploye sur GitHub Pages

### Requirement: Le deploiement GitHub Pages MUST publier le contenu brut du dossier maquettes
Le workflow de publication MUST charger `maquettes/` comme artefact Pages sans build applicatif supplementaire afin de conserver une previsualisation strictement statique.

#### Scenario: Point d'entree workbench disponible apres deploiement
- **WHEN** la publication GitHub Pages est terminee
- **THEN** la page `workbench/index.html` est accessible depuis l'URL GitHub Pages du depot

#### Scenario: Aucune dependance backend lors de la publication
- **WHEN** le workflow execute le deploiement Pages
- **THEN** aucune etape de compilation backend ou de connexion a des services metier n'est requise
