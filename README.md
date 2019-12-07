# gSteam-Auth
A simple, object-oriented Steam authentication PHP script to make your life easier.

## Installation

### Installing via `composer`
- Package name : `gabyfle/gsteam-auth`
- Require :
    - PHP : **>=5.4.0**
    - [iignatov/lightopenid](https://github.com/iignatov/LightOpenID)

Command line : 
```
composer require gabyfle/gsteam-auth
```
### Installing without `composer`
- Download the lastest release : [here](https://github.com/Gabyfle/gSteam-Auth/releases)
- Download the **LightOpenID** library : [iignatov/lightopenid](https://github.com/iignatov/LightOpenID)
- Include these two libraries to your project
- Start using the library

## Quick start

### Connecting an user
```php
/* Do not forget to start the session before using SteamAuth's methods */
use Gabyfle;
$connect = new SteamAuth('localhost', 'XXXXXX');
/* Redirecting user to Steam's login servers */
$connect->open();
/* Checking if everything is okay with Steam */
$isConnected = $connect->check();
```
### Getting user's data
```php
/* Getting user data into a $_SESSION var */
$connect->getDataFromSteam();
/* Accessing to this data */
$steamId = $connect->getUserData('steamid');
```

### Disconnecting an user
```php
/* This will unset the $_SESSION variable */
$connect->disconnect();
```

## Features
#### Allow "snaking" methods
You can connecting an user and directly get his data by 'snaking' gSteam's methods like this :
```php
use Gabyfle;
$connect = new SteamAuth('localhost', 'XXXXXX');
$isConnected = $connect->open()->check();
```
#### Lightweight
**gSteam-Auth size** : 7577 bytes

**LightOpenID size** : 43611 bytes

**Total size** : 51188 bytes

#### Documented
**gSteam-Auth** is documented using PHPDoc standards. It's perfect when your using intelligent IDE such as PhpStorm or VSCode.

## License
**gSteam-Auth** is a free and open-source library. **gSteam-Auth** is licensed under **Apache-2.0** license, see `LICENSE` for deeper informations.
