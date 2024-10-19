namespace Duels;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Main extends PluginBase {
    private DuelManager $duelManager;

    protected function onEnable(): void {
        $this->duelManager = new DuelManager($this);
    }

    public function getDuelManager(): DuelManager {
        return $this->duelManager;
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        switch ($command->getName()) {
            case "duel":
                // Logika untuk perintah duel
                return $this->duelManager->handleDuelCommand($sender, $args);
            case "accept":
                return $this->duelManager->handleAcceptCommand($sender);
            case "decline":
                return $this->duelManager->handleDeclineCommand($sender);
            default:
                return false;
        }
    }
}
