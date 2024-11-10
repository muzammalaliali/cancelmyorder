<?php
    session_start();
    include('steamauth/SteamConfig.php');
    include('main.conf.php');
    
    $steamid = $_SESSION['steamID'];
    
    if ($_SESSION['joinedSteamGroup'] === false) {
        $url = "http://api.steampowered.com/ISteamUser/GetUserGroupList/v1/?key={$steamauth['apikey']}&steamid={$steamid}";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $search = curl_exec($ch);
        curl_close($ch);
        $raw = json_decode($search, true);
    
        $joinedSteamGroup = false;
    
        if (($raw['response']['success']) && (!is_null($raw['response']['groups']))) {
            foreach ($raw['response']['groups'] as $key => $value) {
                if ($value['gid'] == $steamGroupID) {
                    $joinedSteamGroup = true;
                    break;
                }
            }
        }
    
        if ($joinedSteamGroup) {
            $_SESSION['joinedSteamGroup'] = true;
        }
    } else {
        $joinedSteamGroup = true;
    }
    
    echo json_encode(array('joinedSteamGroup' => $joinedSteamGroup));    
?>
