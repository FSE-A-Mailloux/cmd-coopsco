# PRD — Réécriture applicative (agnostique technologie)

## 1. Contexte et objectif

Ce document formalise les besoins produit à partir des spécifications OpenSpec existantes, dans une forme indépendante de toute technologie, afin de servir de base unique pour réécrire l’application.

Objectif principal: disposer d’un produit fonctionnellement équivalent, plus maintenable, testable et évolutif, sans dépendre des choix techniques historiques.

---

## 2. Vision produit

La solution cible est une plateforme de gestion de comptes utilisateurs, d’autorisations et de commandes scolaires, intégrant:
- authentification et gestion de session,
- navigation contextuelle par rôles,
- gestion de cycle de vie des comptes,
- gestion des périodes de campagne (en cours, ouverte/fermée, verrouillée),
- gestion du référentiel articles et des fournisseurs,
- gestion des prix articles par période,
- gestion du stock par article,
- consolidation des besoins pour préparation de commande fournisseur,
- gestion de la cotisation FSE dans le cycle de commande,
- gestion complète du cycle commande (création, suivi, règlement, justificatifs),
- envoi de mails transactionnels, relances et campagnes de mailing,
- communication documentaire (documents),
- observabilité et gestion explicite des erreurs.

La plateforme doit préserver les règles métier actuelles et permettre des évolutions rapides sur les parcours utilisateurs et administratifs.

---

## 3. Périmètre

### 3.1 In scope

1. Authentification, activation de compte, récupération/changement de mot de passe.
2. Session utilisateur avec expiration et gestion de déconnexion.
3. Contrôle d’accès par rôles/groupes et menus contextuels.
4. Gestion des comptes (création, modification, suppression, désinscription communication).
5. Exécution de requêtes métiers référencées (catalogue logique).
6. Gestion des périodes de commande (création, modification, suppression, activation, ouverture/fermeture, verrouillage logique).
7. Gestion du catalogue articles (création, modification, suppression, attributs métier et rattachement fournisseur).
8. Gestion des prix articles par période (prix famille, prix fournisseur, lot fournisseur, copie depuis période en cours).
9. Gestion des stocks article (consultation et ajustement de quantité).
10. Consolidation des besoins par fournisseur avant commande.
11. Workflow de commande: saisie, validation, règlement, annulation, relance.
12. Paiement en ligne via prestataire externe.
13. Génération de documents téléchargeables (dont factures).
14. Envoi de mails transactionnels, de relance et de campagne de mailing ciblée (utilisateur, groupe, liste libre).
15. Contrat d’échange normalisé et cohérent pour les erreurs.

### 3.2 Out of scope

1. Choix des frameworks, langages, bases de données, infrastructures.
2. Refonte des règles métier existantes (hors correction validée explicitement).
3. Redesign UX complet (possible phase ultérieure).

---

## 4. Parties prenantes et personas

1. **Parent / Responsable**: crée et suit ses commandes, règle en ligne ou hors ligne, récupère ses documents.
2. **Gestionnaire / Administration**: configure, pilote les commandes, gère les règlements, envoie relances/communications.
3. **Administrateur fonctionnel**: gère utilisateurs, groupes, autorisations, référentiels.
4. **Support**: traite incidents et anomalies via traces et historiques d’actions.
5. **Direction / Pilotage**: suit qualité de service, taux de validation, paiements, adoption.

---

## 5. Objectifs mesurables

1. 100% des parcours critiques couverts par des scénarios d’acceptation.
2. Taux d’échec fonctionnel sur parcours critiques < 1%.
3. Temps de réponse perçu acceptable sur interactions standard (< 2s cible fonctionnelle).
4. Génération documentaire et envoi transactionnel fiables (> 99% succès hors indisponibilité externe).
5. Traçabilité des actions sensibles (authentification, modifications comptes, paiements, annulations).
6. Support de la volumétrie cible: quelques centaines de commandes concentrées sur un mois de campagne, sans dégradation fonctionnelle notable.

---

## 6. Exigences fonctionnelles détaillées

## 6.1 Expérience applicative et navigation

**FR-001** Le système doit fournir un cadre applicatif stable: zone d’authentification, navigation, contenu principal, feedback d’erreur/chargement.  
**FR-002** Le système doit supporter une navigation contextuelle basée sur un historique de parcours (aller/retour/retour ciblé).  
**FR-003** Le système doit charger les écrans selon un composant métier actif.  
**FR-004** Le système doit afficher explicitement les erreurs de chargement de contenu.

