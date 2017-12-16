<?php

namespace RuinPray\LoginBonus;

use pocketmine\plugin\PluginBase;
use RuinPray\LoginBonus\EventListener;
use RuinPray\LoginBonus\Database\{Sqlite3Database,ConfigLoader};
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\utils\Utils;

class Main extends PluginBase {
	
	private $items = []; //array
	public $db;

	public function onEnable(){
		$cf = new ConfigLoader($this);
		$this->db = new Sqlite3Database($this->getDataFolder(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->items = $cf->getSettings();
	}

	public function getDateElements(): array{
		$date = date("Y-m-d");
		$date = explode("-", $date);
		$result = [
			"year" => $date[0],
			"month" => $date[1],
			"day" => $date[2]
		];
		return $result;
	}

	public function setLastLogin(string $name): void{
		$this->db->setLastLogin($name);
	}

	public function getLastLogin(string $name){
		return $this->db->getLastLogin($name);
	}

	public function registerUser(string $name): void{
		$this->db->registerUser($name);
	}

	public function isBonus(Player $player): bool{
		$name = $player->getName();
		$last = $this->db->getLastLogin($name);
		$now = $this->getDateElements();
		if($last["day"] === $now["day"] && $last["month"] === $now["month"] && $last["year"] === $now["year"]){
			return true;
		}
		return false;
	}

	public function giveItem(Player $player): void{
		$max = count($this->items);
		$key = mt_rand(1, $max);
		$data = $this->items[$key];
		$player->getInventory()->addItem($item = Item::get($data["id"], $data["meta"], $data["amount"]));
		$player->sendMessage(">>ログインボーナス！! \n>>".$item->getName()." を配布しました！");
	}
}
