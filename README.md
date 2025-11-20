# fairytale-campground-by-group-10

### OUR KEKURANGAN WEB KITA (lebih ke front end sy)

### A. Visual & Layout (Tampilan)
1.  **Hero Image Tersembunyi:** Gambar pemandangan di halaman utama ("Home") berada terlalu jauh di bawah (*below the fold*), sehingga user harus *scroll* dulu untuk melihatnya.
2.  **White Space Berlebih:** Terlalu banyak ruang kosong antara *Navbar* dan judul utama di halaman Home, membuat halaman terlihat "belum selesai" atau *broken*.
3.  **Hierarki Visual Lemah:** Judul "Fairytale Campground" di Home kurang menonjol (hanya teks hitam polos di atas putih). Kurang memancarkan atmosfer "alam".
4.  **Image Placeholder:** Pada halaman "Order Summary", gambar tenda tidak muncul (hanya kotak abu-abu bertuliskan "Img").
5.  **Inkonsistensi Desain Halaman Login Berhasil:** Halaman "Login Berhasil" menggunakan *background gradient* hijau penuh yang mencolok, berbeda jauh dari gaya desain halaman lain yang bersih (putih/minimalis).

### B. Copywriting & Bahasa (Konten)
6.  **Bahasa Tidak Konsisten (Gado-gado):** Percampuran Bahasa Indonesia dan Inggris di berbagai elemen.
    * Contoh: "Order Summary Anda", "Book tenda", "Single Tent (x1 malam)", "Check me out".
7.  **Copywriting Internal/Unprofessional:** Penggunaan kata "Gacorrr" pada *footer* halaman Login.
8.  **Teks Placeholder Tertinggal:** Pada kartu pilihan tenda (*Booking page*), deskripsi masih menggunakan *dummy text* bawaan Bootstrap: *"Some quick example text to build on the card title..."*.
9.  **Label Form Default:** *Checkbox* pada halaman Registrasi masih menggunakan label default Bootstrap: *"Check me out"* (seharusnya: "Saya menyetujui syarat & ketentuan" atau "Remember me").

### C. User Experience (UX) & Alur
10. **Metode Login Tidak Standar:** Form Login meminta "Nama Anda", padahal standar keamanan dan keunikan data biasanya menggunakan "Email" atau "Username".
11. **Pemilihan Tenda "Buta":** User diminta memilih nomor tenda spesifik (misal: "Single Tent - 01") tanpa adanya referensi Peta Lokasi (*Site Map*). User tidak tahu posisi tenda tersebut.
12. **Redundansi Halaman Contact:** Terdapat tombol "Website" di dalam halaman *Contact Us*, padahal user sudah berada di dalam website tersebut.
13. **Alur Login Berhasil:** User harus mengklik tombol manual "Booking Disini" setelah login berhasil. Seharusnya sistem melakukan *auto-redirect* ke halaman booking untuk mempercepat proses.
14. **Status Kosong yang Membingungkan:** Pada halaman "Hasil Pilihan Tenda", jika user tidak memilih Double/Family tent, tampilannya hanya strip (-) yang terlihat seperti *error*. Seharusnya bagian yang tidak dipilih disembunyikan saja.

### D. Kredibilitas & Fitur (Legitimacy)
15. **Tidak Ada Lokasi Fisik:** Halaman *Contact Us* tidak memiliki peta (Google Maps embed) atau alamat fisik yang jelas.
16. **Tombol Pembayaran Belum Siap:** Tombol "COMING SOON [Tombol Pembayaran]" di Order Summary memutus alur transaksi user.
17. **Minim Validasi Sosial:** Tidak ada bagian testimoni, ulasan pelanggan, atau galeri foto aktivitas (orang) yang meyakinkan user bahwa tempat ini nyata dan menyenangkan.
