namespace Duels;

use pocketmine\Player;

class Duel {
    private Player $player1;
    private Player $player2;
    private int $duration;

    public function __construct(Player $player1, Player $player2, int $duration = 60) {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->duration = $duration;
    }

    public function start(): void {
        $this->preparePlayer($this->player1);
        $this->preparePlayer($this->player2);
        
        $this->player1->sendMessage("Duel dimulai dengan " . $this->player2->getName() . " selama " . $this->duration . " detik!");
        $this->player2->sendMessage("Duel dimulai dengan " . $this->player1->getName() . " selama " . $this->duration . " detik!");
        
        // Logik duel yang sebenarnya akan ditempatkan di sini
    }

    private function preparePlayer(Player $player): void {
        $inventory = $player->getInventory();
        $inventory->clearAll();
        
        // Memberikan senjata dan armor
        $inventory->addItem(\pocketmine\item\ItemFactory::getInstance()->get(276, 0, 1)); // Diamond Sword
        $inventory->addItem(\pocketmine\item\ItemFactory::getInstance()->get(310, 0, 1)); // Diamond Helmet
        $inventory->addItem(\pocketmine\item\ItemFactory::getInstance()->get(311, 0, 1)); // Diamond Chestplate
        $inventory->addItem(\pocketmine\item\ItemFactory::getInstance()->get(312, 0, 1)); // Diamond Leggings
        $inventory->addItem(\pocketmine\item\ItemFactory::getInstance()->get(313, 0, 1)); // Diamond Boots
    }
}
