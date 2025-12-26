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

## Tata Cara Penggunaan & Tampilan Aplikasi

Bagian ini menjelaskan secara mendalam setiap fitur utama dari aplikasi, lengkap dengan skenario, prasyarat, dan alur proses untuk memberikan pemahaman yang komprehensif.

---


### Halaman Utama (`index.php`)

![Beranda](img/menu_beranda.png)

Halaman ini adalah fasad digital dari Game Store, titik pertama interaksi bagi semua pengunjung. Desainnya bersih dan terfokus untuk memandu pengguna ke area-area paling relevan dari situs.

-   **Skenario Pengguna:** Seorang pengunjung bernama 'Andi' baru pertama kali mendengar tentang situs ini. Ia membukanya untuk melihat-lihat seperti apa platformnya, apa saja yang ditawarkan, dan bagaimana cara kerjanya.
-   **Prasyarat:** Tidak ada. Halaman ini sepenuhnya publik dan dapat diakses oleh siapa saja dengan koneksi internet.
-   **Detail Elemen UI:**
    -   **Header Navigasi:** Berisi link-link vital: `Beranda` untuk kembali ke halaman ini, `Listing` untuk menjelajahi semua produk, serta `Login` dan `Register` untuk manajemen akun.
    -   **Konten Utama:** Biasanya menampilkan daftar produk unggulan, promosi khusus, atau game yang baru ditambahkan untuk menarik perhatian pengguna.
-   **Alur Proses & Hasil:**
    1.  Pengguna membuka URL situs.
    2.  Halaman Beranda dimuat, menampilkan navigasi utama.
    3.  Pengguna dapat mengklik `Listing` untuk langsung melihat katalog produk, atau mengklik `Login`/`Register` jika ingin berinteraksi lebih jauh.

---


### Registrasi Pengguna (`register.php`)

![Register](img/menu_register.png)

Fitur ini adalah gerbang bagi pengunjung untuk menjadi anggota komunitas Game Store. Prosesnya dirancang agar cepat dan mudah.

-   **Skenario Pengguna:** 'Andi' tertarik untuk menjual beberapa game miliknya. Untuk melakukan itu, ia perlu membuat akun terlebih dahulu.
-   **Prasyarat:** Pengguna harus memiliki alamat email yang aktif dan unik (belum terdaftar di sistem).
-   **Detail Elemen UI:**
    -   `Username`: Nama unik yang akan menjadi identitas pengguna di platform.
    -   `Email`: Digunakan untuk komunikasi dan notifikasi. Harus valid.
    -   `Password`: Kata sandi untuk mengamankan akun. Sebaiknya mengikuti standar keamanan minimum.
    -   `Tombol Register`: Tombol final untuk mengirimkan data pendaftaran.
-   **Alur Proses & Hasil:**
    1.  'Andi' mengklik "Register" di menu utama.
    2.  Ia mengisi semua kolom pada formulir.
    3.  **Jika Sukses:** Setelah menekan "Register", data divalidasi oleh sistem. Akun baru dibuat, dan 'Andi' biasanya akan dialihkan ke halaman Login untuk masuk dengan akun barunya.
    4.  **Jika Gagal:** Jika username atau email sudah ada, atau jika ada data yang tidak valid, sistem akan menampilkan pesan error di halaman yang sama, meminta 'Andi' untuk memperbaiki isiannya.

---


### Tampilan Listing Game (`listing.php`)

![Listing](img/menu_listing.png)

Ini adalah etalase utama dari Game Store, tempat semua produk yang dijual oleh komunitas ditampilkan dalam format galeri yang mudah dinavigasi.

-   **Skenario Pengguna:** 'Andi' (yang kini sudah login) ingin melihat game apa saja yang sedang dijual untuk mencari inspirasi harga sebelum menjual game miliknya.
-   **Prasyarat:** Halaman ini dapat diakses publik, namun fungsionalitas seperti membeli memerlukan pengguna untuk login.
-   **Detail Elemen UI:**
    -   **Kartu Produk:** Setiap game ditampilkan sebagai "kartu" yang berisi informasi esensial: gambar sampul, nama game, dan harga.
    -   **Link Detail:** Seluruh area kartu biasanya dapat diklik, berfungsi sebagai link untuk menuju halaman `listing_detail.php`.
