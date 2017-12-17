<?php
/**
 * Multi color task
 * Replaces signs.
 * 
 * Open replacements:
 * §mc: Multicolor
 * §gs: Gray scale
 * §la: Left changing arrows
 * §ra: Right changing arrows
 * 
 * Internal replacements
 * §m: Multicolor
 * §g: Gray scale normal order
 * §h: Gray scale back order
 * §t: Right arrows
 * §j: Left arrows
 */
declare(strict_types = 1);
namespace Ad5001\BetterRaw\tasks;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\tile\Sign;
use pocketmine\scheduler\PluginTask;

use Ad5001\BetterRaw\Main;



class MultiColorTask extends PluginTask {

    /**
     * Order of appearence of the colors. 
     * To be not to blinky
     */
    const COLORS_IN_ORDER = [
        "5" => "4",
        "4" => "c",
        "c" => "e",
        "e" => "6",
        "6" => "2",
        "2" => "a",
        "a" => "b",
        "b" => "3",
        "3" => "1",
        "1" => "0",
        "0" => "8",
        "8" => "f",
        "f" => "9",
        "9" => "d",
        "d" => "5"
    ];

    /**
     * Order of grayscale
     */
    const GRAY_SCALE_IN_ORDER = [
        "0" => "f",
        "f" => "7",
        "7" => "8",
        "8" => "0"
    ];

    /**
     *  Backwards Order of grayscale
     */
    const GRAY_SCALE_IN_BACKORDER = [
        "0" => "8",
        "8" => "7",
        "7" => "f",
        "f" => "0",
    ];

    /**
     * Arrows that points from left
     */
    const LEFT_ARROWS = [
        ">>>" => ">",
        ">" => ">>",
        ">>" => ">>>"
    ];

    /**
     * Arrows that points from left
     */
    const RIGHT_ARROWS = [
        "<<<" => "<",
        "<" => "<<",
        "<<" => "<<<"
    ];

    /**
     * All theses arrays will store some data about repeating arrays.
     * Format: [
     *      $playerName,
     *      $timesToReapeat
     *      $message
     * ]
     *
     * @var array
     */
    public $popups = [];
    public $titles = [];
    public $tips = [];
    public $actions = [];



    /**
     * Constructs the class
     *
     * @param Main $main
     */
    public function __construct(Main $main) {
        parent::__construct($main);
        $this->main = $main;
    }

    /**
     * When the tasks ticks
     *
     * @param int $tick
     * @return void
     */
    public function onRun(int $tick) {
        foreach(Server::getInstance()->getLevels() as $level){
            foreach($level->getTiles() as $tile){
                if($tile instanceof Sign){
                    $texts = $tile->getText();
                    foreach($texts as $i => $text){
                        $texts[$i] = $this->checkForArrows($this->checkForGrayScale($this->checkForMultiColor($text)));
                    }
                    $tile->setText($texts[0], $texts[1], $texts[2], $texts[3]);
                }
            }
        }
        // Popups
        foreach($this->popups as $i => $pop){
            if($pop[1] == 0 || Server::getInstance()->getPlayer($pop[0]) == null){
                unset($this->popups[$i]);
            } else {
                $this->popups[$i][1]--;
                $this->popups[$i][2] = $this->checkForArrows($this->checkForGrayScale($this->checkForMultiColor($this->popups[$i][2])));
                Server::getInstance()->getPlayer($pop[0])->sendPopup($this->popups[$i][2]);
            }
        }
        // Titles (3 is the subtitle)
        foreach($this->titles as $i => $title){
            if($title[1] == 0 || Server::getInstance()->getPlayer($title[0]) == null){
                unset($this->titles[$i]);
            } else {
                $this->titles[$i][1]--;
                $this->titles[$i][2] = $this->checkForArrows($this->checkForGrayScale($this->checkForMultiColor($this->titles[$i][2])));
                $this->titles[$i][3] = $this->checkForArrows($this->checkForGrayScale($this->checkForMultiColor($this->titles[$i][3])));
                Server::getInstance()->getPlayer($title[0])->addTitle($this->titles[$i][2], $this->titles[$i][3], 0, (int) round((strlen($this->titles[$i][2]) + strlen($this->titles[$i][3])) / 3), 0);
            }
        }
        // Tips
        foreach($this->tips as $i => $tip){
            if($tip[1] == 0 || Server::getInstance()->getPlayer($tip[0]) == null){
                unset($this->tips[$i]);
            } else {
                $this->tips[$i][1]--;
                $this->tips[$i][2] = $this->checkForArrows($this->checkForGrayScale($this->checkForMultiColor($this->tips[$i][2])));
                Server::getInstance()->getPlayer($tip[0])->sendPopup($this->tips[$i][2]);
            }
        }
        // Actions
        foreach($this->actions as $i => $act){
            if($act[1] == 0 || Server::getInstance()->getPlayer($act[0]) == null){
                unset($this->actions[$i]);
            } else {
                $this->actions[$i][1]--;
                $this->actions[$i][2] = $this->checkForArrows($this->checkForGrayScale($this->checkForMultiColor($this->actions[$i][2])));
                Server::getInstance()->getPlayer($act[0])->sendPopup($this->actions[$i][2]);
            }
        }
    }

