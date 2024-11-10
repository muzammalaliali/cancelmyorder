<?php

	require_once __DIR__ . '/core.php';

	$rustStats = new RustStats($config);

	// The event map shows which event is corresponding to which database
	// column, so if we add multiple ones in the future, we can change that.
	// EVENT NAME => DATABASE COLUMN
	$eventMap = [
		'playtime'  => 'playtime',
		'kill'      => 'kills',
		'death'     => 'deaths',
	];

	// Only allow requests with the correct secret provided
	$secret = null;
	if(isset($_GET['secret']))
		$secret = RustStats::secureString($_GET['secret']);

	if($secret != $config['secret']) {
		http_response_code(401);
		echo json_encode(['error' => 'Invalid secret key']);
		exit;
	}

	// Only allow requests for an existing server
	$server = null;
	if(isset($_GET['server']))
		$server = (int) RustStats::secureString($_GET['server']);

	if(!array_key_exists($server, $config['servers'])) {
		http_response_code(400);
		echo json_encode(['error' => 'Provided server does not exist']);
		exit;
	}

	// Get POST body from the request
	$events = json_decode(file_get_contents('php://input'), true);

	// In case someone tests the API without actual content
	if($events == null) {
		http_response_code(200);
		echo json_encode(['error' => 'Request body empty, not processing events']);
		exit;
	}

	// If no body is present, print error
	if(count($events) == 0) {
		http_response_code(200);
		echo json_encode(['error' => 'Events object empty, not processing events']);
		exit;
	}

	$updatedRecords = [];

	// Get all the steamid's to request steam avatars
	$steamIds = [];
	foreach($events as $event) {

		if(array_key_exists('steamid', $event))
			$steamIds[] = RustStats::secureString($event['steamid']);

	}

	$steamIds = array_unique($steamIds);

	$chunkedSteamIds = array_chunk($steamIds, 50);

	foreach($chunkedSteamIds as $batchOfSteamIds) {

		// Request steam avatars
		$avatarRequest = json_decode(file_get_contents('https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$config['steamApiKey'].'&steamids=' . implode(',', $batchOfSteamIds)), true);

		// Set steamid's as unique array keys where we are adding the event information
		// afterwards, including the requested avatar. Any event will get added to this afterwards
		foreach($avatarRequest['response']['players'] as $player) {
			$updatedRecords[$player['steamid']] = [
				'steamid'   => $player['steamid'],
				'name'      => $player['personaname'],
				'avatar'    => $player['avatar'],
				'playtime'  => 0,
				'kills'     => 0,
				'deaths'    => 0,
				'kdr'       => 0,
				'new'       => 1,
			];
		}

	}

	// Collect all events for specific players and the values to update
	foreach($events as $event) {
		$databaseColumnName = $eventMap[RustStats::secureString($event['_event'])];
		$updatedRecords[$event['steamid']][$databaseColumnName] = (int) $updatedRecords[$event['steamid']][$databaseColumnName] + (int) $event['amount'];
	}

	// Get all current values for the provided steam ids
	$currentValues = $rustStats->getDataBySteamIds($server, $steamIds);

	// Calculate new values
	foreach($currentValues as $currentValue) {
		$updatedRecords[$currentValue['steamid']]['playtime'] = (int) $updatedRecords[$currentValue['steamid']]['playtime'] + (int) $currentValue['playtime'];
		$updatedRecords[$currentValue['steamid']]['kills'] = (int) $updatedRecords[$currentValue['steamid']]['kills'] + (int) $currentValue['kills'];
		$updatedRecords[$currentValue['steamid']]['deaths'] = (int) $updatedRecords[$currentValue['steamid']]['deaths'] + (int) $currentValue['deaths'];
		$updatedRecords[$currentValue['steamid']]['new'] = 0;
	}

	// Calculate new KDR
	foreach($updatedRecords as $updatedRecord) {

		if($updatedRecord['deaths'] == 0)
			$kdr = $updatedRecord['kills'];
		else
			$kdr = $updatedRecord['kills'] / $updatedRecord['deaths'];

		$updatedRecords[$updatedRecord['steamid']]['kdr'] = round($kdr, 2);
	}

	// Save new values
	foreach($updatedRecords as $updatedRecord) {
		$rustStats->updateDataForSteamId($server, $updatedRecord);
	}

	// Return no error message to the server, done!
	echo json_encode(['error' => false]);