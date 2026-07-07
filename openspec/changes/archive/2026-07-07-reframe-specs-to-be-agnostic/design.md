## Context

Les specs `openspec/specs/*` ont été initialement construites en mode documentation de l’existant legacy.  
Elles contiennent des détails d’implémentation historiques (AngularJS, `wsApp.php`, contextes d’actions, conventions de tables/champs) qui ne doivent pas contraindre la réécriture.

Le PRD agnostique (`openspec/prd-rewrite-agnostique.md`) est désormais la référence produit cible.  
La présente change convertit les specs en référentiel **to-be**: comportement métier testable, conformité, et contraintes fonctionnelles, tout en conservant une trace explicite de ce qui est **legacy-only**.

## Goals / Non-Goals

**Goals:**
- Marquer les mécanismes techniques historiques comme legacy-only dans les capacités techniques.
- Reformuler les exigences existantes en exigences to-be orientées résultats métier observables.
- Aligner les capacités et scénarios avec le PRD agnostique (commande, paiement, communications, audit, conformité).
- Ajouter les capacités manquantes du PRD (périodes/prix, stock/consolidation).

**Non-Goals:**
- Modifier le code applicatif legacy.
- Imposer un choix d’architecture, de framework ou de protocole technique pour la future implémentation.
- Définir le design détaillé des API, du schéma de données physique, ou du déploiement.

## Decisions

1. **Conserver les noms de capacités existantes**  
   Les dossiers de capacités actuels sont conservés pour préserver l’historique OpenSpec, avec mise à jour du contenu via deltas `MODIFIED`.

2. **Introduire une marque explicite legacy-only dans les capacités techniques**  
   Les capacités techniques historiques reçoivent une exigence ajoutée indiquant que le mécanisme legacy est informatif et non prescriptif pour la cible.

3. **Remplacer le “comment historique” par le “quoi attendu”**  
   Les exigences modifiées retirent les références directes aux fichiers/actions legacy et décrivent uniquement des contrats et comportements attendus.

4. **Compléter la couverture métier du PRD par de nouvelles capacités**  
   Création de capacités dédiées pour les domaines insuffisamment couverts: périodes/tarification et stock/consolidation.

5. **Conserver des scénarios vérifiables au niveau produit**  
   Chaque exigence est exprimée avec scénarios WHEN/THEN testables, utilisables comme base d’acceptation pour la réécriture.

## Risks / Trade-offs

- **[Risque] Ambiguïté entre “legacy-only” et “à implémenter”**  
  → **Mitigation**: ajouter une exigence explicite legacy-only dans les specs techniques et reformuler les exigences to-be sans dépendances legacy.

- **[Risque] Régression métier lors de la réécriture des specs**  
  → **Mitigation**: alignement systématique des exigences/scénarios sur le PRD agnostique et conservation des invariants métiers critiques.

- **[Trade-off] Réutilisation des mêmes noms de capacités**  
  → **Mitigation**: favorise la continuité de traçabilité, au prix d’une granularité parfois héritée du legacy.

- **[Risque] Couverture incomplète des zones nouvellement ajoutées**  
  → **Mitigation**: création de deux nouvelles capacités to-be et explicitation des points encore non arbitrés (délivrabilité email).
