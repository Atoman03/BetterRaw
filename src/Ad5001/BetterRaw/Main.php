<?php
namespace Ad5001\BetterRaw;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use Ad5001\BetterRaw\tasks\MultiColorTask;
use Ad5001\BetterRaw\command\SendActionCommand;
use Ad5001\BetterRaw\command\SendPopupCommand;
use Ad5001\BetterRaw\command\SendTipCommand;
use Ad5001\BetterRaw\command\SendTitleCommand;
use Ad5001\BetterRaw\command\TellrawCommand;



class Main extends PluginBase implements Listener{

    public $multiTask;

    /**
     * Called when the plugin gets enabled
     *
     * @return void
     */
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->multiTask = new MultiColorTask($this);
        if($this->getConfig()->get("signs_activated")) $this->getServer()->getScheduler()->scheduleRepeatingTask($this->multiTask, $this->getConfig()->get("signs_change_speed") * 20);
        $this->getServer()->getCommandMap()->register("tellraw", new TellrawCommand($this));
        $this->getServer()->getCommandMap()->register("sendtip", new SendTipCommand($this));
        $this->getServer()->getCommandMap()->register("sendpopup", new SendPopupCommand($this));
        $this->getServer()->getCommandMap()->register("sendaction", new SendActionCommand($this));
        $this->getServer()->getCommandMap()->register("sendtitle", new SendTitleCommand($this));
    }
}