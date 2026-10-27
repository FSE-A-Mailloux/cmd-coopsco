## 1. Spécifications fonctionnelles

- [x] 1.1 Décrire le renvoi du code pour un compte en attente d'activation, ses retours en cas de succès ou d'échec et le cas d'un compte déjà activé; vérifier la couverture des scénarios dans la spec d'authentification.
- [x] 1.2 Décrire l'action de renvoi et les retours visibles dans le parcours d'activation; vérifier la cohérence de la spec d'interface avec les scénarios d'authentification.

## 2. Maquette d'activation

- [x] 2.1 Mettre à jour `maquettes/screens/entree/entree-activation.html` pour représenter l'action de renvoi et les états de confirmation et d'échec; vérifier la maquette en prévisualisation statique.

## 3. Traçabilité et validation

- [x] 3.1 Mettre à jour `docs/legacy-coverage-matrix.md` pour relier UC-AUTH-7 aux deux specs et à la maquette d'activation; vérifier que les livrables manquants sont corrigés.
- [x] 3.2 Exécuter `openspec validate renvoyer-code-de-validation --strict` et vérifier que les artefacts documentaires restent cohérents entre eux.
