## 1. Pipeline de publication GitHub Pages

- [ ] 1.1 Ajouter le workflow `.github/workflows/pages-maquettes.yml` avec permissions Pages (`pages: write`, `id-token: write`) et concurrence dediee.
- [ ] 1.2 Configurer le workflow pour publier directement le dossier `maquettes/` avec `actions/upload-pages-artifact` puis `actions/deploy-pages`.
- [ ] 1.3 Ajouter les declencheurs `push` (branche `main` + chemins `maquettes/**`) et `workflow_dispatch`.

## 2. Mise en service et verification de l'acces

- [ ] 2.1 Activer GitHub Pages en source `GitHub Actions` dans les parametres du depot.
- [ ] 2.2 Executer un premier deploiement et verifier l'acces a l'entree `workbench/index.html` depuis l'URL Pages.
- [ ] 2.3 Communiquer l'URL de consultation aux parties prenantes des ateliers maquettes.
