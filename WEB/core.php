<?php

	$config = require_once __DIR__ . '/config.php';

	class RustStats
	{
		protected $database;

		protected $config;

		public function __construct($config)
		{
			$this->config = $config;
			$this->createDatabaseConnection();
		}

		protected function createDatabaseConnection()
		{
			$this->database = new mysqli($this->config['database']['host'],
										 $this->config['database']['user'],
										 $this->config['database']['password'],
										 $this->config['database']['name'],
										 $this->config['database']['port']);

			if($this->database->connect_errno) {
				die('Database connection failed. Please check your database configuration.');
			}
		}

		/**
		 * Returns existing data based on the provided Steam ID's
		 *
		 * @param int $serverId
		 * @param array $steamIds
		 * @return array
		 */
		public function getDataBySteamIds(int $serverId, array $steamIds): array
		{
			array_map(fn($steamId) =>  $this->database->real_escape_string($steamId), $steamIds);

			return $this->database
						->query("SELECT * FROM " . $this->config['database']['table'] . " WHERE steamid IN ('".implode("','", $steamIds)."') AND server_id = " . $this->database->real_escape_string($serverId))
						->fetch_all(MYSQLI_ASSOC);
		}

		/**
		 * Inserts an entry into the database or updates an existing one
		 *
		 * @param int $server
		 * @param array $player
		 * @return void
		 */
		public function updateDataForSteamId(int $server, array $player): void
		{
			if($player['new'] == 1) {
				$this->database->query("INSERT INTO " . $this->config['database']['table'] . "(server_id,steamid,name,playtime,kills,deaths,kdr,avatar) VALUES ($server, '".$player['steamid']."', '".$player['name']."', ".$player['playtime'].", ".$player['kills'].", ".$player['deaths'].", ".$player['kdr'].", '".$player['avatar']."')");
			} else {
				$this->database->query("UPDATE " . $this->config['database']['table'] . " SET name = '".$player['name']."', avatar = '".$player['avatar']."', playtime = ".$player['playtime'].", kills = ".$player['kills'].", deaths = ".$player['deaths'].", kdr = ".$player['kdr']." WHERE server_id = $server AND steamid = '".$player['steamid']."'");
			}
		}

		/**
		 * Returns all the statistics in the database
		 *
		 * @param int $start
		 * @param int $length
		 * @param string $search
		 * @param array $orderBy
		 * @param int $server
		 * @return array
		 */
		public function getStatistics(int $start, int $length, string $search, array $orderBy, int $server): array
		{
			return $this->database
						->query($this->buildStatisticsQuery($start, $length, $search, $orderBy, $server))
						->fetch_all(MYSQLI_ASSOC);
		}

		/**
		 * Builds the statistics query to retrieve the required information
		 *
		 * @param int $start
		 * @param int $length
		 * @param string $search
		 * @param array $orderBy
		 * @param int $server
		 * @return string
		 */
		protected function buildStatisticsQuery(int $start, int $length, string $search, array $orderBy, int $server): string
		{
			$query = "SELECT * FROM " . $this->config['database']['table'];

			$query .= " WHERE server_id = " . $this->database->real_escape_string($server);

			if(!empty($search)) {
				$query .= " AND (name LIKE '%".$this->database->real_escape_string($search)."%' OR steamid = '".$this->database->real_escape_string($search)."')";
			}

			$query .= " ORDER BY ".$this->database->real_escape_string($orderBy['key']). " " . $this->database->real_escape_string($orderBy['dir'] . ", name");

			$query .= " LIMIT " . $start . ', ' . $length;

			return $query;
		}

		/**
		 * Returns the total amount of players listed in the database
		 *
		 * @param int $server
		 * @return int
		 */
		public function getTotalPlayerAmount(int $server): int
		{
			$serverCountQuery = " WHERE server_id = " . $this->database->real_escape_string($server);

			$result = $this->database->query("SELECT COUNT(*) FROM " . $this->config['database']['table'] . $serverCountQuery);
			return $result->fetch_row()[0];
		}

		/**
		 * Converts the playtime in seconds to a readable time to display
		 * @param int $playtime
		 * @return string
		 */
		public static function convertPlaytimeToDisplay(int $playtime): string
		{
			$hours = floor($playtime / 3600);
			$mins = floor($playtime / 60 % 60);
			$secs = floor($playtime % 60);

			return sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
		}

		/**
		 * Secures a string against XSS attacks
		 *
		 * @param string $string
		 * @return string
		 */
		public static function secureString(string $string): string
		{
			return str_replace(['(', ')', '\'', '"'], '', htmlentities(strip_tags($string)));
		}
	}