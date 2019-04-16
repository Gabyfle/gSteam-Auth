<?php
session_start();

require __DIR__ . "/vendor/autoload.php";
/**
 * This page will be used to display the login button, and after userdata
 */
$config = [
    'domainName' => 'localhost',
    'steamKey' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXX'
];

$steam = new Gabyfle\SteamAuth($config['domainName'], $config['steamKey']);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>gSteam-Auth | Example demo</title>
        <meta charset="UTF-8" />
    </head>
    <body>
    <?php
    if ($steam->__check()) {
        ?>
        <ul>
            <li>Pseudonyme : <?= $steam->getUserData('personaname'); ?></li>
            <li>SteamID : <?= $steam->getUserData('steamid'); ?></li>
            <li>Avatar : <img src="<?= $steam->getUserData('avatarfull'); ?>" alt="your avatar" width="64" height="64"/></li>
            <li>Profile link : <a href="<?= $steam->getUserData('profileurl'); ?>" target="_blank"><?= $steam->getUserData('profileurl'); ?></a></li>
            <li>Realname : <?= $steam->getUserData('realname'); ?> </li>
        </ul>
        <?php
    } else {
        ?>
        <a href="login.php">Sign in throught Steam</a>
        <?php
    }
    ?>
    </body>
</html>