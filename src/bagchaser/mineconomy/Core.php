<?php

declare(strict_types=1);

namespace bagchaser\mineconomy;

use pocketmine\plugin\PluginBase;

use bagchaser\mineconomy\api\DataBase;

use bagchaser\mineconomy\command\BalanceCommand;
use bagchaser\mineconomy\command\SeeBalanceCommand;
use bagchaser\mineconomy\command\AddMoneyCommand;
use bagchaser\mineconomy\command\RemoveMoneyCommand;
use bagchaser\mineconomy\command\SetMoneyCommand;

use CortexPE\Commando\PacketHooker;

class Core extends PluginBase {

    protected static self $instance;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        if(!PacketHooker::isRegistered()){
            PacketHooker::register($this);
        }
        $this->getServer()->getCommandMap()->registerAll("Mineconomy", [
            new BalanceCommand($this, "balance", "Check your balance", ["bal"]),
            new SeeBalanceCommand($this, "seebalance", "Check someone's balance", ["seebal"]),
            new AddMoneyCommand($this, "addmoney", "Add money to someone's balance", ["addbal"]),
            new RemoveMoneyCommand($this, "removemoney", "Remove money from someone's balance", ["removebal"]),
            new SetMoneyCommand($this, "setmoney", "Set someone's balance", ["setbal"])
        ]);
    }

    protected function onDisable() : void{
        DataBase::getInstance()->close();
    }

    public static function getInstance() : self{
        return self::$instance;
    }
}