    /**
     * Checks text for multicolor and replaces it
     *
     * @param string $text
     * @return string
     */
    function checkForMultiColor(string $text): string{
        if(preg_match("/§m§[\da-f]/", $text)){
            $text = preg_replace_callback("/§m§([\da-f])/", function($matches){
                return "§m§" . self::COLORS_IN_ORDER[$matches[1]];
            }, $text);
        }

        if(preg_match("/§mc/", $text)){
            $text = str_replace("§mc", "§m§4", $text);
        }
        return $text;
    }

    /**
     * Checks for grayscale color replacement
     *
     * @param string $text
     * @return string
     */
    function checkForGrayScale(string $text): string{
        if(preg_match("/§g§[\da-f]/", $text)){
            $text = preg_replace_callback("/§g§([\da-f])/", function($matches){
                $pref = "§g§";
                if($matches[1] == "0") {
                    $pref = "§h§";
                    return $pref . self::GRAY_SCALE_IN_BACKORDER[$matches[1]];
                } else {
                    return $pref . self::GRAY_SCALE_IN_ORDER[$matches[1]];
                }
            }, $text);
        } 
        // Backorder
        if(preg_match("/§h§[\da-f]/", $text)){
            $text = preg_replace_callback("/§h§([\da-f])/", function($matches){
                $pref = "§h§";
                if($matches[1] == "f") {
                    $pref = "§g§";
                    return $pref . self::GRAY_SCALE_IN_ORDER[$matches[1]];
                } else {
                    return $pref . self::GRAY_SCALE_IN_BACKORDER[$matches[1]];
                }
            }, $text);
        } 

        if(preg_match("/§gs/", $text)){
            $text = str_replace("§gs", "§g§f", $text);
        }
        return $text;
    }

    /**
     * Checks for replacement arrows and replace them
     *
     * @param string $text
     * @return string
     */
    function checkForArrows(string $text): string {
        if(preg_match("/§j(>{1,3})/", $text)){
            $text = preg_replace_callback("/§j(>{1,3})/", function($matches){
                return "§j" . self::LEFT_ARROWS[$matches[1]];
            }, $text);
        }
        if(preg_match("/§t(<{1,3})/", $text)){
            $text = preg_replace_callback("/§t(<{1,3})/", function($matches){
                return "§t" . self::RIGHT_ARROWS[$matches[1]];
            }, $text);
        }

        if(preg_match("/§la/", $text)){
            $text = str_replace("§la", "§j>", $text);
        }
        if(preg_match("/§ra/", $text)){
            $text = str_replace("§ra", "§t<", $text);
        }
        return $text;
    }


}