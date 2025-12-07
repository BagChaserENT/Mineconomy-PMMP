<?php

declare(strict_types=1);

namespace bagchaser\mineconomy\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use bagchaser\mineconomy\api\DataBase;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;

class SetMoneyCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission($this->getPermission());
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->registerArgument(1, new IntegerArgument("amount"));
    }

    public function unRun(CommandSender $sender, string $aliasUsed, array $args) : void{
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
        $sql->setMoney($args["player"], $args["amount"]);
        $format = $sql->format($args["amount"]);
        $sender->sendMessage("Set " . $args["player"] . " balance to " . $format);
    }

    protected function getPermission() : string{
        return "mineconomy.op";
    }
}
