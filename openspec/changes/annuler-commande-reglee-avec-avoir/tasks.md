## 1. Maquettes du parcours d'annulation

- [ ] 1.1 Mettre a jour `maquettes/screens/commandes/commandes-liste.html` pour rendre l'action d'annulation disponible sur les commandes en paiement partiel et confirmees, en annoncant l'emission d'un avoir ; verifier que les commandes deja annulees ne proposent pas l'action.
- [ ] 1.2 Mettre a jour `maquettes/screens/commandes/commandes-detail.html` pour presenter la confirmation d'annulation, la production eventuelle de la facture et le telechargement de l'avoir ; verifier que le parcours est comprehensible sans contexte backend.

## 2. Coherence des artefacts

- [ ] 2.1 Mettre a jour `docs/legacy-coverage-matrix.md` si les rattachements de UC-CMD-5 et UC-PAY-2 evoluent avec les maquettes ; verifier que `scripts/check-legacy-coverage.ps1` reussit.
- [ ] 2.2 Valider les specifications OpenSpec et les maquettes modifiees ; verifier que `openspec validate annuler-commande-reglee-avec-avoir --strict` et les controles existants de maquettes reussissent.
