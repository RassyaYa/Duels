<?php

namespace Duels;

use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Duel {
    private Player $player1;
    private Player $player2;
    private int $duration;

    public function __construct(Player $player1, Player $player2, int $duration) {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->duration = $duration;
    }

    public function start(): void {
        // Reset inventory
        $this->preparePlayer($this->player1);
        $this->preparePlayer($this->player2);
        
        // Inform players
        $this->player1->sendMessage(TextFormat::GREEN . "Duel dimulai melawan " . $this->player2->getName());
        $this->player2->sendMessage(TextFormat::GREEN . "Duel dimulai melawan " . $this->player1->getName());

        // Schedule ending the duel
        $this->player1->getServer()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() {
            $this->endDuel();
        }), $this->duration * 20); // 20 ticks per second
    }

    private function preparePlayer(Player $player): void {
        // Reset inventory
        $player->getInventory()->clearAll();
        
        // Give items (armor and weapons)
        $player->getInventory()->addItem(\pocketmine\item\ItemFactory::getInstance()->get(276, 0, 1)); // Diamond Sword
        // Give diamond armor
        $player->getInventory()->setArmorContents([
            \pocketmine\item\ItemFactory::getInstance()->get(311, 0, 1), // Diamond Helmet
            \pocketmine\item\ItemFactory::getInstance()->get(307, 0, 1), // Diamond Chestplate
            \pocketmine\item\ItemFactory::getInstance()->get(308, 0, 1), // Diamond Leggings
            \pocketmine\item\ItemFactory::getInstance()->get(309, 0, 1)  // Diamond Boots
        ]);
    }

    private function endDuel(): void {
        // Logic to handle duel ending
        // Notify players that the duel has ended
        $this->player1->sendMessage(TextFormat::YELLOW . "Duel berakhir!");
        $this->player2->sendMessage(TextFormat::YELLOW . "Duel berakhir!");
        
        // Clear inventory or return players to original state if necessary
    }
}
