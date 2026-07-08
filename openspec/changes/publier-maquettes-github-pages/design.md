## Context

Le dossier `maquettes/` contient deja un workbench statique et ses ecrans de reference.
L'usage actuel repose sur une ouverture locale des fichiers HTML, ce qui complique le partage avec des acteurs metier hors environnement de developpement.
Le depot n'expose pas encore de workflow GitHub Actions dedie a la publication Pages.

## Goals / Non-Goals

**Goals:**
- Publier automatiquement `maquettes/` via GitHub Pages avec une URL stable.
- Permettre un redeploiement manuel pour les ateliers ou demonstrations.
- Conserver un flux simple et statique, sans chaine de build frontend additionnelle.

**Non-Goals:**
- Introduire un hebergement alternatif a GitHub Pages.
- Transformer les maquettes en application dynamique.
- Ajouter une authentification applicative specifique au workbench.

## Decisions

1. Utiliser la source GitHub Pages basee sur GitHub Actions
   - Decision: configurer le depot pour deployer via `actions/configure-pages`, `actions/upload-pages-artifact` et `actions/deploy-pages`.
   - Alternatives rejetees: publication depuis `gh-pages` (branche dediee) ou depuis `/docs`; rejetees pour eviter la gestion manuelle des artefacts et conserver le dossier `maquettes/` comme source unique.

2. Publier directement le dossier `maquettes/` comme artefact
   - Decision: uploader `maquettes/` sans etape de build.
   - Alternatives rejetees: pipeline de build statique; rejetee car inutile pour des ressources HTML/CSS/JS deja statiques.

3. Limiter le declenchement automatique aux changements utiles
   - Decision: declencher sur `push` de `main` avec filtre de chemins sur `maquettes/**` et le workflow lui-meme, plus `workflow_dispatch`.
   - Alternatives rejetees: declenchement a chaque push; rejetee pour limiter les deploiements non pertinents.

## Risks / Trade-offs

- [Risque de non-publication si la source Pages n'est pas reglee sur GitHub Actions] -> Mitigation: documenter explicitement ce prerequis dans la mise en service.
- [Risque d'exposition involontaire de maquettes sur depot public] -> Mitigation: rappeler que la visibilite suit celle du depot et valider ce choix avec les parties prenantes.
- [Risque de confusion entre URL racine Pages et entree workbench] -> Mitigation: communiquer l'URL cible `.../workbench/` dans la documentation d'usage.

## Migration Plan

1. Ajouter le workflow `.github/workflows/pages-maquettes.yml`.
2. Activer GitHub Pages avec la source `GitHub Actions` dans les parametres du depot.
3. Pousser sur `main` pour produire un premier deploiement.
4. Communiquer l'URL de consultation du workbench aux equipes metier.

## Open Questions

- Souhaite-t-on ajouter une URL de redirection vers `workbench/` depuis la racine publiee?
- Faut-il documenter une procedure de validation visuelle post-deploiement dans le processus d'atelier?
