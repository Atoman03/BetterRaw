<?php
declare(strict_types = 1);
namespace Ad5001\BetterRaw\command;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Ad5001\BetterRaw\Main;

class SendTipCommand extends Command{

    private $main;

    /**
     * Constructs the class
     *
     * @param Main $main
     */
    public function __construct(Main $main){
        $this->main = $main;
        parent::__construct("sendtip", "Send a tip to a player", "/sendtip <player> <message...>");
        $this->setPermission("betterraw.sendtip");
        $this->setUsage("§c§o[§r§cUsage§o§c]§r§c /sendtip <player> <message...>");
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
            $this->setUsage("§c§o[§r§cUsage§o§c]§r§c /sendtip <player> <message...>");
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
        // Multicolors
        if(preg_match("/§(mc|ga|la|ra)/", $msg)) {
            $this->main->multiTask->tips[] = [
                $player->getName(),
                30,
                $msg
            ];
        } else {
            // Sending MSG
            $player->sendTip($msg);
        }
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
        $cmdData["permission"] = "betterraw.sendtip";
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