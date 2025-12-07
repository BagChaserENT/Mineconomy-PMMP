<?php

declare(strict_types=1);

namespace bagchaser\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use bagchaser\mineconomy\api\DataBase;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;

class RemoveMoneyCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission($this->getPermission());
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->registerArgument(1, new IntegerArgument("amount"));
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
        if($args["amount"] < 0){
            $sender->sendMessage("Amount must be positive!");
            return;
        }
        if($sql->getBalance($args["player"]) < $args["amount"]){
            $sender->sendMessage("Player does not have enough money!");
            return;
        }
        $format = $sql->format($args["amount"]);
        $sql->removeMoney($args["player"], $args["amount"]);
        $sender->sendMessage("Removed " . $format . " from " . $args["player"] . " balance!");
    }

    public function getPermission() : string{
        return "mineconomy.op";
    }
}
