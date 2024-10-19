<?php

namespace Duels;

use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Duels\Forms\DuelForm;

class DuelManager {
    private Main $plugin;
    public array $waitingDuels = [];

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function showDuelForm(Player $player): void {
        $form = new DuelForm($this);
        $player->sendForm($form);
    }

    public function startDuel(Player $player1, Player $player2): void {
        $duel = new Duel($player1, $player2, 60); // 60 seconds duration
        $duel->start();
    }

    public function handleAcceptCommand(Player $sender): bool {
        if (!isset($this->waitingDuels[$sender->getName()])) {
            $sender->sendMessage(TextFormat::RED . "Anda tidak memiliki tantangan duel yang aktif.");
            return true;
        }

        $challengerName = $this->waitingDuels[$sender->getName()];
        unset($this->waitingDuels[$sender->getName()]);

        $challenger = $this->plugin->getServer()->getPlayer($challengerName);
        if ($challenger === null) {
            $sender->sendMessage(TextFormat::RED . "Tantangan tidak valid.");
            return true;
        }

        $this->startDuel($challenger, $sender);
        return true;
    }

    public function handleDeclineCommand(Player $sender): bool {
        if (!isset($this->waitingDuels[$sender->getName()])) {
            $sender->sendMessage(TextFormat::RED . "Anda tidak memiliki tantangan duel yang aktif.");
            return true;
        }

        $challengerName = $this->waitingDuels[$sender->getName()];
        unset($this->waitingDuels[$sender->getName()]);

        $challenger = $this->plugin->getServer()->getPlayer($challengerName);
        if ($challenger !== null) {
            $challenger->sendMessage(TextFormat::RED . $sender->getName() . " menolak tantangan Anda.");
        }

        $sender->sendMessage(TextFormat::GREEN . "Anda telah menolak tantangan duel.");
        return true;
    }
}
