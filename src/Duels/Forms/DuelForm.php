<?php

namespace Duels\Forms;

use pocketmine\Player;
use pocketmine\form\CustomForm;
use pocketmine\utils\TextFormat;
use Duels\DuelManager;

class DuelForm extends CustomForm {

    private DuelManager $duelManager;

    public function __construct(DuelManager $duelManager) {
        parent::__construct(function (Player $player, array $data = null) {
            if ($data === null) return;

            // Ambil data dari formulir
            $targetName = $data[1];
            $waitTime = (int)$data[2];

            // Proses tantangan duel
            $target = $this->duelManager->getPlugin()->getServer()->getPlayer($targetName);
            if ($target === null) {
                $player->sendMessage(TextFormat::RED . "Pemain tidak ditemukan.");
                return;
            }

            if ($waitTime <= 0) {
                $player->sendMessage(TextFormat::RED . "Waktu tunggu tidak valid.");
                return;
            }

            // Simpan tantangan duel
            $this->duelManager->waitingDuels[$player->getName()] = $targetName;
            $player->sendMessage(TextFormat::GREEN . "Tantangan duel terkirim ke " . $targetName . ". Waktu tunggu: " . $waitTime . " detik.");

            // Mulai duel setelah waktu tunggu
            $this->duelManager->getPlugin()->getServer()->getScheduler()->scheduleDelayedTask(new \pocketmine\scheduler\ClosureTask(function () use ($player, $target) {
                if (isset($this->duelManager->waitingDuels[$player->getName()])) {
                    unset($this->duelManager->waitingDuels[$player->getName()]);
                    $this->duelManager->startDuel($player, $target);
                }
            }), $waitTime * 20); // 20 ticks per second
        });

        $this->duelManager = $duelManager;
    }

    public function jsonSerialize() {
        return [
            "type" => "custom_form",
            "title" => "Duel Challenge",
            "content" => [
                [
                    "type" => "label",
                    "text" => "Pilih pemain untuk ditantang dan waktu tunggu:"
                ],
                [
                    "type" => "input",
                    "text" => "Masukkan nama pemain:",
                    "placeholder" => "PlayerName"
                ],
                [
                    "type" => "input",
                    "text" => "Masukkan waktu dalam detik:",
                    "placeholder" => "5"
                ]
            ]
        ];
    }
}
