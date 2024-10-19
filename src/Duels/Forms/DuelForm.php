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
            if ($data === null) {
                return;
            }

            $targetName = $data[0];
            $waitTime = intval($data[1]);

            if (empty($targetName)) {
                $player->sendMessage(TextFormat::RED . "Nama pemain tidak boleh kosong.");
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
            $this->duelManager->getPlugin()->getServer()->getScheduler()->scheduleDelayedTask(new \pocketmine\scheduler\ClosureTask(function () use ($player, $targetName) {
                if (isset($this->duelManager->waitingDuels[$player->getName()])) {
                    unset($this->duelManager->waitingDuels[$player->getName()]);
                    $target = $this->duelManager->getPlugin()->getServer()->getPlayer($targetName);
                    if ($target instanceof Player) {
                        $this->duelManager->startDuel($player, $target, 60); // Default duration
                    } else {
                        $player->sendMessage(TextFormat::RED . "Pemain " . $targetName . " tidak ditemukan.");
                    }
                }
            }), $waitTime * 20); // 20 ticks per second
        });

        $this->duelManager = $duelManager;
    }

    public function jsonSerialize() {
        return [
            "type" => "custom_form",
            "title" => "Tantangan Duel",
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
