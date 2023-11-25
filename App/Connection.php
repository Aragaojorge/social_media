<?php

namespace App;

class Connection {

	public static function getDb() {
		try {
			// como tive problema com o xampp, tive que reinstalar e criar esse novo db, não estou usando o twitter_clone
			$conn = new \PDO(
				"mysql:host=127.0.0.1;dbname=useEste;charset=utf8",
				"root",
				"" 
			);

			return $conn;

		} catch (\PDOException $e) {
			//.. tratar de alguma forma ..//
		}
	}
}

?>