<?php
declare(strict_types = 1);
namespace Ad5001\BetterRaw\command;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Ad5001\BetterRaw\Main;

class SendTitleCommand extends Command{

    private $main;

    /**
     * Constructs the class
     *
     * @param Main $main
     */
    public function __construct(Main $main){
        $this->main = $main;
        parent::__construct("sendtitle", "Send a message in big to a player", "/sendtitle <player> <title[\\n <subtitle>]...>");
        $this->setPermission("betterraw.sendtitle");
        $this->setUsage("§c§o[§r§cUsage§o§c]§r§c /sendtitle <player> <title[\\n <subtitle>]...>");
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
            $this->setUsage("§c§o[§r§cUsage§o§c]§r§c /sendtitle <player> <title[\\n <subtitle>]...>");
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
        if(strpos(implode(" ", $args), "\\n")){
            $parts = explode("\\n", implode(" ", $args));
            $title = preg_replace("/&([a-f0-9])/", "§$1", $parts[0]);
            $subtitle = str_replace("\\n", "\n", preg_replace("/&([a-f0-9])/", "§$1", $parts[1]));
        } else {
            $title = preg_replace("/&([a-f0-9])/", "§$1", implode(" ", $args));
            $subtitle = "";
        }
        // Multicolors
        if(preg_match("/§(mc|ga|la|ra)/", $title) || preg_match("/§(mc|ga|la|ra)/", $subtitle)) {
            $this->main->multiTask->titles[] = [
                $player->getName(),
                30,
                $title,
                $subtitle
            ];
        } else {
            // Sending MSG
            $player->addTitle($title, $subtitle, -1, round((strlen($title) + strlen($subtitle)) / 3));
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
        $cmdData["permission"] = "betterraw.sendtitle";
        $cmdData["aliases"] = [];
        $cmdData["overloads"]["default"]["input"]["parameters"] = [
            0 => [
                "type" => "target",
                "name" => "player",
                "optional" => false
            ],
            1 => [
                "type" => "rawtext",
                "name" => "title[\\n <subtitle>]...",
                "optional" => false
            ]
        ];
        return $cmdData;
    }
}