## 1. Maquettes du parcours administration utilisateurs

- [x] 1.1 Mettre a jour `maquettes/screens/administration/admin-utilisateurs.html` pour ajouter une zone de recherche et verifier visuellement que la recherche est presente au-dessus du tableau utilisateurs.
- [x] 1.2 Integrer dans le listing utilisateurs une action de changement de role reservee au contexte admin et verifier que les roles proposes sont uniquement `Famille`, `Gestionnaire` et `Admin`.
- [x] 1.3 Ajouter dans le parcours maquette un bloc de consultation d'historique des changements de role (ancien role, nouveau role, auteur admin, horodatage) et verifier que ces informations sont lisibles sur l'ecran.

## 2. Contrat metier et API de changement de role

- [x] 2.1 Implementer le contrat serveur de changement de role avec controle d'autorisation `Admin` et verifier qu'une requete non-admin retourne une erreur d'autorisation explicite.
- [x] 2.2 Implementer la validation stricte du role cible (`Famille`, `Gestionnaire`, `Admin`) et verifier qu'une valeur hors referentiel est rejetee avec une erreur de validation explicite.
- [x] 2.3 Implementer la mutation de role et la creation de trace d'audit dans une meme transaction et verifier qu'un echec de journalisation annule aussi la mutation du role.

## 3. Consultation de l'historique d'audit des roles

- [x] 3.1 Exposer un endpoint de consultation de l'historique des changements de role filtre par utilisateur cible et verifier que les entrees sont triees du plus recent au plus ancien.
- [x] 3.2 Proteger l'endpoint d'historique pour les seuls admins et verifier qu'un non-admin obtient un refus d'acces explicite.
- [x] 3.3 Brancher l'interface admin sur l'endpoint d'historique et verifier que l'utilisateur voit les traces avec auteur, ancien role, nouveau role et horodatage.

## 4. Verification transverse du changement

- [x] 4.1 Ajouter/mettre a jour les tests couvrant autorisation, validation des roles et atomicite role+audit, puis verifier que la suite ciblee de tests passe.
- [x] 4.2 Valider le changement OpenSpec en executant `openspec validate cadrer-changement-role-utilisateur --strict` et verifier l'absence d'erreurs.
