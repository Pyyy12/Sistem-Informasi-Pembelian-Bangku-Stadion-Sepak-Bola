<p align="center">
  <h1 align="center">🏟️ Stadium Ticket Booking System (Laravel 11)</h1>
  <p align="center">Sistem visualisasi denah kursi stadion dan reservasi tiket berbasis web.</p>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine JS">
  <img src="https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge" alt="License">
</p>

---

## 📌 Tentang Proyek

Aplikasi penjualan tiket pertandingan stadion dengan visualisasi interaktif denah kursi (*seat map*). Sistem ini mengadopsi mekanisme pemilihan kursi bioskop dengan dua mode akses:

- **Mode Pelanggan (Online Ala Bioskop):** Pengguna memilih kursi yang diinginkan langsung dengan klik pada bangku berwarna merah, memasukkan data pemesan, dan melakukan checkout.
- **Mode Admin (Drag & Drop POS):** Kasir/Admin di loket fisik dapat menyeret (*drag*) bangku merah yang kosong ke area keranjang kasir untuk transaksi langsung (*on the spot*).

### Indikator Status Kursi
| Warna | Status | Keterangan |
| :---: | :---: | :--- |
| 🔴 **Merah** | **Kosong (Available)** | Kursi bebas dipilih (Customer) atau diseret ke kasir (Admin). |
| 🟡 **Kuning** | **Dipilih (Selected)** | Kursi yang sedang Anda pilih sebelum checkout (Mode Customer). |
| 🟢 **Hijau** | **Terisi (Booked)** | Kursi sudah terjual / terisi dan terkunci otomatis. |

---

## 🚀 Fitur Utama

- **Pencegahan Double-Booking:** Dilengkapi mekanisme `lockForUpdate()` pada transaksi database untuk mengunci kursi saat checkout bersamaan.
- **Drag & Drop POS:** Integrasi native HTML5 Drag and Drop API dengan state management Alpine.js untuk kemudahan loket tiket.
- **Denah Interaktif:** Penataan layout visual berdasarkan Tribun, Baris (Row), dan Nomor Kursi (Seat Number).
- **Responsive & Modern:** Menggunakan Tailwind CSS responsif dan antarmuka bertema stadion gelap (*dark stadium theme*).

---

## 🛠️ Persyaratan Sistem

- PHP >= 8.2
- Composer
- MySQL / MariaDB / PostgreSQL
- Node.js & NPM (Opsional jika ingin build aset secara lokal)

---

## ⚙️ Panduan Instalasi

1. **Clone repository ini:**
   ```bash
   git clone [https://github.com/username-anda/stadium-ticketing.git](https://github.com/username-anda/stadium-ticketing.git)
   cd stadium-ticketing
