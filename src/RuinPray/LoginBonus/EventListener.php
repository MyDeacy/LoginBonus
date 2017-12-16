<?php
namespace RuinPray\LoginBonus;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use RuinPray\LoginBonus\Main;

class EventListener implements Listener {

	private $m;

	public function __construct(Main $main){
		$this->m = $main;
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$main = $this->m;
		if($main->getLastLogin($name) !== false){
			if($main->isBonus($player)){
				$main->giveItem($player);
			}
		}else{
			$main->registerUser($name);
			$main->giveItem($player);
		}
		$main->setLastLogin($name);
	}

}