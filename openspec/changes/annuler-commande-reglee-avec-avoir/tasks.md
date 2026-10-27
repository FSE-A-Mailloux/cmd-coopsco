## 1. Maquettes du parcours d'annulation

- [ ] 1.1 Mettre à jour `maquettes/screens/commandes/commandes-liste.html` pour rendre l'action d'annulation disponible sur les commandes en paiement partiel et confirmées, en annonçant l'émission d'un avoir ; vérifier que les commandes déjà annulées ne proposent pas l'action.
- [ ] 1.2 Mettre à jour `maquettes/screens/commandes/commandes-detail.html` pour présenter la confirmation d'annulation, la production éventuelle de la facture et le téléchargement de l'avoir ; vérifier que le parcours est compréhensible sans contexte backend.

## 2. Cohérence des artefacts

- [ ] 2.1 Mettre à jour `docs/legacy-coverage-matrix.md` si les rattachements de UC-CMD-5 et UC-PAY-2 évoluent avec les maquettes ; vérifier que `scripts/check-legacy-coverage.ps1` réussit.
- [ ] 2.2 Valider les spécifications OpenSpec et les maquettes modifiées ; vérifier que `openspec validate annuler-commande-reglee-avec-avoir --strict` et les contrôles existants de maquettes réussissent.
