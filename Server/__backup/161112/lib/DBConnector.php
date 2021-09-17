<?php
class DBConnector
{
	public $db = null;

	function __construct()
	{
		$this->dbConnect();
	}

	private function dbConnect()
	{
		try {
			$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
			$this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS, $options);
		} catch(PDOException $e) {
			die("Failed to connect to the database : " . $e->getMessage());
		}
	}

	public function loadModel($model_name)
	{
		require 'application/model/' . strtolower($model_name) . '.php';
		return new $model_name($this->db);
	}
}
