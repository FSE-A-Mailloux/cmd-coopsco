## Context

Voir `proposal.md` pour la motivation. Le dépôt est actuellement à l'horizon H1 : il contient les spécifications et les maquettes statiques, mais aucun code de l'application cible. Le backlog `docs/legacy-migration-backlog.md` distingue explicitement H1 (spécifications et maquettes) de H2 (construction de l'application). Le dossier `legacy/` reste une référence en lecture seule.

## Goals / Non-Goals

**Goals:**
- Livrer le contrat métier et le contrat d'écran de référence de UC-CON-3.
- Illustrer le lancement gestionnaire, le périmètre éligible, les lots ordonnés, la progression, le résultat sans commande et l'échec par une maquette statique mobile-first.
- Raccorder les livrables au manifeste des maquettes et à la matrice de couverture H1.

**Non-Goals:**
- Implémenter l'application cible, un service, un endpoint, un contrôle d'accès exécutable ou une génération/téléchargement réel de PDF.
- Créer un moteur configurable de rapports ou d'éditions, un fichier ZIP ou un historique durable de lots.
- Modifier le workflow de paiement ou le document unitaire d'une commande.
- Modifier les fichiers de `legacy/`.

## Decisions

### Conserver les règles métier comme contrat cible

Le traitement sélectionne les commandes de la période en cours qui ont un règlement par carte non annulé, puis les trie globalement par nom et prénom du premier enfant lié à chaque commande. Le numéro de commande ne sert pas de critère de tri. La liste ainsi ordonnée est ensuite découpée en groupes consécutifs de 30 commandes au maximum; une limite peut donc séparer des commandes ayant le même enfant. Les lots sont définis à partir des commandes éligibles elles-mêmes, et non par de simples intervalles d'identifiants : cela évite qu'une commande non éligible comprise entre deux identifiants soit ajoutée par erreur. Le seuil de 30 est fixe et n'est pas un paramètre utilisateur.

Alternative considérée : exposer une plage d'identifiants ou un sélecteur de commandes. Elle est écartée car le traitement cible porte sur l'ensemble des commandes éligibles de la période en cours, sans arbitrage manuel du périmètre. Ces règles sont documentées pour une implémentation ultérieure; la maquette ne prétend pas les exécuter.

### Représenter les états sans simuler un traitement réel

La maquette montre un exemple statique de plusieurs lots, avec l'avancement séquentiel et les noms logiques des fichiers attendus. Des états statiques distincts exposent l'absence de commandes et un échec rattaché au lot concerné. Les contrôles visuels ne sont pas branchés à un backend et les boutons ne déclenchent aucune opération.

Alternative considérée : réaliser la génération et le téléchargement dans la maquette. Elle est écartée car le module `maquettes/` est un référentiel statique indépendant du backend et la construction de l'application relève de H2.

### Aligner l'exemple de bon sur les données du traitement par lot

Le traitement legacy charge le modèle `PDF_FSE_CMDS` depuis la table `modelehtml`, puis lui transmet les champs de commande et de période, les coordonnées du commanditaire, les enfants avec leur classe, les articles avec leur ordre, référence, marque, quantité et prix, ainsi que les totaux. L'exemple statique reprend ces familles de données. Le contenu HTML de `PDF_FSE_CMDS` n'est pas présent dans le dépôt; la maquette ne peut donc pas certifier la mise en page ni les libellés exacts du document rendu. Le modèle individuel `PDF_CMD_FSE` est distinct et n'est pas utilisé comme source de la maquette du traitement par lot.

## Risks / Trade-offs

- [La maquette statique peut être prise pour une fonctionnalité disponible] → Afficher le badge de prévisualisation statique déjà utilisé dans le référentiel et ne brancher aucune action.
- [La génération réelle et les contrôles d'habilitation restent à implémenter en H2] → Conserver leurs exigences dans le contrat métier et signaler explicitement cette limite dans les artefacts H1.
- [Les règles de règlement peuvent évoluer] → Conserver les critères précis dans la spécification métier, distinctement de leur illustration visuelle.
- [Le modèle HTML PDF_FSE_CMDS est conservé uniquement en base de données et absent du dépôt] → Aligner l'exemple sur les données préparées par le code du traitement par lot et ne pas présenter son rendu comme une reproduction certifiée du template.

## Migration Plan

Aucune migration de données ni aucun déploiement applicatif n'est requis. Les livrables H1 deviennent une source de vérité pour une implémentation ultérieure; ils n'altèrent ni les commandes ni les règlements.
