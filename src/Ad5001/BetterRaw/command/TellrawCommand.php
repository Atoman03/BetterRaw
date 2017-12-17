<?php
declare(strict_types = 1);
namespace Ad5001\BetterRaw\command;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Ad5001\BetterRaw\Main;

class TellrawCommand extends Command{

    private $main;

    /**
     * Constructs the class
     *
     * @param Main $main
     */
    public function __construct(Main $main){
        $this->main = $main;
        parent::__construct("tellraw", "Send a raw message to a player", "/tellraw <player> <message...>");
        $this->setPermission("betterraw.tellraw");
        $this->setUsage("§c§o[§r§cUsage§o§c]§r§c /tellraw <player> <message...>");
    }
    
    /**
     * When the command is executed
     *
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if(count($args) < 2){
            $this->setUsage("§c§o[§r§cUsage§o§c]§r§c /tellraw <player> <message...>");
            return true;
        }
        // Checking player
        $player = Server::getInstance()->getPlayer($args[0]);
        if($player == null){
            $this->setUsage("§c§o[§r§cBetterRaw§o§c]§r§c Player {$args[0]} not found.");
            return true;
        }
        unset($args[0]);
        // Multiline, colors.
        $msg = str_replace("\\n", "\n", preg_replace("/&([a-f0-9])/", "§$1", implode(" ", $args)));
        // Sending MSG
        $player->sendMessage($msg);
        return true;
    }
    /**
     * Generates custom data for command
     *
     * @param Player $player
     * @return array
     */
    public function generateCustomCommandData(Player $player): array {
        $cmdData = parent::generateCustomCommandData($player);
        $cmdData["permission"] = "betterraw.tellraw";
        $cmdData["aliases"] = [];
        $cmdData["overloads"]["default"]["input"]["parameters"] = [
            0 => [
                "type" => "target",
                "name" => "player",
                "optional" => false
            ],
            1 => [
                "type" => "rawtext",
                "name" => "message...",
                "optional" => false
            ]
        ];
        return $cmdData;
    }
}