## 6.2 Authentification et sessions

**FR-010** Le système doit authentifier un utilisateur par identifiants et ouvrir une session.  
**FR-011** Le système doit délivrer un jeton de session à authentification réussie.  
**FR-012** Le système doit supporter l’activation de compte via code/lien.  
**FR-013** Le système doit invalider les sessions expirées et forcer la déconnexion côté expérience utilisateur.  
**FR-014** Le système doit maintenir l’état de session actif tant que l’utilisateur reste dans la fenêtre de validité.

## 6.3 Autorisations et menu contextuel

**FR-020** Le système doit contrôler les droits par rôle/groupe pour chaque action sensible.  
**FR-021** Le système doit contrôler les droits d’accès aux composants métier.  
**FR-022** Le système doit construire un menu adapté aux permissions effectives de l’utilisateur.  
**FR-023** Le système doit gérer un mode non connecté avec droits limités.
**FR-024** Le système doit permettre à un utilisateur multi-rôles de basculer de rôle actif dans l’interface, avec recalcul immédiat des permissions et du menu.

## 6.4 Cycle de vie des comptes

**FR-030** Le système doit créer un compte en garantissant l’unicité des identifiants métier requis.  
**FR-031** Le système doit appliquer les prérequis de validation anti-abus pour l’inscription publique (ex. preuve humaine).  
**FR-032** Le système doit permettre la modification de profil avec contrôles de cohérence.  
**FR-033** Le système doit gérer la récupération de mot de passe via parcours sécurisé à code.  
**FR-034** Le système doit permettre le changement de mot de passe authentifié.  
**FR-035** Le système doit empêcher les suppressions de comptes non autorisées (ex. auto-suppression interdite si règle métier).  
**FR-036** Le système doit anonymiser les données historiques requises lors de suppression selon la politique définie.  
**FR-037** Le système doit permettre la désinscription à la communication de diffusion.

## 6.5 Requêtes métier référencées

**FR-040** Le système doit exécuter des requêtes métier identifiées par code fonctionnel.  
**FR-041** Le système doit ne considérer que les paramètres explicitement attendus par la requête.  
**FR-042** Le système doit fournir des résultats structurés, exploitables par les composants fonctionnels (listes, recherches, détails).

## 6.6 Périodes, catalogue articles et prix par période

**FR-043** Le système doit gérer des périodes de commande avec au minimum: libellé, préfixe de numérotation, date butoir, statut période en cours, statut ouverte/fermée et statut verrouillée.  
**FR-044** Le système doit garantir qu’une seule période est marquée "en cours" à un instant donné.  
**FR-045** Le système doit permettre l’ouverture/fermeture d’une période sans changer son identité métier.  
**FR-046** Le système doit permettre la gestion du référentiel articles (description, code, marque, ordre, type, fournisseur, usage en commande, usage en stock).  
**FR-047** Le système doit permettre la suppression d’un article avec suppression/neutralisation cohérente des données dépendantes prévues par la politique métier (stock, prix périodiques).  
**FR-048** Le système doit gérer les prix d’un article par période, avec au minimum un prix famille, un prix fournisseur et un lot fournisseur.  
**FR-049** Le système doit permettre la duplication complète des prix d’une période source vers une période cible, avec confirmation d’écrasement, blocage si la période cible est verrouillée, puis ajustement unitaire des prix dupliqués.

## 6.7 Workflow commande

**FR-050** Le système doit permettre la création d’une commande avec informations responsable, bénéficiaires et lignes d’articles.  
**FR-051** Le système doit attribuer un identifiant/numéro métier de commande.  
**FR-052** Le système doit permettre de modifier l’état de commande selon règles (validation, annulation, relance).  
**FR-053** Le système doit gérer les règlements partiels/multiples et la cohérence des montants.  
**FR-054** Le système doit rejeter un règlement invalide (montant, informations obligatoires manquantes).  
**FR-055** Le système doit rattacher une commande au bon compte selon règles de correspondance autorisées.

### 6.7.1 Règles spécifiques cotisation

