<?php

namespace Duels;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {

    private DuelManager $duelManager;

    public function onEnable(): void {
        $this->duelManager = new DuelManager($this);
        $this->getServer()->getPluginManager()->registerEvents($this->duelManager, $this);
        $this->getLogger()->info(TextFormat::GREEN . "Duels plugin enabled!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        switch ($command->getName()) {
            case "duel":
                if ($sender instanceof Player) {
                    $this->duelManager->showDuelForm($sender);
                } else {
                    $sender->sendMessage(TextFormat::RED . "Perintah ini hanya dapat digunakan oleh pemain dalam permainan.");
                }
                return true;

            case "accept":
                if ($sender instanceof Player) {
                    return $this->duelManager->handleAcceptCommand($sender);
                }
                return false;

            case "decline":
                if ($sender instanceof Player) {
                    return $this->duelManager->handleDeclineCommand($sender);
                }
                return false;
        }
        return false;
    }

    public function getDuelManager(): DuelManager {
        return $this->duelManager;
    }
}
