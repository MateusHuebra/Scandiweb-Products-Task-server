<?php

namespace Core\Dao;

require ('./Config.php');
use Config;
use PDO;

class Dao {

    private $connection;

	function __construct() {
        $dsn = 'mysql:host='.Config::HOST.';dbname='.Config::DATABASE;
        $this->connection = new \PDO($dsn, Config::USERNAME, Config::PASSWORD);
    }

	function run(string $sql, array $directValues = null, array $bindingValues = null) {
		$this->prepareAndExecute($sql, $directValues, $bindingValues);
	}

	function selectOne(string $sql, array $directValues = null, array $bindingValues = null) {
		$stmt = $this->prepareAndExecute($sql, $directValues, $bindingValues);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	function selectAll(string $sql, array $directValues = null, array $bindingValues = null) {
		$stmt = $this->prepareAndExecute($sql, $directValues, $bindingValues);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	private function prepareAndExecute(string $sql, array $directValues = null, array $bindingValues = null) {
		$stmt = $this->connection->prepare($sql);
		if($bindingValues) {
			foreach ($bindingValues as $key => $value) {
				$stmt->bindParam(':'.$key, $value);
			}
		}
		$stmt->execute($directValues);
		return $stmt;
	}

}