-   **Alur Proses & Hasil:**
    1.  Pengguna mengklik menu "Listing".
    2.  Sistem mengambil semua data produk dari database dan menampilkannya.
    3.  Pengguna dapat scroll untuk melihat semua penawaran. Jika ia menemukan game yang menarik, ia akan mengkliknya untuk melihat detail lebih lanjut.

---


### Membuat Listing Baru (`create_listing.php`)

![Buat Listing Baru](img/menu_buat_listing_baru.png)

Ini adalah fitur inti bagi penjual. Halaman ini menyediakan formulir lengkap bagi pengguna untuk mempublikasikan game yang ingin mereka jual.

-   **Skenario Pengguna:** 'Andi' siap menjual game pertamanya. Ia menavigasi ke halaman ini untuk membuat penawarannya.
-   **Prasyarat:** Pengguna **wajib** dalam keadaan login.
-   **Detail Elemen UI:**
    -   `Nama Game`: Judul yang jelas dan informatif.
    -   `Deskripsi`: Area teks untuk menjelaskan kondisi game, bonus, atau cerita menarik di baliknya. Deskripsi yang baik meningkatkan peluang penjualan.
    -   `Harga`: Kolom untuk memasukkan harga jual.
    -   `Upload Gambar`: Fitur untuk mengunggah gambar sampul (cover) game. Visual adalah kunci.
    -   `Tombol Submit`: Mengirimkan semua data untuk dibuat menjadi sebuah listing publik.
-   **Alur Proses & Hasil:**
    1.  'Andi' mengisi semua detail pada formulir.
    2.  Ia mengunggah sebuah gambar dari komputernya.
    3.  **Jika Sukses:** Setelah menekan "Submit", sistem memvalidasi data, menyimpan informasi ke database, dan meng-copy file gambar ke direktori `uploads/`. Listing baru tersebut kini akan muncul di halaman "Listing" utama. 'Andi' mungkin akan dialihkan ke halaman detail produk barunya.
    4.  **Jika Gagal:** Jika ada kolom wajib yang kosong atau data tidak valid, halaman akan memuat ulang dengan pesan error yang spesifik.

---


### Panel Admin - Manajemen Pengguna (`admin_users.php`)

![Manajemen Pengguna](img/menu_manajemen_pengguna.png)

Ini adalah pusat kendali bagi administrator untuk mengelola seluruh basis data pengguna. Fitur ini krusial untuk menjaga keamanan dan ketertiban platform.

-   **Skenario Pengguna:** Seorang admin bernama 'Citra' mendapat laporan tentang aktivitas mencurigakan dari akun 'Andi'. Ia perlu memeriksa detail akun tersebut dan mengambil tindakan jika perlu.
-   **Prasyarat:** Pengguna harus login dengan akun yang memiliki hak akses sebagai `administrator`.
-   **Detail Elemen UI:**
    -   **Tabel Pengguna:** Menampilkan daftar semua pengguna beserta detail kunci seperti `username`, `email`, dan `role`.
    -   **Fungsi Pencarian:** Memungkinkan admin untuk menemukan pengguna spesifik dengan cepat.
    -   **Tombol `Edit`:** Mengarahkan ke halaman untuk mengubah data pengguna (misalnya, mereset password atau mengubah role).
    -   **Tombol `Delete`:** Menghapus pengguna dari sistem secara permanen (biasanya didahului oleh dialog konfirmasi).
-   **Alur Proses & Hasil:**
    1.  'Citra' login dan masuk ke Panel Admin, lalu memilih menu "Manajemen Pengguna".
    2.  Ia menggunakan pencarian untuk menemukan 'Andi'.
    3.  Dari tabel, 'Citra' bisa memilih untuk meng-edit (misalnya, menonaktifkan akun dengan mengubah rolenya) atau langsung menghapus akun 'Andi' jika terbukti melanggar aturan. Setiap tindakan akan memberikan feedback visual tentang keberhasilannya.