<?php

namespace Duels;

use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\ClosureTask;
use pocketmine\form\Form;
use pocketmine\form\CustomForm;

class DuelManager {
    private Main $plugin;
    private array $duels = [];
    private array $waitingDuels = [];

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function showDuelForm(Player $player): void {
        $form = new CustomForm(function (Player $player, array $data = null) {
            if ($data === null) return;

            // Ambil data dari formulir
            $targetName = $data[1];
            $waitTime = (int)$data[2];

            // Proses tantangan duel
            $target = $this->plugin->getServer()->getPlayer($targetName);
            if ($target === null) {
                $player->sendMessage(TextFormat::RED . "Pemain tidak ditemukan.");
                return;
            }

            if ($target === $player) {
                $player->sendMessage(TextFormat::RED . "Anda tidak bisa menantang diri sendiri.");
                return;
            }

            if (isset($this->waitingDuels[$player->getName()])) {
                $player->sendMessage(TextFormat::RED . "Anda sedang menunggu untuk duel.");
                return;
            }

            // Simpan tantangan
            $this->waitingDuels[$player->getName()] = $target->getName();
            $player->sendMessage(TextFormat::GREEN . "Anda telah menantang " . $target->getName() . " untuk berduel dalam waktu " . $waitTime . " detik.");
            $target->sendMessage(TextFormat::YELLOW . $player->getName() . " telah menantang Anda untuk berduel! Ketik /accept untuk menerima.");

            // Jadwalkan mulai duel setelah waktu tunggu
            $this->plugin->getServer()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($player, $target) {
                if (isset($this->waitingDuels[$player->getName()])) {
                    unset($this->waitingDuels[$player->getName()]);
                    $this->startDuel($player, $target);
                }
            }), $waitTime * 20); // 20 ticks per second
        });

        $form->setTitle("Duel Challenge");
        $form->addLabel("Pilih pemain untuk ditantang dan waktu tunggu:");
        $form->addInput("Masukkan nama pemain:", "PlayerName");
        $form->addInput("Masukkan waktu dalam detik:", "5");

        $player->sendForm($form);
    }

    public function startDuel(Player $player1, Player $player2): void {
        $duel = new Duel($player1, $player2, 0, 60); // 60 seconds duration
        $this->duels[$player1->getName()] = $duel;
        $this->duels[$player2->getName()] = $duel;

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
            $challenger->sendMessage(TextFormat::RED . $sender->getName() . " telah menolak tantangan duel.");
        }
        $sender->sendMessage(TextFormat::GREEN . "Anda telah menolak tantangan duel dari " . $challengerName . ".");
        return true;
    }
}
