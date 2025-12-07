<?php

declare(strict_types=1);

namespace bagchaser\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use bagchaser\mineconomy\api\DataBase;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\RawStringArgument;

class SeeBalanceCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission($this->getPermission());
        $this->registerArgument(0, new RawStringArgument("player"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if(!$sender instanceof Player){
            $sender->sendMessage("You must be a player to use this command!");
            return;
        }
        $sql = DataBase::getInstance();
        if($sql->isNew($args["player"])){
            $sender->sendMessage("Player not found!");
            return;
        }
        $balance = $sql->getBalance($args["player"]);
        $format = $sql->format($balance);
        $sender->sendMessage($args["player"] . " balance is " . $format);
    }

    protected function getPermission() : string{
        return "mineconomy.cmd";
    }
}
