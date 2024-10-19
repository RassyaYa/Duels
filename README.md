# Duels
Tantangan Duel:

Pemain dapat menggunakan perintah /duel <nama_pemain> <waktu_dalam_detik> untuk menantang pemain lain.
Setelah perintah diinput, formulir akan ditampilkan kepada pemain untuk memilih target dan waktu tunggu.
Antarmuka Formulir:

Plugin menggunakan CustomForm untuk menampilkan UI yang ramah pengguna.
Pemain memasukkan nama pemain yang ingin ditantang dan waktu tunggu sebelum duel dimulai.
Penerimaan Tantangan:

Pemain yang menerima tantangan akan mendapatkan pesan yang memberi tahu mereka tentang tantangan tersebut.
Mereka dapat menggunakan perintah /accept untuk menerima tantangan dan memulai duel.
Armor dan Senjata:

Ketika duel dimulai, pemain diberikan armor set penuh (diamond) dan senjata (diamond sword).
Ini memastikan bahwa duel berlangsung dengan adil dan seimbang.
Reset Inventaris:

Sebelum duel dimulai, inventaris pemain direset untuk memastikan bahwa mereka hanya memiliki item yang diberikan oleh plugin.
Durasi Duel:

Duel berlangsung selama waktu yang telah ditentukan (misalnya, 60 detik). Setelah waktu habis, duel akan berakhir dan pemain akan dipulangkan ke lokasi spawn.
Pesan Notifikasi:

Pemain menerima notifikasi mengenai status duel, seperti penerimaan atau penolakan tantangan.
Struktur Kode
Main.php: Kelas utama plugin yang mengatur inisialisasi plugin dan perintah.
DuelManager.php: Mengelola logika tantangan duel, termasuk menyimpan tantangan dan menangani penerimaan atau penolakan.
Duel.php: Mengatur logika duel, termasuk reset inventaris, pemberian armor dan senjata, serta pengaturan lokasi duel.
plugin.yml: File konfigurasi yang mendefinisikan informasi dasar plugin, termasuk nama, versi, penulis, dan perintah yang tersedia.
Cara Menggunakan Plugin
Instalasi:

Salin semua file ke dalam folder plugin bernama Duels.
Pastikan server PocketMine-MP Anda berjalan dan plugin terpasang dengan benar.
Menggunakan Perintah:

Pemain dapat mengetik /duel untuk membuka formulir tantangan.
Masukkan nama pemain yang ingin ditantang dan waktu tunggu.
Pemain yang ditantang akan menerima notifikasi dan dapat menerima atau menolak tantangan menggunakan perintah /accept atau /decline.
Duel Dimulai:

Setelah tantangan diterima, pemain akan dipindahkan ke lokasi duel dengan armor dan senjata yang telah disiapkan.
Duel berakhir setelah waktu yang ditentukan habis.
Penutup
Plugin Duels ini memberikan cara yang menyenangkan dan interaktif untuk berduel di Minecraft, meningkatkan pengalaman bermain bagi pemain yang menyukai kompetisi. Jika Anda ingin menambahkan lebih banyak fitur atau memiliki pertanyaan lebih lanjut tentang plugin ini, silakan beri tahu saya!
