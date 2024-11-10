<?php
	$user = "SQL_USER";
	$pass = "SQL_PASS";
	$details = "mysql:dbname=DB_NAME;host=localhost";
	$database = new PDO($details, $user, $pass);

	$redirect = 'REDIRECT_URL';

	//Do you want to use the Steam group feature?//
	$steamGroupUse = true;
	//Do you want to use the Steam group feature?//

	$steamGroupURL = 'https://steamcommunity.com/groups/YourGroupName';
	
	//Group ID found by clicking "Edit Group Profile" on your group page
	$steamGroupID = '39098766';
	//Group ID found by clicking "Edit Group Profile" on your group page

	$VerifiedRoleID = ROLE_ID_123;
	$webhook = "WEBHOOK_URL";
	$logoURL = "LOGO_URL";
	$SiteTitle = "SERVER_NAME";

	$guild_id = GUILD_ID_123;

    $bot_token = 'BOT_TOKEN';
    $client_id = CLIENT_ID_123;
    $client_secret = 'SECRET';


    // DO NOT MODIFY //
    $tokenURL = 'https://discordapp.com/api/oauth2/token';
    $scope = 'identify guilds guilds.join';
    // DO NOT MODIFY //
?>