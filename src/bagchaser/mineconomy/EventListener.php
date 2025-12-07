<?php

declare(strict_types=1);

namespace bagchaser\mineconomy;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use bagchaser\mineconomy\api\DataBase;

class EventListener implements Listener {

    public function onJoin(PlayerJoinEvent $event) : void{
        $player = $event->getPlayer();
        if(DataBase::getInstance()->isNew($player)){
            DataBase::getInstance()->create($player);
        }
    }
}