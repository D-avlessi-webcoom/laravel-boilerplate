# Laravel Boilerplate avec Authentification et Gestion des Rôles

Un boilerplate Laravel prêt à l'emploi avec système d'authentification complet et gestion des rôles et permissions.

## 🚀 Commandes utiles

### Développement
```bash
# Démarrer l'environnement de développement
composer dev

# Exécuter les tests
composer test

# Lancer l'analyse de code avec Pint
./vendor/bin/pint
```

### Base de données
```bash
# Exécuter les migrations
php artisan migrate

# Exécuter les seeders
php artisan db:seed

# Rafraîchir la base de données et réexécuter les seeders
php artisan migrate:fresh --seed
```

### Génération de code
```bash
# Générer un nouveau contrôleur
php artisan make:controller NomDuControleur

# Générer un nouveau modèle avec migration
php artisan make:model NomDuModele -m

# Générer un nouveau service
php artisan make:service NomDuService

# Générer un CRUD complet pour un modèle existant
php artisan make:crud NomDuModele
```

#### Commande `make:crud`

La commande `make:crud` génère automatiquement un contrôleur CRUD complet pour un modèle existant avec les fonctionnalités suivantes :

- Méthodes CRUD complètes (index, store, show, update, destroy)
- Gestion des erreurs avec try/catch
- Réponses JSON structurées
- Validation des données
- Routes API RESTful

**Options :**
- `{model}` : (Optionnel) Nom du modèle pour lequel générer le CRUD. Si non spécifié, une liste des modèles disponibles sera affichée.

**Exemple :**
```bash
# Générer un CRUD pour le modèle User
php artisan make:crud User
```

**Fonctionnalités :**
- Vérification de l'existence du modèle
- Demande de confirmation avant d'écraser un contrôleur existant
- Détection automatique des routes existantes pour éviter les doublons
- Messages d'erreur et de succès en français
- Journalisation complète des erreurs dans `storage/logs/laravel.log`
- Gestion sécurisée des erreurs sans exposer de détails sensibles
- Structure de réponse standardisée :
  ```json
  {
    "success": true,
    "message": "Message de succès",
    "data": {},
    "errors": []
  }
  ```

**Journalisation des erreurs :**
- Toutes les erreurs sont automatiquement enregistrées avec :
  - Message d'erreur détaillé
  - Contexte de la requête (données, utilisateur, etc.)
  - Stack trace pour le débogage
  - Horodatage précis
- Les erreurs sont classées par type d'opération (création, lecture, mise à jour, suppression)
- Les messages d'erreur utilisateur sont génériques pour la sécurité

**Bonnes pratiques :**
1. Vérifiez régulièrement les fichiers de logs dans `storage/logs/`
2. Configurez un système de surveillance des logs pour les environnements de production
3. Utilisez la commande `php artisan pail` pour surveiller les logs en temps réel

## 📦 Packages inclus

### Principaux
- **Laravel Sanctum** - Authentification API légère
- **Spatie Laravel Permission** - Gestion des rôles et permissions
- **Laravel Tinker** - Console interactive pour Laravel

### Développement
- **Laravel Sail** - Environnement de développement Docker
- **Laravel Pint** - Outil de formatage de code
- **Pest PHP** - Framework de test élégant
- **Reliese Laravel** - Génération de code pour les modèles
- **Laravel Pail** - Outil de journalisation en temps réel

## 🔄 Utilisation de Reliese Laravel

Reliese Laravel est un outil puissant pour générer automatiquement du code à partir de votre base de données. Voici comment l'utiliser :

### Configuration initiale

1. Publier la configuration :
   ```bash
   php artisan vendor:publish --provider="Reliese\Coders\CodersServiceProvider"
   ```
   Ceci créera un fichier `config/models.php` que vous pouvez personnaliser.

2. Configurer la connexion à la base de données dans `.env` si ce n'est pas déjà fait.

### Génération des modèles

Pour générer les modèles à partir de votre base de données :
```bash
php artisan code:models
```

Options utiles :
- `--table=nom_table` : Générer un modèle spécifique
- `--schema=nom_schema` : Spécifier un schéma de base de données
- `--connection=ma_connexion` : Utiliser une connexion spécifique
- `--suffix=` : Ajouter un suffixe aux noms de modèles
- `--namespace=` : Définir l'espace de noms personnalisé

