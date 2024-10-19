namespace Duels\Forms;

use pocketmine\Player;
use pocketmine\form\CustomForm;
use pocketmine\form\Form;
use Duels\DuelManager;

class DuelForm extends CustomForm {
    private Player $player;

    public function __construct(Player $player) {
        parent::__construct("Duel Form");
        $this->player = $player;

        // Tambahkan elemen form
        $this->addLabel("Tantang pemain lain untuk berduel!");
        $this->addInput("Nama Pemain:", "Masukkan nama pemain...");
        $this->addInput("Durasi (detik):", "Masukkan durasi...");
    }

    public function handleResponse(Player $player, array $data): void {
        $targetName = $data[0];
        $duration = (int)$data[1];

        // Menggunakan DuelManager untuk menangani tantangan
        $duelManager = $this->player->getServer()->getPluginManager()->getPlugin("Duels")->getDuelManager();
        $duelManager->handleDuelCommand($player, [$targetName, $duration]);
    }
}
