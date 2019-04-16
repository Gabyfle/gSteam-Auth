# gSteam-Auth
A simple, object-oriented Steam authentication PHP script to make your life easier.

## Installation

### Installing via `composer`
- Package name : `Gabyfle/gSteam-Auth`
- Require :
    - PHP : **>=5.4.0**
    - [iignatov/lightopenid](https://github.com/iignatov/LightOpenID)

Command line : 
```
composer require Gabyfle/gSteam-Auth
```
### Installing without `composer`
- Download the lastest release : here
- Download the **LightOpenID** library : [iignatov/lightopenid](https://github.com/iignatov/LightOpenID)
- Include these two libraries to your project
- Start using the library

## Quick start

### Connecting an user
```php
use Gabyfle;
$connect = new gSteamAuth('localhost', 'XXXXXX');
/* Redirecting user to Steam's login servers */
$connect->__open();
/* Checking if everything is okay with Steam */
$connect->__check();
```
### Getting user's data
```php
/* Getting the whole data as an associative table */
$userData = $connect->getUserData();
/* Getting only one information */
$steamId = $connect->getUserData('steamid');
```

## Features
#### Allow "snaking" methods
You can connecting an user and directly get his data by 'snaking' gSteam's methods like this :
```php
use Gabyfle;
$connect = new gSteamAuth('localhost', 'XXXXXX');
$userData = $connect->__open()->_check()->getUserData();
```
#### Lightweight
**gSteam-Auth size** : 7577 bytes
**LightOpenID size** : 43611 bytes
**Total size** : 51188 bytes

#### Documented
**gSteam-Auth** is documented using PHPDoc standards. It's perfect when your using intelligent IDE such as PhpStorm or VSCode.

## License
**gSteam-Auth** is a free and open-source library. **gSteam-Auth** is licensed under **Apache-2.0** license, see `LICENSE` for deeper informations.