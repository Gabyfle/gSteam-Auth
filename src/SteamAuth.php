<?php
/**
 * gSteamAuth
 * A simple Steam authentication class to make your life easier.
 *
 * @author Gabriel Santamaria <gaby.santamaria@outlook.fr>
 * @copyright Gabriel Santamaria 2019
 * @license Apache 2.0
 */
namespace Gabyfle;

/**
 * Class SteamAuth
 * @package Gabyfle
 * @param $domainUrl string Your domain url
 */
class SteamAuth
{
    /**
     * @var \LightOpenID
     */
    private $openid;
    /**
     * @var string Steam Developer's API key
     */
    private $steamKey;
    /**
     * @var string User's steam id
     */
    private $steamId;

    public function __construct(string $domainUrl, string $steamKey)
    {
        try {
            $this->openid = new \LightOpenID($domainUrl);
            $this->openid->identity = 'https://steamcommunity.com/openid';
            $this->steamKey = $steamKey;
        }catch (\ErrorException $e) {
            throw new \Exception('An error occurred why trying to initialize LightOpenID : ' . $e->getMessage());
        }
    }

    /**
     * __open()
     * Redirect the user to steam openid provider
     * @return $this
     * @throws \ErrorException
     */
    public function open()
    {
        if(!$this->openid->mode)
            header('Location: ' . $this->openid->authUrl());
        elseif($this->openid->mode == 'cancel')
            throw new \Exception('User cancelled Steam connection.');
        else
            return $this;
    }

    /**
     * check()
     * Check if the user is connected
     * @return bool
     */
    public function check()
    {
        if (!isset($_SESSION['gSteamUserData']))
        {
            if($this->openid->validate())
            {
                $this->steamId = $this->openid->identity;
                return true;
            }
            else
                return false;
        } else
            return true;
    }

    /**
     * parseSteamId
     * Parse the user's steam id in the given identity (returned by OpenId, must be like : https://steamcommunity.com/openid/id/<id>)
     * @param string $identity
     * @return string
     */
    private function parseSteamId()
    {
        $pattern = '/^https?:\/\/(?:www.)?steamcommunity\.com\/openid\/id\/([0-9]+)\/*$/';
        preg_match($pattern, $this->steamId, $m);
        $this->steamId = $m[1];

        return $m[1];
    }

    /**
     * getDataFromSteam()
     * Call steam's API to get user's profile data (steam avatar, name & stuff)
     * @return $this
     */
    public function getDataFromSteam()
    {
        $steamUrl = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $this->steamKey . '&steamids=' . $this->parseSteamId();
        $json = file_get_contents($steamUrl);
        $contents = json_decode($json, true);
        foreach ($contents['response']['players'][0] as $key => $content)
            $_SESSION['gSteamUserData'][$key] = $content;

        return $this;
    }

    /**
     * getUserData
     * @param string $name
     * @return array|mixed|string
     * @see https://developer.valvesoftware.com/wiki/Steam_Web_API#GetPlayerSummaries_.28v0001.29
     *
     * List of available arguments (2019) (FROM VALVESOFTWARE WIKI):
     * ATTRIBUTE-------------------DESCRIPTION
     * steamid                      64bit SteamID of the user
     * personaname                  The player's persona name (display name)
     * profileurl                   The full URL of the player's Steam Community profile.
     * avatar                       The full URL of the player's 32x32px avatar. If the user hasn't configured an avatar, this will be the default ? avatar.
     * avatarmedium                 The full URL of the player's 64x64px avatar. If the user hasn't configured an avatar, this will be the default ? avatar.
     * avatarfull                   The full URL of the player's 184x184px avatar. If the user hasn't configured an avatar, this will be the default ? avatar.
     * personastate                 The user's current status. 0 - Offline, 1 - Online, 2 - Busy, 3 - Away, 4 - Snooze, 5 - looking to trade, 6 - looking to play. If the player's profile is private, this will always be "0", except if the user has set their status to looking to trade or looking to play, because a bug makes those status appear even if the profile is private.
     * communityvisibilitystate     This represents whether the profile is visible or not, and if it is visible, why you are allowed to see it. Note that because this WebAPI does not use authentication, there are only two possible values returned: 1 - the profile is not visible to you (Private, Friends Only, etc), 3 - the profile is "Public", and the data is visible.
     * profilestate                 If set, indicates the user has a community profile configured (will be set to '1')
     * lastlogoff                   The last time the user was online, in unix time.
     * commentpermission            If set, indicates the profile allows public comments.
     * realname                     The player's "Real Name", if they have set it.
     * primaryclanid                The player's primary group, as configured in their Steam Community profile.
     * timecreated                  The time the player's account was created.
     * gameid                       If the user is currently in-game, this value will be returned and set to the gameid of that game.
     * gameserverip                 The ip and port of the game server the user is currently playing on, if they are playing on-line in a game using Steam matchmaking. Otherwise will be set to "0.0.0.0:0".
     * gameextrainfo                If the user is currently in-game, this will be the name of the game they are playing. This may be the name of a non-Steam game shortcut.
     * cityid                       This value will be removed in a future update (see loccityid)
     * loccountrycode               If set on the user's Steam Community profile, The user's country of residence, 2-character ISO country code
     * locstatecode                 If set on the user's Steam Community profile, The user's state of residence
     * loccityid                    An internal code indicating the user's city of residence. A future update will provide this data in a more useful way.
     */
    public static function getUserData(string $name = '')
    {
        if (!isset($_SESSION['gSteamUserData']))
            return '[gSteam-Auth] Can\'t access to user\'s data since he isn\'t connected.';
        if($name == '')
            return $_SESSION['gSteamUserData'];
        elseif (isset($_SESSION['gSteamUserData'][$name]))
            return $_SESSION['gSteamUserData'][$name];
        else
            return '[gSteam-Auth] UserData not found.';
    }

    /**
     * disconnect
     * Unsets $_SESSION['gsteamUserData'] variable
     */
    public static function disconnect()
    {
        if (isset($_SESSION['gSteamUserData']))
            unset($_SESSION['gSteamUserData']);
    }
}