-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Des 2025 pada 11.08
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suandy_store`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `listings`
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `game` varchar(100) DEFAULT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'active',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `listings`
--

INSERT INTO `listings` (`id`, `title`, `game`, `description`, `price`, `status`, `image`, `created_at`) VALUES
(1, 'TH 14 ALL MAX', 'Clash of Clans', 'Spesifikasi Utama:\r\n\r\nTown Hall: Level 14 (Giga Inferno Bintang 3)\r\n\r\nLevel XP: 160 - 170\r\n\r\nBuilder: 5 Tukang\r\n\r\nGems: Sisa sedikit (di bawah 500)\r\n\r\nGanti Nama (CN): Off / Tidak Bisa (Harus pakai Gems sendiri)\r\n\r\nLiga Saat Ini: Crystal / Master\r\n\r\nKondisi Hero & Pet (Spek Menengah):\r\n\r\nBarbarian King: Level 50-55 (Masih perlu upgrade)\r\n\r\nArcher Queen: Level 60-65 (Lumayan)\r\n\r\nGrand Warden: Level 35-40\r\n\r\nRoyal Champion: Level 15-20\r\n\r\nPet House: Sudah dibangun (Terbuka L.A.S.S & Electro Owl level rendah)\r\n\r\nPertahanan (Defense):\r\n\r\nScattershot: Level 1 (Sudah terpasang 2 buah)\r\n\r\nEagle Artillery: Level Max TH 12\r\n\r\nInferno & X-Bow: Level Campur (Sebagian TH 13, sebagian TH 12)\r\n\r\nPagar / Wall: Belang-belang (Dominan level 11/12, belum rata ungu/hijau)\r\n\r\nPasukan (Troops & Spell):\r\n\r\nTroop Farming: Goblin Licik / Super Goblin (Perlu boost)\r\n\r\nTroop War: Electro Dragon & Balon Max (Sudah kuat untuk ratain TH 13)\r\n\r\nSiege Machine: Log Launcher & Flame Flinger terbuka (Level 1)\r\n\r\nSpell: Rage & Freeze Max, sisanya standar', 255000.00, 'active', 'uploads/img_694bdc3f767bb3.54518779.jpg', '2025-12-24 12:27:43'),
(6, 'TH 13 MAX HERO', 'Clash of Clans', 'Spesifikasi Umum:\r\n\r\nTown Hall: Level 13 (Giga Inferno Level 3)\r\n\r\nBuilder: 5 Tukang\r\n\r\nLevel XP: 145 - 155\r\n\r\nGems: 200 - 500 (Random)\r\n\r\nGanti Nama (CN): Off / Tidak Bisa (Bayar 500 Gems)\r\n\r\nSkin Hero: Ada 1-2 skin dari Gold Pass lama (Biasanya skin Gladiator/PEKKA King)\r\n\r\nKondisi Pahlawan (Heroes): Total Level Hero lumayan, tidak terlalu rendah:\r\n\r\nBarbarian King: Level 55 - 60 (Max TH 13 itu 75)\r\n\r\nArcher Queen: Level 65 - 70 (Sudah sakit buat Queen Walk)\r\n\r\nGrand Warden: Level 35 - 40 (Ability Eternal Tome sudah lama)\r\n\r\nRoyal Champion: Level 5 - 10 (Masih rendah, baru buka)\r\n\r\nPertahanan (Defense):\r\n\r\nScattershot: Sudah terpasang 2 unit (Level 1)\r\n\r\nEagle Artillery: Level Max TH 12\r\n\r\nInferno Tower: Level Campur (Multi & Single)\r\n\r\nX-Bow: Level Max TH 12\r\n\r\nPagar / Wall: Dominan Level 12 (Biru) dan sebagian Level 13 (Biru Tua/Kayu). Tidak ada pagar level kecil (pink/ungu).\r\n\r\nPasukan & Spell (Laboratorium):\r\n\r\nTroop War Ready: Electro Dragon Max TH 13, Balon Max, Yeti Level 2.\r\n\r\nTroop Farming: Baby Dragon & Miner level tinggi.\r\n\r\nSiege Machine: Siege Barracks & Log Launcher terbuka.\r\n\r\nSpell: Rage, Heal, Freeze Max.', 185000.00, 'active', NULL, '2025-12-25 01:45:04'),
(7, 'TH 15 SEMI MAX', 'Clash of Clans', 'Spesifikasi Utama:\r\n\r\nTown Hall: Level 15 (Giga Inferno sudah aktif, level 1-2)\r\n\r\nLevel XP: 170 - 190\r\n\r\nBuilder: 5 Tukang (Jarang ada 6 Builder di harga ini)\r\n\r\nGems: Minim (Di bawah 500)\r\n\r\nGanti Nama (CN): Off / Tidak Bisa\r\n\r\nPemandangan (Scenery): Standar (Hutan/Default)\r\n\r\nFitur Eksklusif TH 15:\r\n\r\nMonolith: Sudah dibangun (Level 1 atau 2)\r\n\r\nSpell Tower: Sudah dibangun 2 unit (Level 1)\r\n\r\nPet House: Terbuka, sudah ada Pet baru seperti Diggy atau Frosty (Level rendah)\r\n\r\nKondisi Hero (Semi/Menengah): Hero biasanya tertinggal jauh dari level maksimal TH 15 (Level 90).\r\n\r\nBarbarian King: Level 60 - 65\r\n\r\nArcher Queen: Level 70 - 75 (Masih bisa dipakai War)\r\n\r\nGrand Warden: Level 45 - 50\r\n\r\nRoyal Champion: Level 25 - 30\r\n\r\nPertahanan (Defense):\r\n\r\nPertahanan Utama (Eagle, Scattershot): Level TH 13/14\r\n\r\nPertahanan Dasar (Cannon, Archer Tower): Masih level TH 12/13\r\n\r\nPagar / Wall: Sangat belang. Dominan level 13 (Biru tua) dan level 14 (Emas/Hijau). Mungkin masih ada sisa wall level 12.\r\n\r\nPasukan (Laboratorium):\r\n\r\nTroop War Andalan: Electro Dragon & Balon Max (Fokus spam E-Drag).\r\n\r\nTroop Meta: Root Rider mungkin sudah terbuka tapi level 1-2.\r\n\r\nSiege Machine: Battle Drill sudah terbuka.\r\n\r\nSpell: Rage, Freeze, Overgrowth (Level menengah).', 400000.00, 'active', NULL, '2025-12-25 01:47:10'),
(8, 'Wild Rift Ex Grandmaster Semi sultan', 'League of Legends', 'SPESIFIKASI UTAMA AKUN\r\n\r\nRank Saat Ini: Master (20 Mark)\r\n\r\nRank Tertinggi (Peak): Grandmaster\r\n\r\nLevel Akun: 92\r\n\r\nJumlah Champion: 100% (Semua Champion Terbuka)\r\n\r\nJumlah Skin Total: 115 Skin\r\n\r\nWinrate All Season: 54.5% (Match 3000+)\r\n\r\nWild Cores: 120\r\n\r\nBlue Motes: 25.000 (Cukup buat ganti nama jika beli item card)\r\n\r\nDAFTAR KOLEKSI SKIN (HIGHLIGHT)\r\n\r\n1. SKIN ULTIMATE, MYTHIC & GACHA (LANGKA)\r\n\r\nCrystal Rose Lux (Skin Gacha Event - Pasaran Mahal)\r\n\r\nSupreme Cells Sett (Skin Gacha)\r\n\r\nPrestige Brave Phoenix Xayah (Skin Edisi Prestige Terbatas)\r\n\r\n2. SKIN LEGENDARY (TIER TERTINGGI - EFEK MERAH) Total ada 7 Skin Legendary:\r\n\r\nGod King Garen\r\n\r\nGod King Darius\r\n\r\nDark Cosmic Jhin\r\n\r\nHigh Noon Lucian\r\n\r\nProject: Vayne\r\n\r\nStar Guardian Ahri\r\n\r\nMecha Sion\r\n\r\n3. SKIN WILD PASS (EKSKLUSIF SEASON) Lengkap dari season lama:\r\n\r\nHexplorer Jax (Season 2 - Sangat Langka)\r\n\r\nHexplorer Teemo\r\n\r\nStargazer Karma\r\n\r\nPsychic Detective Senna\r\n\r\nSuperhero Jayce\r\n\r\nSupervillain Jhin\r\n\r\nDream Raider Nasus\r\n\r\nFood Spirits Yuumi\r\n\r\n4. SKIN EPIC & SPECIAL (FULL SQUAD) Banyak koleksi skin set lengkap:\r\n\r\nSeri K/DA All Out: Ahri, Akali, Kai\'Sa, Evelynn\r\n\r\nSeri Project: Yasuo, Zed, Katarina, Ashe, Leona\r\n\r\nSeri Star Guardian: Ezreal, Janna, Lulu, Miss Fortune\r\n\r\nSeri Pool Party: Fiora, Lee Sin, Renekton\r\n\r\nSeri Blood Moon: Diana, Jhin, Twisted Fate, Yasuo\r\n\r\nSeri Juara Dunia (iG): Camille, Fiora, Kai\'Sa\r\n\r\nINVENTARIS & KOSMETIK\r\n\r\nRecall Efek: Project Recall, K/DA Recall, dan Recall bawaan skin Legendary.\r\n\r\nBorder: Border Crystal Rose, Border God King, dan Border Rank Grandmaster.\r\n\r\nPose: Hampir semua skin di atas memiliki Pose Khusus (tidak standar).\r\n\r\nPoro Energy: 3000 (Setengah jalan menuju peti skin gratis).', 800000.00, 'active', NULL, '2025-12-25 01:51:58'),
(9, 'Wild Rift lvl 87 ex GM', 'League of Legends', 'SPESIFIKASI UTAMA\r\n\r\nRank Saat Ini: Diamond 1 (Sedikit lagi naik Master)\r\n\r\nRank Tertinggi: Master / Grandmaster (Season lalu)\r\n\r\nLevel Akun: 78\r\n\r\nJumlah Champion: 92 Champion (Hampir lengkap, kurang hero support/tank yang jarang dipakai)\r\n\r\nJumlah Skin Total: 85 Skin\r\n\r\nWinrate: 52.8% (Ranked Match)\r\n\r\nBlue Motes: 15.000\r\n\r\nWild Cores: 45 (Sisa topup)\r\n\r\nRINCIAN KOLEKSI SKIN\r\n\r\n1. SKIN LEGENDARY (TIER TINGGI - EFEK MERAH) Ada 3 Skin Legendary (Biasanya hero carry):\r\n\r\nGod King Garen (Skin sejuta umat Baron Lane)\r\n\r\nProject: Yi (Favorit Jungler)\r\n\r\nDark Star Thresh (Support)\r\n\r\n2. SKIN WILD PASS (BATTLE PASS) Tidak full semua season, tapi memiliki yang langka:\r\n\r\nHexplorer Jax (Season 2)\r\n\r\nHexplorer Teemo\r\n\r\nStargazer Karma\r\n\r\nSupervillain Jhin\r\n\r\nSoda Pop Ahri\r\n\r\n3. SKIN EPIC & SPECIAL (HIGHLIGHT)\r\n\r\nSeri Project: Project Zed, Project Yasuo, Project Leona.\r\n\r\nSeri High Noon: High Noon Yasuo, High Noon Darius.\r\n\r\nSeri Dragon Slayer: Dragon Slayer Pantheon, Dragon Slayer Xin Zhao.\r\n\r\nSeri K/DA (Lama): K/DA Ahri, K/DA Kai\'Sa.\r\n\r\nSeri Omega Squad: Fizz, Teemo, Tristana.\r\n\r\nSkin Lunar Beast: Lunar Beast Annie, Lunar Beast Jarvan IV.\r\n\r\n4. SKIN LAINNYA Sisanya adalah campuran skin Elite (Merah) dan Normal yang didapat dari event peti gratisan (Poro Chest).\r\n\r\nINVENTARIS & AKSESORIS\r\n\r\nBauble (Aksesoris Kill): Banyak koleksi lama.\r\n\r\nRecall: Recall K/DA, Recall Event Lunar, dan Recall bawaan Legendary.\r\n\r\nRift Emblem: Ada emblem elemen Naga dan emblem Guild.\r\n\r\nPose: Pose God King Garen & Project Yi (Lengkap).', 525000.00, 'active', NULL, '2025-12-25 01:55:09'),
(10, 'Wild Rift ADC lvl 17', 'League of Legends', 'SPESIFIKASI UTAMA\r\n\r\nRank Saat Ini: Emerald I / Diamond IV\r\n\r\nWinrate Total: 75% - 80% (Sangat Tinggi)\r\n\r\nJumlah Match: Sedikit (Sekitar 80 - 120 match)\r\n\r\nLevel Akun: 25 - 30 (Baru tembus Ranked)\r\n\r\nJumlah Champion: 20 - 25 (Hanya hero Meta yang dibeli)\r\n\r\nJumlah Skin: 5 - 8 Skin (Skin gratisan)\r\n\r\nDETAIL STATISTIK (NILAI JUAL) Akun ini memiliki MMR (Matchmaking Rating) yang sangat tinggi.\r\n\r\nKeuntungan: Sekali menang dapat poin rank (Mark/Fortitude) lebih banyak, loncat tier lebih cepat.\r\n\r\nWinrate Hero Main (Contoh Jungler/Carry):\r\n\r\nLee Sin: WR 85% (30 Match)\r\n\r\nKha\'Zix: WR 78% (25 Match)\r\n\r\nIrelia: WR 80% (20 Match)\r\n\r\nKDA Rata-rata: Sangat Bagus (MVP/SVP sering)\r\n\r\nINVENTARIS (SUMBER DAYA)\r\n\r\nBlue Motes: 35.000+ (Ini yang mahal. Kamu bisa langsung beli 6-7 Champion pilihan kamu sendiri tanpa harus farming dari nol)\r\n\r\nPoro Energy: Masih awal\r\n\r\nWild Cores: 0\r\n\r\nKOLEKSI SKIN (BONUS) Skin hanya seadanya dari hadiah peti pemula (New Player Chest):\r\n\r\n1 Skin Epic Random (Contoh: Shockblade Zed / High Noon Yasuo)\r\n\r\nSkin Glorious (Hadiah Rank Season ini)\r\n\r\nSisanya skin Basic', 980000.00, 'active', NULL, '2025-12-25 01:57:32'),
(11, 'Akun Murah Skin GG KOF Chou', 'Mobile Legends', 'SPESIFIKASI UTAMA\r\n\r\nRank Saat Ini: Epic / Legend (Tergantung reset season)\r\n\r\nRank Tertinggi: Mythic Honor (25 - 30 Bintang)\r\n\r\nJumlah Hero: 40 - 55 Hero (Akun belum terlalu lama/hero sedikit)\r\n\r\nJumlah Skin: 60 - 80 Skin\r\n\r\nEmblem: Rata-rata Level 40-50 (Belum ada yang Max level 60)\r\n\r\nWinrate: Standar (50% - 52%)\r\n\r\nKOLEKSI SKIN UTAMA (HIGHLIGHT)\r\n\r\n1. SKIN KOF (KING OF FIGHTERS)\r\n\r\nChou (Iori Yagami) - NILAI JUAL UTAMA\r\n\r\nKarina (Leona) - Skin KOF gratisan, pasti ada\r\n\r\n2. SKIN EPIC & SPECIAL LAINNYA Karena budget 300k sudah habis di nilai skin Chou KOF, sisa skin lainnya biasanya standar:\r\n\r\nSkin STUN: Mungkin ada 1 (Brody/Selena/Chou STUN)\r\n\r\nSkin Epic Shop: Ada 1-2 biji (Contoh: Harley Great Inventor atau Saber Regulator)\r\n\r\nSkin Season: Lengkap season baru\r\n\r\nSkin 515: Harith / Zilong\r\n\r\nKONDISI INVENTARIS\r\n\r\nRecall: Standar / Trial. Jangan harap ada tas-tas permanen.\r\n\r\nEfek Spawn/Eliminasi: Standar event gratisan.', 300000.00, 'sold', NULL, '2025-12-25 02:05:01'),
(12, 'Ling Collector High MMR', 'Mobile Legends', 'SPESIFIKASI UTAMA\r\n\r\nRank Saat Ini: Epic / Legend\r\n\r\nRank Tertinggi: Mythical Glory (50 - 60 Bintang)\r\n\r\nJumlah Hero: 60 - 80 Hero (Hero Assassin lengkap, Role lain bolong-bolong)\r\n\r\nJumlah Skin: 90 - 110 Skin\r\n\r\nEmblem: Assassin Level 60 (Max), emblem lain rata-rata level 45-50.\r\n\r\nWinrate: 50% - 53% (Ribuan Match)\r\n\r\nKOLEKSI SKIN UTAMA (HIGHLIGHT)\r\n\r\n1. SKIN COLLECTOR (NILAI JUAL UTAMA)\r\n\r\nLing (Serene Plume) - Skin Collector langka, efek skill sangat bagus (pedang terbang).\r\n\r\n2. SKIN EPIC & EVENT LAINNYA Selain Ling Collector, biasanya ada beberapa skin tambahan:\r\n\r\nKOF: Karina (Leona)\r\n\r\nEpic Limited: Mungkin ada 1 tambahan (Misal: Selena Thunder Flash atau Roger Dr Beast) tapi hoki-hokian.\r\n\r\nEpic Shop: 2-3 Skin (Contoh: Gusion Venom, Lancelot Floral Knight).\r\n\r\nSkin 515: Harith, Zilong, Wanwan (M-World).\r\n\r\nSkin Special: Terizla Hammer Giant, Sun Street Legend, dll.\r\n\r\nKONDISI INVENTARIS\r\n\r\nEfek Recall: Standar (Bawaan event 515 atau event gratisan). Jangan harap ada Recall Tas Tas permanen di harga segini yang sudah ada Collectornya.\r\n\r\nMagic Wheel: Poin gacha legend biasanya masih sedikit (di bawah 100).', 450000.00, 'active', NULL, '2025-12-25 02:08:25'),
(13, 'Akun Sultan kolektor Terhormat II Skin 330', 'Mobile Legends', 'SPESIFIKASI UTAMA AKUN\r\n\r\nRank Saat Ini: Mythic Honor\r\n\r\nRank Tertinggi: Mythical Immortal (100+ Bintang) - Ada Loading Border Immortal\r\n\r\nJumlah Hero: 124 (Full Hero)\r\n\r\nJumlah Skin Total: 350 - 380 Skin\r\n\r\nEmblem: All Max Level 60 (Rata Kanan)\r\n\r\nWinrate: 55% - 60% (Sangat Sehat, Match Ribuan)\r\n\r\nDAFTAR KOLEKSI SKIN MEWAH\r\n\r\n1. SKIN LEGEND (MAGIC WHEEL) Ada 2 Skin Legend (Biasanya kombinasi Assassin/Mage):\r\n\r\nGusion (Cosmic Gleam)\r\n\r\nValir (Infernal Blaze)\r\n\r\nMagic Point: Sisa 150 (Setengah jalan lagi dapat batu Legend baru)\r\n\r\n2. SKIN COLLECTOR (EVENT MAHAL) Ada 2-3 Skin Collector:\r\n\r\nYu Zhong (Blood Serpent)\r\n\r\nPharsa (Empress Phoenix)\r\n\r\nWanwan (Pixel Blast)\r\n\r\n3. SKIN KOLABORASI (EVENT LIMITED)\r\n\r\nKOF: Chou (Iori Yagami), Gusion (K\'), Karina (Leona).\r\n\r\nTransformers: Roger (Grimlock), X-Borg (Bumblebee).\r\n\r\nAspirants: Fanny (Blade of Kibou) - Skin anime kabel jerami.\r\n\r\nSaint Seiya: Badang (Pegasus).\r\n\r\n4. SKIN EPIC LIMITED (OLD & BARU) Koleksi sangat banyak, contoh:\r\n\r\nAssassin: Hayabusa (Shadow of Obscurity), Selena (Thunder Flash), Lancelot (Royal Matador).\r\n\r\nFighter: Aldous (M1), Roger (M3), Chou (Dragon Boy).\r\n\r\nMage: Lunox (Butterfly), Kagura (Soryu Maiden).\r\n\r\nSkin ZODIAC: Hampir lengkap (10/13 skin).\r\n\r\nSkin LIGHTBORN: Full Squad.\r\n\r\nINVENTARIS & EFEK BATTLE (NILAI PLUS)\r\n\r\nEfek Recall: Ada Recall \"Tas Tas\" (Seal of Anvil) Permanen atau Recall \"Api\" (Fire Crown). Ini nilai jual tinggi.\r\n\r\nEfek Eliminasi: Banyak pilihan dari event KOF/Transformers.\r\n\r\nEmote: Emote \"Mic Check\" tim esports lengkap.\r\n\r\nSpawn: Efek spawn M1/M2 World Championship.', 1200000.00, 'active', NULL, '2025-12-25 02:13:55'),
(15, 'Skin GG semi sultan', 'Mobile Legends', 'skin nyak 123\r\nkof chou \r\ngojo satoru xavier dll....', 480000.00, 'active', NULL, '2025-12-26 09:02:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `listing_images`
--

CREATE TABLE `listing_images` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `listing_images`
--

INSERT INTO `listing_images` (`id`, `listing_id`, `image_path`) VALUES
(2, 1, 'uploads/img_694bdc3f7716e9.14878977.jpg'),
(3, 1, 'uploads/img_694bdc3f76f231.01553653.jpg'),
(4, 1, 'uploads/img_694bdc3f76c973.72997254.jpg'),
(5, 1, 'uploads/img_694bdc3f7736d0.40287174.jpg'),
(15, 6, 'uploads/img_694c97205fa187.18209197.jpg'),
(16, 6, 'uploads/img_694c9720601c85.70595683.jpg'),
(17, 6, 'uploads/img_694c9720606523.59791377.jpg'),
(18, 6, 'uploads/img_694c972060ab43.23123059.jpg'),
(19, 7, 'uploads/img_694c979edab265.95896902.jpg'),
(20, 7, 'uploads/img_694c979edb2631.80449545.jpg'),
(21, 7, 'uploads/img_694c979edb7754.29989820.jpg'),
(22, 7, 'uploads/img_694c979edbb4f6.90892266.jpg'),
(23, 7, 'uploads/img_694c979edbf886.39560749.jpg'),
(24, 7, 'uploads/img_694c979edc2ef8.58619240.jpg'),
(25, 7, 'uploads/img_694c979edc67a6.88689193.jpg'),
(26, 7, 'uploads/img_694c979edca580.88990487.jpg'),
(27, 8, 'uploads/img_694c98bed56468.74425930.jpg'),
(28, 8, 'uploads/img_694c98bed51089.49724365.jpg'),
(29, 8, 'uploads/img_694c98bed5a624.81698403.jpg'),
(30, 8, 'uploads/img_694c98bed5e297.37698428.jpg'),
(31, 8, 'uploads/img_694c98bed62085.41772083.jpg'),
(32, 8, 'uploads/img_694c98bed65995.14089559.jpg'),
(33, 8, 'uploads/img_694c98bed69e79.20875462.jpg'),
(34, 8, 'uploads/img_694c98bed6dcc1.20632469.jpg'),
(35, 8, 'uploads/img_694c98bed70be2.15526991.jpg'),
(36, 8, 'uploads/img_694c98bed750a6.49528841.jpg'),
(37, 9, 'uploads/img_694c997d116ff2.40179203.jpg'),
(38, 9, 'uploads/img_694c997d11f4a8.22831936.jpg'),
(39, 9, 'uploads/img_694c997d124197.52993121.jpg'),
(40, 9, 'uploads/img_694c997d1279d8.38145810.jpg'),
(41, 9, 'uploads/img_694c997d12b907.41555865.jpg'),
(42, 9, 'uploads/img_694c997d130061.09716225.jpg'),
(43, 9, 'uploads/img_694c997d135264.03805829.jpg'),
(44, 9, 'uploads/img_694c997d13a112.84356413.jpg'),
(45, 9, 'uploads/img_694c997d13e815.37631443.jpg'),
(46, 9, 'uploads/img_694c997d142d98.68867492.jpg'),
(47, 10, 'uploads/img_694c9a0c3f8293.17404604.jpg'),
(48, 10, 'uploads/img_694c9a0c3dc449.75945637.jpg'),
(49, 10, 'uploads/img_694c9a0c3e0e17.53358760.jpg'),
(50, 10, 'uploads/img_694c9a0c3e45f5.12950032.jpg'),
(51, 10, 'uploads/img_694c9a0c3e7cb7.92607287.jpg'),
(52, 10, 'uploads/img_694c9a0c3ebe13.80906001.jpg'),
(53, 10, 'uploads/img_694c9a0c3f05c1.27171221.jpg'),
(54, 10, 'uploads/img_694c9a0c3f46c8.96143773.jpg'),
(55, 10, 'uploads/img_694c9a0c3d7734.86271331.jpg'),
(56, 10, 'uploads/img_694c9a0c3fb822.92304453.jpg'),
(57, 11, 'uploads/img_694c9bcde57661.53165493.jpg'),
(58, 11, 'uploads/img_694c9bcde53149.23636411.jpg'),
(59, 11, 'uploads/img_694c9bcde4e547.91682382.jpg'),
(60, 11, 'uploads/img_694c9bcde5bcf8.35711325.jpg'),
(61, 11, 'uploads/img_694c9bcde5ff39.60888222.jpg'),
(62, 11, 'uploads/img_694c9bcde64540.08677048.jpg'),
(63, 11, 'uploads/img_694c9bcde681b9.32761966.jpg'),
(64, 12, 'uploads/img_694c9c99d01341.68015596.jpg'),
(65, 12, 'uploads/img_694c9c99cf7f57.46443809.jpg'),
(66, 12, 'uploads/img_694c9c99cf3418.16559252.jpg'),
(67, 12, 'uploads/img_694c9c99d0ac73.57629522.jpg'),
(68, 12, 'uploads/img_694c9c99d0f685.74555428.jpg'),
(69, 12, 'uploads/img_694c9c99d13b58.73490130.jpg'),
(70, 12, 'uploads/img_694c9c99d17e10.84573516.jpg'),
(71, 13, 'uploads/img_694c9de3da2884.90998393.jpg'),
(72, 13, 'uploads/img_694c9de3da7345.94504145.jpg'),
(73, 13, 'uploads/img_694c9de3dab7c5.75529897.jpg'),
(74, 13, 'uploads/img_694c9de3daed79.74485596.jpg'),
(75, 13, 'uploads/img_694c9de3db7796.98347210.jpg'),
(76, 13, 'uploads/img_694c9de3dbb840.25202782.jpg'),
(77, 13, 'uploads/img_694c9de3dbfc70.04089687.jpg'),
(90, 15, 'uploads/img_694e4f1c6b4132.54565250.jpg'),
(91, 15, 'uploads/img_694e4f1c6aaf48.81421396.jpg'),
(92, 15, 'uploads/img_694e4f1c6ae065.22564946.jpg'),
(93, 15, 'uploads/img_694e4f1c6b0b06.42281258.jpg'),
(94, 15, 'uploads/img_694e4f1c6a5970.48124298.jpg'),
(95, 15, 'uploads/img_694e4f1c6b73b3.17561219.jpg'),
(96, 15, 'uploads/img_694e4f1c6bb086.61517724.jpg'),
(97, 15, 'uploads/img_694e4f1c6be351.46860699.jpg'),
(98, 15, 'uploads/img_694e4f1c6c1510.29561478.jpg'),
(99, 15, 'uploads/img_694e4f1c6c3bd3.20198248.jpg'),
(100, 15, 'uploads/img_694e4f1c6c6133.95823561.jpg'),
(101, 15, 'uploads/img_694e4f1c6c8859.49500952.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `listing_id`, `payment_method`, `status`, `order_date`) VALUES
(3, 3, 11, 'Gopay', 'confirmed', '2025-12-26 08:59:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_admin`, `created_at`) VALUES
(1, 'Admin', 'admin@gamestore.com', 'admin123', 1, '2025-12-24 12:14:42'),
(2, 'suandy', 'suandydoank@gmail.com', '123456', 0, '2025-12-24 13:38:37'),
(3, 'suandy', 'suandyajah@gmail.com', '123456', 0, '2025-12-26 08:58:34');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `listing_images`
--
ALTER TABLE `listing_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `listing_images`
--
ALTER TABLE `listing_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `listing_images`
--
ALTER TABLE `listing_images`
  ADD CONSTRAINT `listing_images_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
