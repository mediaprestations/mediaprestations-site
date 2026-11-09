# Site Média Prestations — guide de mise en ligne

Ce dossier contient le code complet du nouveau site. Le principe :

```
Espace admin (/admin)  →  GitHub (dépôt "mediaprestations-site", branche main)
        →  construction automatique (GitHub Actions)  →  branche "deploy"
        →  OVH récupère la branche "deploy" et met le site à jour
```

---

## 1. Mettre le code sur GitHub (une seule fois)

1. Créez un compte gratuit sur github.com si vous n'en avez pas.
2. Installez **GitHub Desktop** (desktop.github.com) et connectez-vous.
3. *File › Add local repository…* → choisissez le dossier `site`
   → GitHub Desktop propose « create a repository » : acceptez.
4. Faites un premier *Commit to main*, puis **Publish repository**
   - Nom : `mediaprestations-site`
   - Cochez **Keep this code private**.
5. **Activer la construction automatique** : sur github.com, dans le dépôt, *Add file › Create new file*,
   nommez-le exactement `.github/workflows/deploy.yml`, collez le contenu du fichier
   `workflow-deploy.yml` (présent dans ce dossier), puis *Commit changes*.
   (Ce fichier ne peut pas être déposé directement sur votre Mac pour des raisons de sécurité.)

Au premier envoi, l'onglet **Actions** du dépôt construit le site et crée la branche `deploy`
(environ 1 minute).

## 2. Remplacer le WordPress par le nouveau site (une seule fois)

Prérequis : l'étape 1 est faite et l'onglet **Actions** de GitHub montre une coche verte
(la branche `deploy` existe).

1. **Sauvegarde (5 minutes, à ne pas sauter)**
   - Avec FileZilla ou Cyberduck (identifiants : OVH › onglet **FTP - SSH**), téléchargez le dossier `www`
     sur votre Mac (`SITE MEDIA PRESTATIONS/ancien-wordpress`).
   - OVH › onglet **Bases de données** › base du WordPress › **…** › **Créer une sauvegarde**, puis téléchargez-la.
2. **Vider le dossier `www`** (via FileZilla : tout sélectionner dans `www` › Supprimer).
   OVH exige un dossier vide pour y associer Git.
3. OVH › **Mes sites** › ligne `mediaprestations.fr` (dossier `www`) › **…** › **Associer Git**
   - Dépôt : `git@github.com:mediaprestations/mediaprestations-site.git` (dépôt privé)
   - Branche : **deploy**
   - Suivez l'étape « clé SSH » proposée par OVH (à coller dans GitHub › Settings › Deploy keys).
   - Copiez l'**URL de webhook** affichée en bas du formulaire, puis dans GitHub :
     dépôt › Settings › Webhooks › Add webhook › collez l'URL (Content type : application/json).
4. Le site s'affiche en quelques minutes sur https://mediaprestations.fr
   (entre les étapes 2 et 3 le site est vide : faites-le à une heure calme).
5. Plus tard, une fois rassuré : supprimez la base de données WordPress dans l'onglet **Bases de données**.

## 3. Se connecter à l'espace admin

Adresse : https://mediaprestations.fr/admin/

Connexion par **jeton d'accès** GitHub (une fois par navigateur) :
1. GitHub › photo de profil › Settings › Developer settings › **Personal access tokens › Fine-grained tokens** › Generate new token
2. Repository access : *Only select repositories* → `mediaprestations-site`
3. Permissions › Repository permissions › **Contents : Read and write**
4. Générez, copiez le jeton, collez-le dans l'écran de connexion de l'admin.
   Gardez-le dans votre gestionnaire de mots de passe ; mettez une date d'expiration longue (1 an).

### Ajouter une couverture
Réalisations › **Nouvelle parution** → titre, numéro, catégorie, glisser la couverture (JPG),
date, case « Afficher sur l'accueil », lien flipbook éventuel → **Enregistrer**.
Le site est à jour en 1 à 2 minutes.

Conseils images : JPG en **RVB** (pas CMJN), environ 1200 px de large, moins de 3 Mo.
Le site crée automatiquement les versions légères (WebP).

### Modifier les textes « réglages »
Réglages du site › Coordonnées, chiffres et clients / Témoignages.
Les autres textes (titres, savoir-faire, méthode) sont dans `src/pages/index.astro` :
demandez-moi pour les faire évoluer.

## 4. Anciennes adresses et domaine .com

Les anciennes adresses du WordPress (/qui-sommes-nous, /prestations, /contacts…) sont redirigées
automatiquement vers les nouvelles pages (fichier `public/.htaccess`).

Le domaine **mediaprestations.com** (dossier `mediap-com`) : y mettre un simple fichier `.htaccess` :

```
RewriteEngine On
RewriteRule ^ https://mediaprestations.fr%{REQUEST_URI} [L,R=301]
```

et vérifier sa zone DNS (témoin « A » jaune) et son certificat SSL.

## 5. Formulaire de contact

`public/contact.php` envoie les demandes à l'adresse indiquée en haut du fichier
(`DESTINATAIRE`). Il utilise l'envoi d'e-mails de l'hébergement OVH : faites un essai
après la mise en ligne, et vérifiez les spams la première fois.

## 6. À compléter avant la mise en ligne

- `src/pages/mentions-legales.astro` : forme juridique, capital, SIRET, RCS.
- `src/data/site.json` (ou via l'admin) : lien LinkedIn si vous en avez un.
- Couvertures des ligues de football : ajoutez-les via l'admin, catégorie « Fédérations & associations ».