**COTIS-01** La commande doit intégrer un article de cotisation identifié comme tel dans le référentiel produit.  
**COTIS-02** La quantité de cotisation doit être automatiquement alignée sur le nombre d’enfants rattachés à la commande.  
**COTIS-03** La quantité de cotisation ne doit pas être modifiable manuellement dans l’écran de saisie article.  
**COTIS-04** Le système doit permettre, sur action explicite d’un utilisateur autorisé, de retirer la ligne cotisation d’une commande existante uniquement tant que la commande n’est ni validée ni facturée.  
**COTIS-05** Le retrait de cotisation est une action irréversible au niveau fonctionnel courant, doit être confirmé avant exécution, et devient interdit dès qu’une commande est validée et facturée.

## 6.8 Stock et consolidation fournisseur

**FR-056** Le système doit maintenir une quantité de stock par article, modifiable par un utilisateur autorisé, dans un pilotage volontairement simple adapté à un cycle annuel (environ 3 inventaires par an).  
**FR-057** Le système doit garantir qu’une quantité de stock reste un entier positif ou nul (jamais négatif).  
**FR-058** Le système doit consolider les besoins d’achat par fournisseur pour la période ciblée en tenant compte des unités déjà commandées, des besoins complémentaires internes et du stock disponible; la consolidation doit rester visualisable à tout moment sans changement d’état ni workflow d’approbation.  
**FR-059** Le système doit calculer un nombre de lots fournisseur à commander par article, à partir des unités nettes à acheter et de la taille de lot, avec arrondi supérieur et plancher à zéro, pour préparer une commande fournisseur annuelle.

## 6.9 Paiement en ligne

**FR-060** Le système doit initier un paiement en ligne via un prestataire externe.  
**FR-061** Le système doit suivre l’état du paiement et éviter les incohérences de commande/paiement.  
**FR-062** Le système doit exposer une URL de redirection de paiement quand la commande est éligible.  
**FR-063** Le système doit retourner un état explicite si la commande n’est pas éligible (annulée, soldée, déjà validée, etc.).
**FR-064** Le système doit traiter de façon simple les cas limites de paiement (timeout, statuts intermédiaires) via un statut explicite "à vérifier", permettant une reprise manuelle (nouvelle tentative ou annulation) sans automatisation de litige.

## 6.10 Documents et communications

**FR-070** Le système doit générer des documents de commande/facturation téléchargeables.  
**FR-071** Le système doit produire des contenus de communication depuis des modèles avec fusion de données métier.  
**FR-072** Le système doit permettre les emails transactionnels nécessaires au passage de commande et les emails de diffusion générale.  
**FR-073** Le système doit prendre en charge les pièces jointes documentaires.  
**FR-074** Le système doit intégrer un mécanisme de recueil de consentement explicite et de désinscription pour les communications de diffusion, sans bloquer les emails transactionnels nécessaires au parcours de commande.

### 6.10.1 Règles spécifiques facturation

**FACT-01** Le système doit permettre l’édition d’une facture au format document téléchargeable pour une commande identifiée.  
**FACT-02** La facture doit être produite depuis un gabarit dédié de type facture, distinct du gabarit de confirmation de commande.  
**FACT-03** Le contenu de facture doit inclure au minimum: identité de commande, période, date, bénéficiaires, lignes d’articles, prix unitaires, quantités, total.  
**FACT-04** Le nom du document de facture doit suivre une convention métier traçable avec le numéro de commande.  
**FACT-05** Le système doit signaler explicitement l’échec de génération de facture (commande introuvable, gabarit absent, erreur de production document).
**FACT-06** Le processus de facturation doit respecter les obligations de la réglementation française applicable (mentions, émission, conservation et archivage).

## 6.11 Envoi de mail (relances et campagnes)

**FR-075** Le système doit permettre la composition d’un mail à partir d’un gabarit et d’un contenu éditable, avec prévisualisation avant envoi.  
**FR-076** Le système doit permettre trois modes de ciblage destinataire: adresse libre, utilisateur identifié, groupe d’utilisateurs.  
**FR-077** Le système doit permettre l’envoi en destinataire masqué pour les campagnes de diffusion afin de protéger la confidentialité des destinataires.  
**FR-078** Le système doit permettre l’envoi d’un mail de relance de règlement sur une commande non validée, et interdire la relance d’une commande déjà validée.  
**FR-079** Le système doit tracer les opérations d’envoi de campagne et de relance dans l’historique des actions, et contrôler une fréquence annuelle faible pour les campagnes générales (2 à 3 envois maximum par destinataire et par an).

## 6.12 Contrat de réponse et gestion d’erreurs

