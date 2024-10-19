namespace Duels;

use pocketmine\Player;
use pocketmine\Server;

class DuelManager {
    private array $challenges = [];
    private Main $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function handleDuelCommand(CommandSender $sender, array $args): bool {
        if (!$sender instanceof Player) {
            $this->plugin->getLogger()->info("Perintah ini hanya dapat dijalankan oleh pemain!");
            return true;
        }

        if (count($args) < 2) {
            $this->plugin->getLogger()->info("Gunakan: /duel <nama_pemain> <waktu_dalam_detik>");
            return true;
        }

        $targetName = $args[0];
        $duration = (int)$args[1];
        $target = $this->plugin->getServer()->getPlayerExact($targetName);

        if ($target === null || $target === $sender) {
            $this->plugin->getLogger()->info("Pemain tidak ditemukan atau Anda tidak dapat menantang diri sendiri!");
            return true;
        }

        // Mengatur tantangan duel
        $this->challenges[$sender->getName()] = $target->getName();
        $sender->sendMessage("Tantangan duel telah dikirim ke " . $target->getName());
        $target->sendMessage($sender->getName() . " menantang Anda untuk duel! Gunakan /accept atau /decline.");

        return true;
    }

    public function handleAcceptCommand(CommandSender $sender): bool {
        if (!$sender instanceof Player) {
            $this->plugin->getLogger()->info("Perintah ini hanya dapat dijalankan oleh pemain!");
            return true;
        }

        $challengerName = array_search($sender->getName(), $this->challenges);

        if ($challengerName === false) {
            $this->plugin->getLogger()->info("Anda tidak memiliki tantangan duel yang aktif!");
            return true;
        }

        // Menghapus tantangan dan memulai duel
        unset($this->challenges[$challengerName]);
        $challenger = $this->plugin->getServer()->getPlayerExact($challengerName);

        if ($challenger instanceof Player) {
            $duel = new Duel($challenger, $sender);
            $duel->start();
        }

        return true;
    }

    public function handleDeclineCommand(CommandSender $sender): bool {
        if (!$sender instanceof Player) {
            $this->plugin->getLogger()->info("Perintah ini hanya dapat dijalankan oleh pemain!");
            return true;
        }

        $challengerName = array_search($sender->getName(), $this->challenges);

        if ($challengerName === false) {
            $this->plugin->getLogger()->info("Anda tidak memiliki tantangan duel yang aktif!");
            return true;
        }

        unset($this->challenges[$challengerName]);
        $challenger = $this->plugin->getServer()->getPlayerExact($challengerName);

        if ($challenger instanceof Player) {
            $challenger->sendMessage($sender->getName() . " telah menolak tantangan duel.");
        }

        return true;
    }
}