### Personnalisation des modèles

Les modèles générés incluent des fonctionnalités avancées :
- Relations automatiquement détectées
- Casts pour les types de colonnes
- Règles de validation
- Configuration des champs remplissables (fillable)

### Mise à jour des modèles

Pour mettre à jour les modèles existants sans écraser vos modifications :
```bash
php artisan code:models --ignore=updated_at,created_at
```

### Configuration avancée

Personnalisez `config/models.php` pour :
- Définir des espaces de noms personnalisés
- Configurer le format des noms de modèles
- Définir des relations personnalisées
- Configurer les types de retour PHPDoc

### Bonnes pratiques

1. Versionnez toujours vos modèles générés
2. Utilisez les modèles de base (BaseModel) pour personnaliser le comportement
3. Consultez la [documentation officielle](https://github.com/reliese/laravel) pour des fonctionnalités avancées

### Tests
- **Pest PHP** - Framework de test élégant
- **Mockery** - Création de mocks pour les tests
- **Faker** - Génération de données de test

## Fonctionnalités

- **Authentification**
  - Connexion/Déconnexion
  - Gestion des jetons d'API avec Laravel Sanctum
  - Protection des routes API

- **Gestion des Utilisateurs**
  - CRUD complet des utilisateurs
  - Attribution de rôles aux utilisateurs
  - Gestion des profils utilisateurs

- **Gestion des Rôles et Permissions**
  - Création et gestion des rôles
  - Attribution de permissions aux rôles
  - Vérification des permissions dans les contrôleurs

- **Sécurité**
  - Protection CSRF
  - Validation des données
  - Hachage des mots de passe

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- Base de données (MySQL/PostgreSQL/SQLite)
- Node.js et NPM (pour les assets frontend)

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone [URL_DU_REPO]
   cd nom-du-projet
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances NPM**
   ```bash
   npm install
   npm run dev
   ```

4. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurer la base de données**
   - Créer une base de données
   - Mettre à jour le fichier `.env` avec les informations de connexion

6. **Exécuter les migrations et les seeders**
   ```bash
   php artisan migrate --seed
   ```
   
   Ceci créera :
   - Un utilisateur administrateur par défaut
   - Des rôles de base (admin, user)
   - Des permissions de base

## Utilisation

### Démarrer le serveur de développement
```bash
php artisan serve
```

### Endpoints API

#### Authentification
- `POST /api/login` - Connexion utilisateur
- `POST /api/logout` - Déconnexion utilisateur (nécessite authentification)

#### Utilisateurs
- `GET /api/users` - Lister tous les utilisateurs
- `POST /api/users` - Créer un nouvel utilisateur
- `GET /api/users/{id}` - Afficher un utilisateur
- `PUT/PATCH /api/users/{id}` - Mettre à jour un utilisateur
- `DELETE /api/users/{id}` - Supprimer un utilisateur

#### Rôles et Permissions
- `GET /api/roles` - Lister tous les rôles
- `POST /api/roles` - Créer un nouveau rôle
- `GET /api/roles/{id}` - Afficher un rôle
- `PUT/PATCH /api/roles/{id}` - Mettre à jour un rôle
- `DELETE /api/roles/{id}` - Supprimer un rôle
- `PUT/PATCH /api/roles/{role}/permissions` - Attribuer des permissions à un rôle

### Utilisation avec Postman

Une collection Postman est disponible dans le dossier `laravel-auto-crud` pour tester facilement les endpoints de l'API.

## Structure du Projet

```
app/
  ├── Http/
  │   ├── Controllers/     # Contrôleurs de l'application
  │   ├── Requests/        # Classes de validation
  │   └── Resources/       # Transformateurs de données
  ├── Models/              # Modèles Eloquent
  ├── Providers/           # Fournisseurs de services
  └── Services/            # Logique métier
config/                    # Fichiers de configuration
database/
  ├── migrations/          # Migrations de base de données
  ├── seeders/             # Données initiales
  └── factories/           # Usines de test
routes/                    # Définitions des routes
```

## Sécurité

- Tous les mots de passe sont hachés avec Bcrypt
- Protection contre les attaques CSRF
- Validation des entrées utilisateur
- Gestion des erreurs sécurisée

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus d'informations.

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou une pull request.
