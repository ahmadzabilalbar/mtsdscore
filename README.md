# MTSD SCORE 📊

**MTSD SCORE** ialah Sistem Analisis Keputusan Peperiksaan interaktif yang dibangunkan khusus untuk **Maahad Tahfiz Sains Darul Aman (MTSD)**. Sistem ini membolehkan pihak pengurusan, guru, dan waris menganalisis prestasi akademik pelajar, kedudukan kedudukan kelas, perbandingan subjek, dan taburan gred secara visual dan dinamik.

Sistem ini direka bentuk sebagai aplikasi *single-page application* (SPA) premium dengan sokongan mod gelap/cerah (*dark/light mode*), visualisasi carta interaktif, dan penapis data yang fleksibel.

---

## ✨ Ciri-ciri Utama

1. **Papan Pemuka Interaktif (Interactive Dashboard)**
   - Paparan ringkasan prestasi utama seperti purata markah keseluruhan, peratus kelulusan, dan taburan gred.
   - Pilihan penapis pantas mengikut **Tahun (Batch)**, **Tingkatan (Form)**, **Peperiksaan (Exam)**, dan **Jantina (Gender)**.

2. **Analisis Visual Bergraf**
   - Integrasi **Chart.js** untuk memaparkan taburan gred (A hingga F), analisis prestasi subjek, dan statistik pencapaian keseluruhan.

3. **Jadual Keputusan Pelajar yang Lengkap**
   - Senarai penuh keputusan pelajar berserta fungsi carian nama.
   - Menyokong pengiraan automatik untuk Jumlah Markah, Purata Markah, Gred Purata Pelajar (GPP), dan bilangan Gred A bagi setiap pelajar.
   - Susunan kedudukan (Ranking) yang dinamik mengikut penapis semasa.

4. **Sokongan Mod Gelap & Cerah (Theme Toggle)**
   - Antaramuka moden yang dioptimumkan menggunakan pembolehubah CSS (*CSS variables*) dan reka bentuk *glassmorphism* yang elegan.

---

## 📁 Struktur Projek

```
mtsdscore/
├── csv/                  # Folder yang mengandungi fail data CSV mentah
│   └── 2024-F1-1-L.csv   # Contoh fail data CSV (Format: Batch-Form-Exam-Gender.csv)
├── data.js               # Pangkalan data statik yang dikompilasi (Digunakan oleh index.html)
├── generate_data.php     # Skrip CLI PHP untuk mengkompilasi fail CSV kepada data.js
├── index.html            # Antaramuka utama web (HTML, CSS, JS)
└── README.md             # Dokumentasi projek (Fail ini)
```

---

## 🛠️ Cara Penggunaan & Mengemas Kini Data

Sistem ini menggunakan data statik `data.js` untuk kelajuan pemuatan yang optimum tanpa memerlukan pelayan pangkalan data (SQL) yang aktif. Ikuti langkah di bawah untuk menambah atau mengemas kini data peperiksaan:

### 1. Sediakan Fail CSV Mentah
Letakkan fail CSV keputusan peperiksaan di dalam folder `csv/`. Nama fail mestilah mengikut format berikut:
```
[Tahun/Batch]-[Tingkatan]-[KodPeperiksaan]-[Jantina].csv
```
*Contoh:* `csv/2024-F1-1-L.csv` (Batch 2024, Tingkatan 1, Peperiksaan 1, Lelaki).

#### Format Kandungan Fail CSV:
- **Baris Pertama (Header):** Nama Lajur Pertama mestilah untuk nama pelajar (cth: `NAMA`), diikuti oleh singkatan kod subjek (cth: `BM`, `BI`, `SEJ`, `MATE`, `SNS`).
- **Baris Seterusnya (Data):** Nama pelajar diikuti markah subjek masing-masing.

| NAMA | BM | BI | SEJ | MATE | SNS |
| :--- | :-: | :-: | :-: | :-: | :-: |
| AHMAD BIN ALI | 85 | 78 | 90 | 65 | 70 |
| MUHAMMAD BIN ABU | 70 | 60 | 85 | 50 | 55 |

> [!NOTE]
> Kod subjek yang disokong untuk pemetaan nama penuh termasuklah: `BM` (Bahasa Melayu), `BI` (Bahasa Inggeris), `SEJ` (Sejarah), `GEO` (Geografi), `BA` (Bahasa Arab), `PI` (Pendidikan Islam), `MATE` (Matematik), `SNS` (Sains), `RBT` (Reka Bentuk & Teknologi), `HQ` (Hifz Al-Quran), `MQ` (Maharat Al-Quran), `FEQ` (Feqah), `HAD` (Hadis), `TAU` (Tauhid), `TAF` (Tafsir), `PJK` (Pendidikan Jasmani & Kesihatan), dan `PSV` (Pendidikan Seni Visual).

### 2. Jalankan Skrip Kompilasi Data
Gunakan PHP CLI di terminal anda untuk mengkompilasi fail CSV ke dalam format JavaScript (`data.js`):
```bash
php generate_data.php
```

Skrip ini akan memproses semua fail CSV yang ditemui, mengira Gred Purata Pelajar (GPP), Gred subjek mengikut julat pemarkahan MTSD, mengasingkan kategori data, dan membina fail `data.js`.

### 3. Buka Papan Pemuka
Buka fail `index.html` menggunakan pelayar web (browser) pilihan anda (atau hoskan pada pelayan web Apache/Nginx). Data baharu akan dipaparkan secara automatik!

---

## ⚙️ Skala Gred Keputusan (MTSD)

Sistem ini mematuhi skala gred berikut bagi pengiraan Gred Purata Pelajar (GPP):
- **85 - 100:** Gred **A** (Nilai Gred: 1)
- **70 - 84:** Gred **B** (Nilai Gred: 2)
- **60 - 69:** Gred **C** (Nilai Gred: 3)
- **50 - 59:** Gred **D** (Nilai Gred: 4)
- **40 - 49:** Gred **E** (Nilai Gred: 5)
- **0 - 39:** Gred **F** (Nilai Gred: 6)

---

## 💻 Teknologi yang Digunakan

- **HTML5 & CSS3 (Vanilla)** - Papan pemuka responsif dengan reka bentuk moden bergaya *Deep Indigo* / *Glassmorphism*.
- **JavaScript (ES6+)** - Pemprosesan data statik, penapis carian, dan logik dinamik.
- **Chart.js** (via CDN) - Untuk visualisasi data carta palang dan carta pai interaktif.
- **Lucide Icons** (via CDN) - Pustaka ikon minimalis moden.
- **Plus Jakarta Sans** (Google Fonts) - Reka huruf yang bersih dan profesional.
