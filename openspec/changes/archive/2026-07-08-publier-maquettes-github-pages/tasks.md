## 1. Pipeline de publication GitHub Pages

- [x] 1.1 Ajouter le workflow `.github/workflows/static.yml` avec permissions Pages (`pages: write`, `id-token: write`) et concurrence dediee.
- [x] 1.2 Configurer le workflow pour publier directement le dossier `maquettes/` avec `actions/upload-pages-artifact` puis `actions/deploy-pages`.
- [x] 1.3 Ajouter les declencheurs `push` (branche `main` + chemins `maquettes/**`) et `workflow_dispatch`.

## 2. Mise en service et verification de l'acces

- [x] 2.1 Activer GitHub Pages en source `GitHub Actions` dans les parametres du depot.
- [x] 2.2 Executer un premier deploiement et verifier l'acces a l'entree `workbench/index.html` depuis l'URL Pages.
- [x] 2.3 Communiquer l'URL de consultation aux parties prenantes des ateliers maquettes.
