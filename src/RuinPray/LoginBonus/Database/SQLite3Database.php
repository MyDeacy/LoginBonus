<?php
namespace RuinPray\LoginBonus\Database;

use RuinPray\LoginBonus\Main;

class Sqlite3Database {

	private $db;

	public function __construct(string $dir, Main $main){
		$this->main = $main;
		$this->db = new \SQLite3($dir."data.sqlite");
		$this->db->exec("CREATE TABLE IF NOT EXISTS userdata (
			name TEXT NOT NULL PRIMARY KEY,
			year INTEGER NOT NULL,
			month INTEGER NOT NULL,
			day INTEGER NOT NULL
		)");
	}

	public function registerUser(string $name): void{
		$value = "INSERT INTO userdata (name, year, month, day) VALUES (:name, :year, :month, :day)";
		$this->setValue($name, $value);
	}

	public function getLastLogin(string $name){
		$value = "SELECT * FROM userdata WHERE name = :name";
		$db = $this->db->prepare($value);
		$db->bindValue(":name", strtolower($name), SQLITE3_TEXT);
		$result = $db->execute()->fetchArray(SQLITE3_ASSOC);
		if(empty($result)){
			return false;
		}
		return $result;
	}

	public function setLastLogin(string $name): void{
		$value = "UPDATE userdata SET year = :year, month = :month, day = :day WHERE name = :name";
		$this->setValue($name, $value);
	}

	private function setValue(string $name, string $value){
		$db = $this->db->prepare($value);
		$name = strtolower($name);
		$date = $this->main->getDateElements();
		$db->bindValue(":name", $name, SQLITE3_TEXT);
		$db->bindValue(":year", $date["year"], SQLITE3_TEXT);
		$db->bindValue(":month", $date["month"], SQLITE3_TEXT);
		$db->bindValue(":day", $date["day"], SQLITE3_TEXT);
		$db->execute();
	}
}