<?php

namespace Duels;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\level\Position;

class Duel {
    private Player $player1;
    private Player $player2;
    private int $waitTime;
    private int $duration;
    private bool $isActive = false;

    public function __construct(Player $player1, Player $player2, int $waitTime, int $duration) {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->waitTime = $waitTime;
        $this->duration = $duration;
    }

    public function start(): void {
        $this->isActive = true;

        // Reset inventory
        $this->resetInventory($this->player1);
        $this->resetInventory($this->player2);

        // Give armor and weapons
        $this->giveArmor($this->player1);
        $this->giveArmor($this->player2);
        $this->giveWeapons($this->player1);
        $this->giveWeapons($this->player2);

        // Teleport players to the duel location
        $duelLocation = new Position(100, 64, 100, $this->player1->getLevel());
        $this->player1->teleport($duelLocation);
        $this->player2->teleport($duelLocation);

        // Notify players
        $this->player1->sendMessage(TextFormat::GREEN . "Duel dimulai dengan " . $this->player2->getName() . "!");
        $this->player2->sendMessage(TextFormat::GREEN . "Duel dimulai dengan " . $this->player1->getName() . "!");

        // Schedule end of duel
        $this->scheduleDuelEnd();
    }

    private function resetInventory(Player $player): void {
        $player->getInventory()->clearAll();
    }

    private function giveArmor(Player $player): void {
        // Give armor to the player
        $player->getArmorInventory()->setHelmet(Item::get(Item::DIAMOND_HELMET));
        $player->getArmorInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
        $player->getArmorInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
        $player->getArmorInventory()->setBoots(Item::get(Item::DIAMOND_BOOTS));
    }

    private function giveWeapons(Player $player): void {
        // Give weapons to the player
        $player->getInventory()->addItem(Item::get(Item::DIAMOND_SWORD));
        $player->getInventory()->addItem(Item::get(Item::HEALING_POTION, 0, 5)); // Give 5 healing potions
    }

    private function scheduleDuelEnd(): void {
        $this->player1->getServer()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () {
            $this->end();
        }), $this->duration * 20); // 20 ticks = 1 detik
    }

    public function end(): void {
        if (!$this->isActive) return;

        $this->isActive = false;
        $this->player1->sendMessage(TextFormat::RED . "Duel Anda telah berakhir!");
        $this->player2->sendMessage(TextFormat::RED . "Duel Anda telah berakhir!");

        // Teleport back to spawn
        $this->player1->teleport($this->player1->getServer()->getDefaultLevel()->getSafeSpawn());
        $this->player2->teleport($this->player2->getServer()->getDefaultLevel()->getSafeSpawn());
    }

    public function isActive(): bool {
        return $this->isActive;
    }
}
