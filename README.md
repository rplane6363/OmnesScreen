# OmnesScreen - Plateforme d'affichage d'événements

## Description

OmnesScreen est une plateforme web professionnelle conçue pour afficher les événements des associations d'Omnes Education sur des écrans de télévision. Le système permet aux associations de proposer des événements et aux administrateurs de les valider avant affichage.

## Fonctionnalités

### 🖥️ Affichage public
- **Campus Citadelle** : Page d'affichage dédiée aux événements du campus Citadelle
- **Campus Citroën** : Page d'affichage dédiée aux événements du campus Citroën
- Actualisation automatique toutes les 60 secondes
- Interface optimisée pour télévisions (format large, lisibilité)
- Affichage de 10-20 événements maximum par campus

### 👥 Espace Associations
- Connexion sécurisée avec gestion des rôles
- Ajout d'événements avec validation des champs
- Suivi du statut des événements (en attente, approuvé, refusé)
- Interface intuitive et responsive

### 🛡️ Administration
- Validation/refus des événements proposés
- Gestion complète de tous les événements
- Statistiques d'utilisation
- Contrôle total sur l'affichage public

## Design

### Couleurs
- **Fond principal** : #190137 (violet foncé)
- **Couleur secondaire** : #5d0283 (violet)
- **Texte** : Blanc (#ffffff)

### Style
- Design professionnel inspiré d'Apple
- Interface épurée et moderne
- Cartes avec effets de survol
- Typographie lisible et élégante

## Structure du projet

```
OmnesScreen/
├── assets/
│   └── css/
│       └── style.css          # Styles principaux
├── config/
│   └── database.php           # Configuration BDD
├── includes/
│   └── auth.php              # Gestion authentification
├── models/
│   └── Event.php             # Modèle des événements
├── public/
│   ├── index.php             # Page d'accueil
│   ├── citadelle.php         # Affichage Campus Citadelle
│   ├── citroen.php           # Affichage Campus Citroën
│   ├── login.php             # Connexion
│   ├── association-dashboard.php  # Interface associations
│   ├── admin-dashboard.php   # Interface administration
│   └── logout.php            # Déconnexion
├── sql/
│   └── create_tables.sql     # Script de création BDD
└── README.md
```

## Installation

### Prérequis
- Serveur web (Apache/Nginx)
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone [url-du-repo]
   cd OmnesScreen
   ```

2. **Configuration de la base de données**
   - Créer une base de données MySQL
   - Importer le fichier `sql/create_tables.sql`
   - Modifier `config/database.php` avec vos paramètres :
   ```php
   private $host = 'localhost';
   private $db_name = 'omnes_screen';
   private $username = 'votre_utilisateur';
   private $password = 'votre_mot_de_passe';
   ```

3. **Configuration du serveur web**
   - Pointer le DocumentRoot vers le dossier `public/`
   - Activer mod_rewrite (Apache) ou équivalent (Nginx)

4. **Permissions**
   ```bash
   chmod 755 -R public/
   chmod 644 -R assets/
   ```

## Comptes de démonstration

### Administrateur
- **Utilisateur** : `admin`
- **Mot de passe** : `password`

### Association
- **Utilisateur** : `bde_citadelle`
- **Mot de passe** : `password`

## Utilisation

### Pour les télévisions
1. Naviguer vers `http://votre-domaine/citadelle.php` ou `citroen.php`
2. Mettre en plein écran
3. L'affichage se met à jour automatiquement

### Pour les associations
1. Se connecter via `http://votre-domaine/login.php`
2. Ajouter des événements via l'interface
3. Suivre le statut de validation

### Pour les administrateurs
1. Se connecter avec un compte administrateur
2. Valider/refuser les événements en attente
3. Gérer tous les événements existants

## Sécurité

- **Authentification** : Sessions PHP sécurisées
- **Mots de passe** : Hachage avec `password_hash()`
- **Validation** : Filtrage et échappement des données
- **Accès** : Contrôle des rôles et permissions

## Technologies utilisées

- **Backend** : PHP 7.4+, MySQL
- **Frontend** : HTML5, CSS3, JavaScript (Vanilla)
- **Architecture** : MVC simple
- **Sécurité** : Sessions PHP, PDO préparé

## Support

Pour toute question ou problème, contacter l'équipe technique d'Omnes Education.

---

**OmnesScreen** - Plateforme d'affichage d'événements professionnelle pour Omnes Education
