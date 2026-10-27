## Context

Voir `proposal.md` pour la motivation et les specs pour les comportements attendus. L'écran d'activation existe déjà dans `maquettes/screens/entree/entree-activation.html`. Cette change produit des artefacts de spécification et une maquette statique; elle ne modifie pas le comportement de l'application.

## Goals / Non-Goals

**Goals:**
- Décrire le comportement attendu du renvoi et ses cas de succès et d'échec dans les specs.
- Représenter dans la maquette statique l'action ainsi que les retours de succès et d'échec.
- Relier UC-AUTH-7 aux artefacts documentaires concernés.

**Non-Goals:**
- Modifier le code applicatif, les services d'authentification ou l'envoi de courriels.
- Ajouter des tests d'exécution ou changer les comportements runtime de l'application.
- Modifier les parcours de création de compte, de connexion ou de récupération de mot de passe.
- Définir de nouveaux seuils de limitation ou une politique générale d'anti-abus.

## Decisions

- **Prolonger le parcours d'activation existant dans la maquette.** La demande de renvoi et ses retours seront représentés dans le même écran, sans ajouter un écran d'entrée distinct.
- **Documenter les règles de comportement sans les implémenter.** Les specs décrivent les résultats attendus, notamment la préservation du code précédent si l'envoi échoue; leur mise en œuvre applicative n'appartient pas à cette change.

## Risks / Trade-offs

- **Les comportements décrits devront être implémentés ultérieurement** → Garder la portée de cette change limitée aux specs, à la maquette et à la traçabilité; ne pas présenter le comportement documenté comme déjà disponible dans l'application.

## Migration Plan

Aucune migration, modification de code applicatif ou procédure de déploiement n'est prévue dans cette change. L'implémentation fonctionnelle pourra faire l'objet d'une change ultérieure.
