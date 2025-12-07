<?php

declare(strict_types=1);

namespace bagchaser\mineconomy\api;

use SQLite3;

use pocketmine\player\Player;

use pocketmine\utils\SingletonTrait;

use bagchaser\mineconomy\Core;

class DataBase {
    use SingletonTrait;

    protected SQLite3 $sql;

    public function __construct(){
        $this->sql = new SQLite3(Core::getInstance()->getDataFolder() . "data.db");
        $this->sql->exec("CREATE TABLE IF NOT EXISTS money (player TEXT PRIMARY KEY, money INTEGER);");
    }

    public function isNew($player) : bool{
        $player = $player instanceof Player ? $player->getName() : $player;
        $query = $this->sql->query("SELECT * FROM money WHERE player = '$player';");
        $result = $query->fetchArray(SQLITE3_ASSOC);
        return $result === false;
    }

    public function create($player) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $this->sql->exec("INSERT INTO money (player, money) VALUES ('$player', 1000);");
    }

    public function getBalance($player) : int{
        $player = $player instanceof Player ? $player->getName() : $player;
        $query = $this->sql->query("SELECT money FROM money WHERE player = '$player';");
        $result = $query->fetchArray(SQLITE3_ASSOC);
        return $result["money"];
    }

    public function addMoney($player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $this->sql->exec("UPDATE money SET money = money + $amount WHERE player = '$player';");
    }

    public function removeMoney($player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $this->sql->exec("UPDATE money SET money = money - $amount WHERE player = '$player';");
    }

    public function setMoney($player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $this->sql->exec("UPDATE money SET money = $amount WHERE player = '$player';");
    }

    public function format(int $amount) : string{
        $formatted = number_format($amount);
        return "$" . $formatted;
    }

    public function close() : void{
        $this->sql->close();
    }
}