**FR-080** Le système doit exposer un contrat de réponse homogène pour succès et erreurs.  
**FR-081** Le système doit distinguer au minimum: erreur fonctionnelle, erreur de sécurité, session expirée, erreur technique.  
**FR-082** Le système doit journaliser les anomalies non fonctionnelles avec contexte suffisant pour diagnostic.
**FR-083** Le système doit assurer un audit horodaté des créations, modifications et suppressions sur l’ensemble des entités métier, avec identification de l’acteur.

---

## 7. Règles métier critiques

1. Une session expirée invalide toute action protégée.
2. Les actions protégées nécessitent un droit explicite.
3. Un compte ne peut pas être créé avec des identifiants déjà utilisés.
4. La suppression de compte doit respecter les règles de privilèges.
5. Les opérations de commande doivent conserver l’intégrité financière.
6. Les relances et notifications doivent utiliser des données de commande cohérentes.
7. Les documents générés doivent être traçables à une commande précise.
8. Une et une seule période doit être active "en cours" à un instant donné.
9. Les prix d’une période verrouillée ne sont pas modifiables.
10. Le stock article ne peut jamais devenir négatif.
11. La consolidation fournisseur applique la formule d’unités nettes et un arrondi par lot à l’entier supérieur.
12. Une consolidation est consultable à tout moment et ne déclenche aucun changement d’état ni approbation dédiée.
13. Une relance de règlement ne peut être envoyée que pour une commande non validée.
14. Une campagne de mailing doit respecter le mode de confidentialité destinataire choisi.
15. La quantité de cotisation suit automatiquement le nombre d’enfants de la commande.
16. Le retrait de la cotisation sur une commande requiert une double confirmation et reste interdit dès que la commande est validée et facturée.
17. Une facture doit être générée à partir d’un gabarit dédié, distinct du document de commande.
18. Les données historisées doivent être anonymisées conformément aux délais de conservation applicables à la réglementation européenne en vigueur.
19. Les communications générales nécessitent un consentement explicite préalable et respectent une fréquence annuelle faible (2 à 3 envois maximum par destinataire).
20. Les cas limites de paiement (timeout, statuts intermédiaires) sont gérés par un statut explicite "à vérifier" et une reprise manuelle.
21. L’évolution annuelle des prix s’appuie sur une duplication complète inter-périodes suivie d’ajustements unitaires.
22. Toute création, modification ou suppression d’une donnée métier doit être auditée avec acteur et horodatage.
23. Le cycle commande (dont cotisation) et le cycle de facturation doivent respecter la réglementation française applicable.

---

## 8. Modèle conceptuel (agnostique)

Entités métier minimales:

1. **Utilisateur**: identité, contact, état d’activation, préférences de communication.
2. **Rôle/Groupe**: périmètre de droits.
3. **Permission**: action autorisée sur composant/fonction.
4. **Session**: jeton, utilisateur, dates de validité.
5. **Composant métier**: écran/fonctionnalité navigable.
6. **Menu**: options visibles selon contexte et permissions.
7. **Période de commande**: libellé, date butoir, préfixe de numérotation, statut actif, statut ouvert, statut verrouillé.
8. **Article**: code, description, marque, type, ordre, options d’usage.
9. **Tarification article par période**: prix famille, prix fournisseur, lot fournisseur.
10. **Fournisseur**: identité commerciale de rattachement article.
11. **Stock article**: quantité disponible interne par article.
12. **Consolidation fournisseur**: regroupement des besoins d’achat par période et fournisseur.
13. **Ligne de consolidation**: article, unités commandées, besoin interne complémentaire, stock, unités nettes, taille de lot, lots à commander.
14. **Commande**: en-tête, statut, totaux, rattachement utilisateur, rattachement période.
15. **Ligne de commande**: article, quantité, prix, réduction éventuelle.
16. **Ligne de cotisation**: ligne de commande dédiée, calculée automatiquement selon le nombre d’enfants.
17. **Règlement**: type, montant, statut, référence prestataire éventuelle.
18. **Facture**: document financier lié à une commande, produit via gabarit dédié.
19. **Document**: type, contenu généré, nom logique.
20. **Message**: modèle, destinataire(s), statut d’envoi.
21. **Anomalie / Historique d’action**: audit opérationnel.

---

## 9. Exigences non fonctionnelles

## 9.1 Sécurité

