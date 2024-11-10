<?php
    session_start();
    require 'steamauth/steamauth.php';
    require 'main.conf.php';
    require 'functions.php';

    if (!isset($_SESSION['joinedSteamGroup'])) {
        $_SESSION['joinedSteamGroup'] = false;
    }

    if (isset($_SESSION['steamid'])) {
        require 'steamauth/userInfo.php';
        $_SESSION['steamID'] = $steamprofile['steamid'];
        $_SESSION['steamName'] = $steamprofile['personaname'];

        // Check for completion conditions
        if ((isset($_SESSION['guilds']) && $steamGroupUse && $_SESSION['joinedSteamGroup'] === true) ||
            (isset($_SESSION['guilds']) && !$steamGroupUse)) {
            header('location: complete');
            exit;
        }

        // Handle Discord login process
        if (isset($_GET['code'])) {
            $_SESSION['code'] = $_GET['code'];
            init($redirect, $client_id, $client_secret, $bot_token);
            $user = get_user();
            join_guild($guild_id);
            $_SESSION['guilds'] = get_guilds();
            header('location: ' . $redirect);
            exit;
        }
    }

    $steamLoggedIn = isset($_SESSION['steamid']);
    $discordLoggedIn = isset($_SESSION['guilds']);
    $discordButtonDisabled = $steamLoggedIn ? '' : 'disabled';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $SiteTitle; ?> &bull; Verification</title>
        <link rel="stylesheet" href="style.css">
        <script src="https://kit.fontawesome.com/9e14982b30.js" crossorigin="anonymous"></script>
        <script>
            var steamLoggedIn = <?php echo $steamLoggedIn ? 'true' : 'false'; ?>;
            var discordLoggedIn = <?php echo $discordLoggedIn ? 'true' : 'false'; ?>;
            
            var steamID = '<?php echo isset($_SESSION['steamID']) ? $_SESSION['steamID'] : ''; ?>';
            var steamName = '<?php echo isset($_SESSION['steamName']) ? $_SESSION['steamName'] : ''; ?>';

            function setButtonStates() {
                var discordButton = document.getElementById('discordButton');
                var steamButton = document.getElementById('steamButton');
                var steamGroupButton = document.getElementById('steamGroupButton');

                if (steamLoggedIn && discordLoggedIn) {
                    steamGroupButton.disabled = false;
                } else if (steamLoggedIn) {
                    discordButton.disabled = false;
                    steamGroupButton.disabled = true;
                } else {
                    discordButton.disabled = true;
                    steamButton.disabled = false;
                    steamGroupButton.disabled = true;
                }
            }

            window.onload = setButtonStates;

            function redirectToSteam() {
                document.getElementById('steamButton').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                window.location.href = "?login";
            }

            function redirectToDiscord() {
                document.getElementById('discordButton').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                window.location.href = "<?php echo url($client_id, $redirect, $scope); ?>";
            }

            function joinSteamGroup() {
                document.getElementById('steamGroupButton').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                startTimer();
                window.open("<?php echo $steamGroupURL; ?>");
            }

            var timer;
            var counter = 0;

            function startTimer() {
                timer = setInterval(checkIfJoinedSteamGroup, 1000);
            }

            function checkIfJoinedSteamGroup() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var joinedSteamGroup = JSON.parse(this.responseText).joinedSteamGroup;
                        if (joinedSteamGroup) {
                            clearInterval(timer);
                            document.getElementById('steamGroupButton').disabled = true;
                            location.href = "<?php echo htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8'); ?>";
                        }
                    }
                };
                xhttp.open("GET", "checkIfJoinedSteamGroup.php", true);
                xhttp.send();

                counter++;
                if (counter >= 300) {
                    clearInterval(timer);
                    counter = 0;
                }
            }
        </script>
    </head>

    <body>
        <div class="container">
            <div class="logo">
                <img src="<?php echo $logoURL; ?>" alt="" width="130" />
            </div>
            <nav>
                <ul>
                    <li><a href='/'>Home</a></li>
                </ul>
            </nav>
        </div>
        <div id="logincontent">
            <div class="step">
                <span>Step 1: Steam</span>
                <?php if (isset($_SESSION['steamID'])) : ?>
                    <div class="steamInfo">
                        <div>
                            <span class="label">Steam ID:</span>
                            <span class="value"><?php echo $_SESSION['steamID']; ?></span>
                        </div>
                        <div>
                            <span class="label">Steam Name:</span>
                            <span class="value"><?php echo $_SESSION['steamName']; ?></span>
                        </div>
                    </div>
                <?php else : ?>
                    <button style="font-size:30px;" class="steamButton" id="steamButton" onclick="redirectToSteam()" disabled>
                        <i class="fab fa-steam"></i>
                    </button>
                <?php endif; ?>
            </div>
            <br />
            <div class="step">
                <span>Step 2: Discord</span>
                <?php if (isset($_SESSION['guilds'])) : ?>
                    <div class="steamInfo">
                        <div>
                            <span class="label">Discord ID:</span>
                            <span class="value"><?php echo $_SESSION['user_id']; ?></span>
                        </div>
                        <div>
                            <span class="label">Discord Name:</span>
                            <span class="value"><?php echo $_SESSION['username']; ?></span>
                        </div>
                    </div>
                <?php else : ?>
                    <button style="font-size:30px;" class="discordButton" id="discordButton" onclick="redirectToDiscord()" disabled>
                        <i class="fab fa-discord"></i>
                    </button>
                <?php endif; ?>
            </div>
            <br />
            <div class="step">
                <span>Step 3: Steam Group</span>
                <button style="font-size:30px;" class="steamGroupButton" id="steamGroupButton" onclick="joinSteamGroup()" <?php echo !$steamLoggedIn ? 'disabled' : ''; ?>>
                    <i class="fab fa-steam"></i>
                </button>
            </div>
        </div>
    </body>
</html>
