<?php
namespace RuinPray\LoginBonus\Database;
use pocketmine\utils\Config;
use pocketmine\Plugin\PluginBase;
use RuinPray\LoginBonus\Main;

class ConfigLoader extends PluginBase {

	public function __construct(Main $main){
		$this->m = $main;
		if(!file_exists($this->m->getDataFolder())){
			mkdir($this->m->getDataFolder(), 0744, true);
		}
		$this->c = new Config($this->m->getDataFolder() . "config.yml", Config::YAML, [
			'1' => [
				"id" => 1,
				"meta" => 0,
				"amount" => 1
			],
			'2' => [
				"id" => 5,
				"meta" => 1,
				"amount" => 3
			]
		]);
	}
	public function getSettings(): array{
		return $this->c->getAll();
	}
}