1. Contrôle d’accès systématique sur actions protégées.
2. Mécanismes robustes de gestion de session et d’expiration.
3. Protection des données personnelles (minimisation, anonymisation ciblée, traçabilité).
4. Prévention des abus sur inscription/récupération de compte.

## 9.2 Fiabilité et cohérence

1. Opérations critiques atomiques (commande, règlement, activation).
2. Aucune mise à jour partielle silencieuse en cas d’échec.
3. Gestion explicite des erreurs et remontée exploitable.

## 9.3 Performance et expérience

1. Feedback utilisateur sur chargement et erreurs.
2. Navigation fluide entre parcours standards.
3. Génération documentaire compatible avec un usage en masse raisonnable.

## 9.4 Exploitabilité

1. Journalisation des anomalies et des actions sensibles.
2. Possibilité de diagnostiquer un incident de bout en bout.
3. Audit transversal des entités métier (création, modification, suppression) avec horodatage et acteur.

## 9.5 Conformité et conservation des données

1. Les données historisées doivent être anonymisées selon une politique alignée sur la réglementation européenne en vigueur.
2. Les délais d’anonymisation doivent être paramétrables et traçables (preuve d’exécution).
3. Le cycle de commande, de cotisation et de facturation doit être conforme à la réglementation française applicable.

---

## 10. Parcours utilisateurs (résumé)

1. **Inscription / activation**: création compte → validation anti-abus → email d’activation → activation → première connexion.
2. **Connexion standard**: authentification → session active → menu contextualisé.
3. **Récupération accès**: demande récupération → émission lien/code → redéfinition mot de passe.
4. **Passage commande**: saisie commande → génération numéro → génération justificatif → notifications.
4.bis **Cotisation**: calcul automatique de la quantité de cotisation selon le nombre d’enfants, avec possibilité de suppression explicite par rôle habilité uniquement avant validation et facturation.
5. **Règlement / suivi**: ajout règlement(s) → recalcul état commande → validation/annulation selon règles.
5.bis **Facturation**: génération d’une facture à partir de la commande et téléchargement du document.
6. **Paiement en ligne**: initiation → redirection prestataire → retour statut → mise à jour état.
7. **Relance impayés**: déclenchement d’un mail de relance depuis la gestion des commandes en cours, selon éligibilité métier.
8. **Campagne de mailing**: sélection d’un gabarit, choix du ciblage (groupe/utilisateur/libre), prévisualisation puis envoi, sous consentement explicite pour la diffusion générale et fréquence annuelle limitée (2 à 3 campagnes maximum).
9. **Administration stock**: ajustement des stocks par article et pilotage simple avec environ 3 inventaires annuels.
10. **Consolidation fournisseur**: regroupement des besoins par fournisseur, simulation des lots à commander, génération d’un document de consolidation, consultation à tout moment sans approbation.
11. **Administration référentiel**: gestion périodes (en cours, ouverture, verrouillage), gestion articles, gestion prix par période et duplication complète d’un barème inter-périodes avec ajustements unitaires.
12. **Administration**: gestion comptes, permissions, paramètres, relances.

---

## 11. Critères d’acceptation (niveau produit)

1. Tous les parcours critiques (auth, commande, paiement, document, communication) sont exécutables sans régression métier.
2. Toute action non autorisée est refusée avec message de catégorie correcte.
3. Toute session expirée est détectée et traitée de façon homogène.
4. Toute commande conserve une cohérence montant-lignes-règlements.
5. Les documents et emails sont générés et transmis avec le bon contexte métier.
6. Les erreurs techniques sont journalisées et distinguées des erreurs fonctionnelles.
7. L’administration peut gérer les périodes et garantir une période active unique.
8. Les prix article/période respectent les règles de verrouillage, de duplication complète inter-périodes et de modification unitaire.
9. Les niveaux de stock article sont modifiables sans créer de valeurs négatives, dans un pilotage simple compatible avec un cycle annuel et environ 3 inventaires par an.
10. La consolidation produit des quantités et lots fournisseur cohérents et vérifiables, reste consultable à tout moment et ne change pas d’état.
11. Les campagnes de mailing sont envoyables via gabarit, prévisualisation et ciblage multi-mode, dans la limite d’une fréquence annuelle faible et sous consentement explicite pour la diffusion générale.
12. Les relances sont autorisées uniquement pour les commandes éligibles.
13. La cotisation est automatiquement calculée sur la commande en fonction du nombre d’enfants.
14. La suppression de cotisation nécessite confirmation, est correctement tracée, et n’est plus autorisée après validation et facturation de la commande.
15. La facture est générée avec un gabarit dédié, contient les éléments de détail de la commande et respecte la réglementation française applicable.
16. Un utilisateur multi-rôles peut changer de rôle actif et voit immédiatement les permissions correspondantes.
17. Les mécanismes d’anonymisation respectent les délais réglementaires définis.
18. Les cas limites de paiement basculent en statut "à vérifier" et sont traitables manuellement.
19. Toute donnée métier est couverte par une piste d’audit de ses créations, modifications et suppressions.

