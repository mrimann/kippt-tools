<?php

class kipptBackup {
	var $userName;
	var $apiToken;

	var $dbName;
	var $dbUser;
	var $dbPass;

	// limit of clips to fetch per run, API limit is 200
	var $limit = 20;

	// the total count of clips the API delivers
	var $totalCount;

	// counters for the statistics
	var $countUpToDate = 0;
	var $countUpdated = 0;
	var $countNew = 0;

	public function setUsername($userName) {
		$this->userName = $userName;
	}

	public function setApiToken($apiToken) {
		$this->apiToken = $apiToken;
	}

	public function setDatabaseCredentials($dbName, $dbUser, $dbPass) {
		$this->dbName = $dbName;
		$this->dbUser = $dbUser;
		$this->dbPass = $dbPass;
	}

	public function createBackup() {
		$this->connectToDatabase();

		// get total number of clips
		$ch = curl_init('https://kippt.com/api/clips/?limit=1&offset=0');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'X-Kippt-Username: ' . $this->userName,
				'X-Kippt-API-Token: ' . $this->apiToken
			)
		);

		$apiResult = curl_exec($ch);
		curl_close($ch);

		// decode the JSON data
		$apiData = json_decode($apiResult);

		// See if the response contains some kind of a message - usually a hint that something
		// went horribly wrong
		if (isset ($apiData->message)) {
			echo 'Something went wrong:' . "\n";
			exit($apiData->message . "\n");
		}

		// starting to fetch clips
		$offset = 0;
		echo 'Backing up all kippt.com bookmarks for user ' . $this->userName;
		echo "\n";


		// Do some requests to the API to fetch the clips in batches
		$run = 1;
		$apiData = $this->fetchClips($this->limit, 0);

		while (count($apiData->objects) > 0) {
			if (isset($apiData->objects)) {
				foreach ($apiData->objects as $clip) {
					$this->compareOrImportClip($clip);
				}
			}

			// fetch the next stuff
			$offset = $run * $this->limit;
			$apiData = $this->fetchClips($this->limit, $offset);
			$run++;
		}
		echo "\n";
		echo 'Backup statistics:' . "\n";
		echo '------------------' . "\n";
		echo 'New       : ' . $this->countNew . "\n";
		echo 'Updated   : ' . $this->countUpdated . "\n";
		echo 'Unchanged : ' . $this->countUpToDate . "\n\n";

	}

	/**
	 * Fetches a bunch of clips from the kippt API and return the result
	 * as decoded array (no json anymore)
	 *
	 * @param int the limit (number of clips to fetch)
	 * @param int the offset
	 * @return array
	 */
	private function fetchClips($limit, $offset) {
		// fetch the next batch of clips from the API
		$ch = curl_init('https://kippt.com/api/clips/?limit=' . $limit . '&offset=' . $offset);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'X-Kippt-Username: ' . $this->userName,
				'X-Kippt-API-Token: ' . $this->apiToken
			)
		);

		$apiResult = curl_exec($ch);
		curl_close($ch);

		// decode the JSON data
		$apiData = json_decode($apiResult);

		// See if the response contains some kind of a message - usually a hint that something
		// went horribly wrong
		if (isset ($apiData->message)) {
			echo 'Something went wrong:' . "\n";
			exit($apiData->message . "\n");
		}

		return $apiData;
	}

	private function compareOrImportClip($clip) {
		$dbResult = mysql_query('SELECT id,updated FROM clips WHERE id=' . $clip->id);
		if (mysql_num_rows($dbResult) == 1) {
			$this->countUpToDate++;

			$row = mysql_fetch_assoc($dbResult);
			if ($row['updated'] < $clip->updated) {
				$this->countUpdated++;
				echo "\n" . 'Updating clip ID ' . $clip->id;
				$this->updateExistingClip($clip);
			}
		} else {
			echo "\n" . 'Inserting clip ID ' . $clip->id;
			$this->countNew++;
			$this->insertNewClip($clip);
		}

	}

	private function updateExistingClip($clip) {
		mysql_query('DELETE from clips WHERE id=' . $clip->id . ' LIMIT 1;');
		$this->insertNewClip($clip);
	}

	private function insertNewClip($clip) {
		$importResult = mysql_query('INSERT into clips SET id="' . $clip->id .
				'", title="' . addslashes($clip->title) .'"' .
				', url="' . $clip->url .'"' .
				', list="' . $clip->list .'"' .
				', notes="' . addslashes($clip->notes) .'"' .
				', url_domain="' . $clip->url_domain .'"' .
				', created="' . $clip->created .'"' .
				', updated="' . $clip->updated .'"' .
				', resource_uri="' . $clip->resource_uri .'"'
		);

		if (!$importResult) {
			echo 'INSERT error: ' . mysql_error() . "\n\n";
		}
	}

	private function connectToDatabase() {
		mysql_connect(
			'localhost',
			$this->dbUser,
			$this->dbPass
		);
		mysql_select_db($this->dbName);
		mysql_query('SET NAMES utf8;');
	}

}

// include the config from external file
include 'config.php';

// Instaniate the backup object
$backup = new kipptBackup();
$backup->setUsername($kipptUsername);
$backup->setApiToken($kipptApiToken);
$backup->setDatabaseCredentials(
	$dbName,
	$dbUser,
	$dbPass
);

// Go and execute the backup
$backup->createBackup();

?>