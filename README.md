# crypto-compta

Front projet UF Web en React. Thème noyau : un gestionnaire de trésorerie d'actif crypto.

Binance, coinbase, cryptocom sont certes d'excellentes plateformes pour le trading de crypto-monnaie mais elles n'offrent qu'un spectre limité de son investissement total avec très peu voire aucun indices statistiques.

C'est là que notre application entre en jeu, le but : regrouper ses investissements en crypto-monnaie dans un même lieu et offrir à l'utilisateur des données macro/micro synthétisées pour une meilleure vision de l'ensemble de ses investissements.

## 💻 Technologies

- **Back :**

  - Symfony 5.2.6
  - ApiPlatform
  - BDD SQL MySQL 5.7

- **Front :**
  - React 17.0.2
  - TailwindCSS 2.1.0
  - Twin Macro 2.3.3

## 🔱 Routing

```php
    - { path: '^/web/login', roles: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: '^/web/register', roles: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: '^/api/docs', roles: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: '^/api/auth', roles: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: '^/api/auth/refresh', roles: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: '^/api/register', roles: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: '^/web', roles: IS_AUTHENTICATED_FULLY }
    - { path: '^/api', roles: IS_AUTHENTICATED_FULLY }
    - { path: '^/', roles: IS_AUTHENTICATED_FULLY }
```

## ⚙️ Fonctionnalités et spécificités techniques

- **BACK** :

  - API :

    - Séparée en deux parties : api `/api/*` et web `/web/*`
    - Doc OpenApi `/api/docs`
    - Routes customs (ex: `/api/me`, `/api/transactions/{user}`, `/api/get_token_payloads}`...)
    - Filtres : `Pagination, PropertyFilter, SearchFilter, DateFilter`...
    - Custom fields (ex: `isMe: true`)
    - Custom validation (ex: `isValidOwner`)
    - Custom normalizer (ex: `owner:read`)
    - Entity Listeners
    - Data Persisters
    - Custom group context (ex: `admin:read`)
    - AutoGroup pour les contextes de normalization/denormalization

  - API CoinGecko v3:

    - Base URL : `api.coingecko.com/api/v3`
    - Très complète et performante
    - Utilisée pour récupérer toutes sortes d'informations liées aux crypto-monnaies (ex: `monnaies, valeurs, dates, icons..`)

  - Security :

    - Firewall _JWTGuard_ pour `/api/*` et _SymfonyHttpOnlyGuard_ pour `/web/*`
    - JWT Refresh token
    - Customs voter (ex: `['EDIT'] Portfolio Voter`)
    - Restrictions d'accès selon Roles ou Auth à des url patterns
    - Gestion de la propriété sur certaines ressources
    - ID utilisateurs de type Ulid

  - Base de données :

    - Node mySQL 5.7
    - Requêtes depuis API uniquement avec l'ORM Doctrine
    - MCD : [Lien de l'image](https://drive.google.com/file/d/1kFUwTS-wEeVqmd1bkeAiavbvap1JaM_F/view?usp=sharing)
    - Les jeux de données seront aussi complétés avec ceux de l'API de CoinGecko

- **FRONT** :

  - Authentification (`/register` & `/login`) :

    - Authentification JWT avec ApiPlatform
    - JWT refresh token pour éviter de devoir retaper ses logs

  - Gestion d'utiisateurs :

    - Session utilisateur stockée en sessionStorage (donc re-auth à chaque fermeture du client web)
    - User infos requêtable avec `GET /me` dans l'API

  - Security :

    - Restrictions de certaines routes aux anonymes
    - Nettoyage des valeurs des states
    - User passé en sessionStorage au lieu du localStorage

  - Composant MyAssets (`/myassets`) :

    - Screenshot de la page : [Image](https://github.com/Mockinbrd/front-uf-web-b3/tree/master/doc/myassets.png)
    - C'est ici que l'utilisateur aura des statistiques de tous ses portfolios réunis.

  - Page portfolios (`/portfolios`) :

    - Screenshot de la page : [Image](https://github.com/Mockinbrd/front-uf-web-b3/tree/master/doc/portfolios.png)
    - C'est ici que l'utilisateur aura des statistiques d'un portfolio précis.

  - Page profil utilisateur (`/profile`) :

    - Profil utilisateur regroupant ses infos personnelles

  - Styling :

    - Styles des composants avec les librairie styled-components et twin.macro pour une personnalisation plus poussées et des performances accrues.

  - Burger menu :

    - Sidebar avec burger icon
    - Ajout dynamique d'un sous-menu si utilisateur connecté

  - Animations :

    - Implentation d'animations avec la librairie react-animations couplée avec styled-components.

  - Dark Mode :
    - Possibilité de switch de thème `["light","dark"]` depuis tout le site.
