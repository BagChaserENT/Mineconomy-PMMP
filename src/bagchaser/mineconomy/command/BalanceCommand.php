<?php

declare(strict_types=1);

namespace bagchaser\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use bagchaser\mineconomy\api\DataBase;

use CortexPE\Commando\BaseCommand;

class BalanceCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission($this->getPermission);
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if(!$sender instanceof Player){
            $sender->sendMessage("You must be a player to use this command!");
            return;
        }
        $balance = DataBase::getInstance()->getBalance($sender);
        $format = DataBase::getInstance()->format($balance);
        $sender->sendMessage("Your balance is " . $format);
    }

    protected function getPermission() : string{
        return "mineconomy.cmd";
    }
}
