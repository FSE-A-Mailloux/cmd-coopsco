## Why

Le use-case UC-CON-3 de génération en lot des PDF de commandes réglées par carte est encore « à cadrer » : la génération documentaire existante couvre une commande à la fois et ne décrit ni le traitement par lots ni sa restitution. Formaliser le parcours gestionnaire et le représenter par une maquette statique permet de couvrir le besoin H1 sans réintroduire un moteur générique d'éditions ni anticiper l'implémentation de la future application.

## What Changes

- Spécifier le contrat cible UC-CON-3 : commandes de la période en cours avec règlement par carte non annulé, tri alphabétique selon le nom et le prénom du premier enfant lié, lots ordonnés de 30 au maximum et restitution d'un PDF par lot.
- Décrire dans la spécification des parcours frontend l'écran gestionnaire et ses états de progression, d'absence de commandes et d'échec.
- Ajouter et cataloguer une maquette statique mobile-first qui illustre le parcours sans appeler de service ni générer de document réel.
- Mettre à jour la traçabilité H1 du use-case dans la matrice de couverture.

## Capabilities

### New Capabilities
- `batch-card-paid-order-documents`: cadrer la sélection des commandes réglées par carte, leur regroupement en lots de 30 et la restitution d'un PDF téléchargeable par lot.

### Modified Capabilities
- `frontend-reference-screen-flows`: spécifier l'écran de référence gestionnaire et ses états visuels pour UC-CON-3.

## Impact

- Artefacts OpenSpec pour le contrat métier UC-CON-3 et son écran de référence, ainsi qu'une maquette et ses entrées dans `maquettes/manifest/`.
- Mise à jour de `docs/legacy-coverage-matrix.md` pour rattacher le use-case à ses livrables H1.
- Aucun endpoint, traitement serveur, génération PDF réelle ou interface dynamique n'est implémenté dans ce changement. Aucun fichier de `legacy/` n'est modifié.
