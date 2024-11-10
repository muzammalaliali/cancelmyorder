<?php
    session_start();
    require('main.conf.php');
    if (!isset($_SESSION['steamid'], $_SESSION['user_id'])) header('location: ' . $redirect);

    include ('steamauth/userInfo.php');
    include ('functions.php');

    $ip = getUserIP();
    $date = new DateTime(null, new DateTimeZone('America/New_York'));
    $timestamp = $date->getTimestamp();

    $status = updateUser($database, $ip, $timestamp, $steamprofile, $webhook, $guild_id, $VerifiedRoleID);

    function getUserIP() {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) { $ip = $client; }
        elseif(filter_var($forward, FILTER_VALIDATE_IP)) { $ip = $forward; }
        else { $ip = $remote; }

        return $ip;
    }

    function updateUser($database, $ip, $timestamp, $steamprofile, $webhook, $guild_id, $VerifiedRoleID) {
        $check = $database->prepare("SELECT * FROM users WHERE discord_id = ? OR steam_id = ?");
        $check->execute(array($_SESSION['user_id'], $_SESSION['steamid']));
        $rowCount = $check->rowCount();
        $row = $check->fetch(PDO::FETCH_ASSOC);
        $user = get_user();
        $user = json_decode($user, true);
        $status = "";

        if ($rowCount >= 1) {
            if ($row['discord_id'] == $_SESSION['user_id'] && $row['steam_id'] == $_SESSION['steamid']) {
                $status = "Discord ID and Steam ID already exist. Updating simple data.";

                $updateQuery = $database->prepare("UPDATE users SET `steam_name` = ?, `discord_name` = ?, `discord_discrim` = ?, `user_locale` = ?, `timestamp` = ?, `access_token` = ? WHERE steam_id = ?");
                $updateQuery->execute(array($_SESSION['steam_personaname'], $_SESSION['username'], $_SESSION['user']['discriminator'], $_SESSION['user']['locale'], $timestamp, $_SESSION['access_token'], $_SESSION['steamid']));
            } else {
                $status = "Discord ID or Steam ID already exists, but not both. Updating full data.";

                $insertRemovedQuery = $database->prepare("INSERT INTO `users_removed` SELECT * FROM `users` WHERE steam_id = ? OR discord_id = ?");
                $insertRemovedQuery->execute(array($_SESSION['steamid'], $_SESSION['user_id']));

                $deleteQuery = $database->prepare("DELETE FROM `users` WHERE steam_id = ? OR discord_id = ?");
                $deleteQuery->execute(array($_SESSION['steamid'], $_SESSION['user_id']));

                $insertQuery = $database->prepare("INSERT INTO `users`(`id`, `steam_id`, `steam_name`, `discord_id`, `discord_name`, `discord_discrim`, `user_locale`, `user_ip`, `nitro`, `staff_flag`, `timestamp`, `access_token`) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?)");
                $insertQuery->execute(array($_SESSION['steamid'], $_SESSION['steam_personaname'], $_SESSION['user_id'], $_SESSION['username'], $_SESSION['user']['discriminator'], $_SESSION['user']['locale'], $ip, 0, 0, $timestamp, $_SESSION['access_token']));
                sendVerify($user, $steamprofile, $user['username'] . " has successfully verified their accounts!", $webhook, "#00FF00");
                add_role($guild_id, $user, $VerifiedRoleID);
            }

        } else {
            $status = "Discord ID and Steam ID do not exist. Inserting new row.";

            $insertQuery = $database->prepare("INSERT INTO `users`(`id`, `steam_id`, `steam_name`, `discord_id`, `discord_name`, `discord_discrim`, `user_locale`, `user_ip`, `nitro`, `staff_flag`, `timestamp`, `access_token`) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?)");
            $insertQuery->execute(array($_SESSION['steamid'], $_SESSION['steam_personaname'], $_SESSION['user_id'], $_SESSION['username'], $_SESSION['user']['discriminator'], $_SESSION['user']['locale'], $ip, 0, 0, $timestamp, $_SESSION['access_token']));
            sendVerify($user, $steamprofile, $user['username'] . " has successfully verified their accounts!", $webhook, "#00FF00");
            add_role($guild_id, $user, $VerifiedRoleID);
        }

        return $status;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $SiteTitle; ?> &bull; Verification</title>
        <link rel="stylesheet" href="style.css">
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/9e14982b30.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <div class="logo">
                <img src="<?php echo $logoURL; ?>" alt="" width="130"/>
            </div>
            <nav>
                <ul>
                    <li><a href='/'>Home</a></li>
                    <?php if (isset($_SESSION['user_id'])) { echo "<li><a href='unlink'>Unlink</a>"; } ?>
                </ul>
            </nav>
        </div>
        <div id="logincontent" class="alert alert-success">
            <h4 class="alert-heading">Verification Complete!</h4>
            <p><?php echo $status; ?></p>
            <hr>
            <p class="mb-0">Discord: <?php echo $_SESSION['username'] . "#" . $_SESSION['discrim'] . " - " . $_SESSION['user_id']; ?></p>
            <p class="mb-0">Steam: <?php echo $_SESSION['steam_personaname'] . " - " . $_SESSION['steamid']; ?></p>
            <div class="avatars">
                <img class="avatar" src="<?php echo "https://cdn.discordapp.com/avatars/{$_SESSION['user_id']}/{$_SESSION['user_avatar']}.png" ?>">
                <img class="avatar" src="<?php echo $_SESSION['steam_avatarfull'];?>">
            </div>
        </div>
    </body>
</html>