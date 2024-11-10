<?php

	require_once __DIR__ . '/core.php';

	$rustStats = new RustStats($config);

	$start = (int) RustStats::secureString($_GET['start']);
	$length = (int) RustStats::secureString($_GET['length']);
	$search = RustStats::secureString($_GET['search']['value']);
	$columns = (array) $_GET['columns'];
	$order = (array) $_GET['order'];
	$server = (int) RustStats::secureString($_GET['server']);

	if(!array_key_exists($server, $config['servers']))
		die('Requested Server ID does not exist');

	$orderBy = [
		'key' => RustStats::secureString($columns[(int) $order[0]['column']]['name']),
		'dir' => RustStats::secureString($order[0]['dir']),
	];

	$statistics = $rustStats->getStatistics($start, $length, $search, $orderBy, $server);

	$data = [];

	foreach($statistics as $statistic) {
		$data[] = [
			'avatar' => $statistic['avatar'],
			'steamid' => $statistic['steamid'],
			'name' => $statistic['name'],
			'playtime'  => $statistic['playtime'],
			'playtimeProcessed' => RustStats::convertPlaytimeToDisplay($statistic['playtime']),
			'kills'  => $statistic['kills'],
			'deaths'  => $statistic['deaths'],
			'kdr'  => $statistic['kdr'],
		];
	}

	echo json_encode([
		'draw' => (int) RustStats::secureString($_GET['draw']),
		'recordsTotal' => $rustStats->getTotalPlayerAmount($server),
		'recordsFiltered' => (empty($search)) ? $rustStats->getTotalPlayerAmount($server) : count($statistics),
		'data' => $data,
	]);

