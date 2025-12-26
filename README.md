# Game Store - Platform Jual Beli Game Digital

## Deskripsi Proyek

**Game Store** adalah sebuah aplikasi web platform _e-commerce_ yang dirancang khusus untuk para gamer. Aplikasi ini memungkinkan pengguna untuk tidak hanya membeli game digital tetapi juga menjual game mereka sendiri dalam bentuk "listing". Platform ini memfasilitasi seluruh siklus transaksi, mulai dari pendaftaran pengguna, pembuatan dan pengelolaan listing, hingga proses pemesanan dan konfirmasi.

Aplikasi ini juga dilengkapi dengan panel administrasi yang kuat, memberikan kontrol penuh kepada admin untuk mengelola pengguna, memantau laporan penjualan, dan mengatur status pesanan. Arsitekturnya dibangun menggunakan tumpukan teknologi web klasik (PHP, MySQL, HTML/CSS/JS), menjadikannya solusi yang mudah dipahami dan dikelola.

## Teknologi yang Digunakan

- **Backend**: PHP (Native)
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript (Vanilla)
- **Web Server**: Apache (Biasanya dijalankan melalui lingkungan pengembangan seperti XAMPP)

## Struktur Proyek

Berikut adalah penjelasan mengenai struktur file dan direktori dalam proyek ini:

```
c:\xampp\htdocs\game-store\
├─── admin_*.php            # Kumpulan file untuk fitur panel admin
├─── auth.php               # Logika inti untuk autentikasi sesi pengguna
├─── create_listing.php     # Halaman form untuk membuat listing baru
├─── db.php                 # Skrip koneksi ke database MySQL
├─── delete_listing.php     # Skrip untuk menghapus listing
├─── edit_listing.php       # Halaman form untuk mengedit listing
├─── footer.php             # Komponen UI/template untuk bagian footer
├─── header.php             # Komponen UI/template untuk bagian header
├─── index.php              # Halaman utama (beranda) aplikasi
├─── listing_detail.php     # Halaman untuk menampilkan detail satu listing
├─── listing.php            # Halaman untuk menampilkan semua listing
├─── login.php              # Halaman login pengguna
├─── logout.php             # Skrip untuk proses logout
├─── order_*.php            # Kumpulan file untuk manajemen pesanan sisi pengguna
├─── register.php           # Halaman registrasi pengguna baru
├─── script.js              # File JavaScript untuk interaktivitas di sisi klien
├─── setup_database.php     # Skrip untuk inisialisasi database awal
├─── side_bar.php           # Komponen UI/template untuk sidebar navigasi
├─── .git/                  # Direktori repositori Git
├─── database/              # Berisi file dump SQL (struktur dan data)
│   └─── migration.sql
│   └─── suandy_store.sql
├─── img/                   # Aset gambar statis untuk UI dan dokumentasi
└─── uploads/               # Direktori untuk menyimpan gambar yang di-upload pengguna (cover game)
```

## Fitur & Tampilan Aplikasi

Berikut adalah rincian fungsionalitas utama beserta tangkapan layar untuk setiap halaman.

### Halaman Utama (`index.php`)
Halaman utama dari website yang menyambut pengguna.
![Beranda](img/menu_beranda.png)

### Registrasi Pengguna (`register.php`)
Halaman untuk membuat akun baru.
![Register](img/menu_register.png)

### Login Pengguna (`login.php`)
Halaman untuk masuk ke akun yang sudah ada.
![Login](img/menu_login.png)

### Tampilan Listing Game (`listing.php`)
Halaman yang menampilkan semua listing game yang tersedia.
![Listing](img/menu_listing.png)

### Detail Listing Game (`listing_detail.php`)
Menampilkan detail lengkap dari satu game, dari perspektif pengunjung atau pengguna biasa.
![Detail Listing](img/menu_listing_detail.png)

### Detail Listing (Perspektif Penjual)
Tampilan detail listing dari sudut pandang pengguna yang memiliki item tersebut.
![Detail Listing User](img/menu_listing_detail_user.png)

### Membuat Listing Baru (`create_listing.php`)
Formulir untuk pengguna menambahkan listing game baru untuk dijual.
![Buat Listing Baru](img/menu_buat_listing_baru.png)

### Mengedit Listing (`edit_listing.php`)
Formulir untuk mengubah detail dari listing yang sudah ada.
![Edit Listing](img/menu_edit_listing.png)

### Halaman Pesanan Pengguna (`orders.php`)
Pengguna dapat melihat riwayat pesanan mereka di halaman ini.
![Order User](img/menu_order_user.png)

### Panel Admin - Manajemen Pengguna (`admin_users.php`)
Admin dapat melihat, menambah, mengedit, atau menghapus pengguna.
![Manajemen Pengguna](img/menu_manajemen_pengguna.png)

### Panel Admin - Manajemen Pesanan
Admin dapat melihat dan mengelola status pesanan yang masuk.
![Order Admin](img/menu_order_admin.png)

### Panel Admin - Laporan Penjualan (`admin_sales.php`)
Halaman yang menyajikan data penjualan untuk admin.
![Laporan](img/menu_laporan.png)

### Jelajahi Akun Pengguna
Fitur untuk melihat profil atau akun pengguna lain.
![Jelajahi Akun](img/menu_jelajahi_akun.png)