---

## 12. Plan de réécriture recommandé (phases)

1. **Phase 1 — Fondations**: modèle métier, identité/session, autorisations, contrat d’erreur.
2. **Phase 2 — Parcours utilisateurs**: navigation, comptes, récupération accès.
3. **Phase 3 — Référentiels métier**: périodes, catalogue articles, tarification par période.
4. **Phase 4 — Stock & consolidation**: gestion des stocks, calcul des besoins et préparation commande fournisseur.
5. **Phase 5 — Cœur métier commande**: création/suivi/validation/règlement.
6. **Phase 6 — Paiement, documents & mailing**: paiement en ligne, génération documentaire, relances et campagnes mailing.
7. **Phase 7 — Administration & observabilité**: gestion avancée, audit, robustesse opérationnelle.

Chaque phase doit livrer des scénarios d’acceptation traçables vers les exigences FR-*.

---

## 13. Traçabilité OpenSpec → PRD

1. `frontend-shell-and-dynamic-components` → FR-001 à FR-004  
2. `authentication-and-session-token-lifecycle` → FR-010 à FR-014  
3. `authorization-and-menu-resolution` → FR-020 à FR-023  
4. `user-account-lifecycle-management` → FR-030 à FR-037  
5. `request-catalog-execution` → FR-040 à FR-042  
6. `fse-order-and-payment-workflow` → FR-050 à FR-064  
7. `templated-mail-and-document-generation` → FR-070 à FR-079  
8. `webservice-dispatch-and-response-contract` → FR-080 à FR-083  
9. Compléments référentiels (périodes, articles, prix par période) extraits des comportements métier existants → FR-043 à FR-049
10. Compléments stock et consolidation fournisseur extraits des comportements métier existants → FR-056 à FR-059
11. Compléments cotisation extraits des comportements métier existants → COTIS-01 à COTIS-05
12. Compléments facturation extraits des comportements métier existants → FACT-01 à FACT-06

---

## 14. Arbitrages (mise à jour)

### 14.1 Décisions validées

1. **Niveau de service et volumétrie**: niveau de service attendu faible; volumétrie cible de quelques centaines de commandes concentrées sur un mois.
2. **Anonymisation historique**: les données historisées doivent être anonymisées selon les délais de conservation imposés par la réglementation européenne en vigueur.
3. **Multi-rôles**: l’interface doit permettre à un utilisateur disposant de plusieurs rôles de basculer de rôle actif; les permissions et le menu doivent s’adapter immédiatement au rôle choisi.
4. **Politique de communication**: communications générales très légères (2 à 3 emails par an pour ouverture/fermeture de campagne), avec consentement explicite requis; les emails nécessaires au parcours de commande restent autorisés.
5. **Cas limites paiement**: traitement volontairement simple des timeouts/statuts intermédiaires, via statut explicite "à vérifier" et résolution manuelle.
6. **Évolution annuelle des prix et audit**: duplication complète des prix d’une période à l’autre, ajustements unitaires ensuite, et audit attendu sur l’ensemble des éléments du modèle métier.
7. **Pilotage stock**: gestion volontairement simple, adaptée à un usage annuel, avec une commande fournisseur unique par an et environ trois inventaires annuels.
8. **Consolidation fournisseur**: la consolidation est consultable à tout moment, sans changement d’état ni étape d’approbation.
9. **Cotisation**: la cotisation fait partie de la commande; sa suppression est interdite dès qu’une commande est validée et facturée.
10. **Conformité réglementaire française**: la gestion des commandes et la facturation doivent respecter la réglementation française applicable.

### 14.2 Questions restant à arbitrer

1. Politique de délivrabilité email (gestion rebonds, taux d’échec, anti-spam, fréquence maximale par cible) — non arbitrée à ce stade.
