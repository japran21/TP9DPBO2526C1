# TP9DPBO2526C1

## JANJI
Saya Muhammad Ilal Zhafran dengan NIM 2502623 mengerjakan Tp 9 dalam mata kuliah DPBO untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin

## STRUKTUR

```text
TP 9/
├── project/
│   ├── index.php                              
│   ├── assets/
│   │   └── style.css                          
│   ├── models/
│   │   ├── DB.php                            
│   │   ├── KontrakModel.php                   
│   │   ├── KontrakModelKandidat.php          
│   │   ├── KontrakModelLowongan.php           
│   │   ├── Kandidat.php                       
│   │   ├── Lowongan.php                       
│   │   ├── TabelKandidat.php                  
│   │   └── TabelLowongan.php                  
│   ├── views/
│   │   ├── KontrakView.php                    
│   │   ├── KontrakViewKandidat.php            
│   │   ├── KontrakViewLowongan.php            
│   │   ├── TemplateRenderer.php               
│   │   ├── ViewKandidat.php                   
│   │   └── ViewLowongan.php                   
│   ├── presenters/
│   │   ├── KontrakPresenter.php               
│   │   ├── KontrakPresenterKandidat.php       
│   │   ├── KontrakPresenterLowongan.php       
│   │   ├── PresenterHelper.php                #
│   │   ├── PresenterKandidat.php              
│   │   └── PresenterLowongan.php              
│   └── templates/
│       ├── layout.html                       
│       ├── kandidat_list.html                 
│       ├── kandidat_form.html                
│       ├── lowongan_list.html                
│       └── lowongan_form.html                 
├── dokumentasi/
│   └── Vidio                             
└── mvp_db.sql                                 
                               
```

# Desain
## 1.
**Penerapan aturan Strict Passive View:**
- **View Pasif (*Dumb View*):** Direktori `project/views/` dan `project/templates/` **tidak** memiliki `require_once` ke file Model maupun Entity, **tidak** mengandung `foreach`, dan **tidak** memanggil `count()`.
- **Komunikasi Berbasis Kontrak:** Presenter hanya bergantung pada interface `KontrakModel*` dan `KontrakView*`, serta mengimplementasikan `KontrakPresenter*`. Instansiasi implementasi konkret dilakukan hanya pada composition root (`project/index.php`).
- **Format & Iterasi di Presenter:** Seluruh perhitungan statistik ringkasan, iterasi `foreach` untuk menyusun baris tabel `<tr>...</tr>` dan `<option>`, serta sanitasi `htmlspecialchars` dijalankan di lapisan Presenter sebelum dikirim sebagai string primitif ke View.
- **Keamanan Query (Prepared Statement):** Seluruh operasi `SELECT ... WHERE id = :id`, `INSERT`, `UPDATE`, dan `DELETE` pada `TabelKandidat` dan `TabelLowongan` memakai PDO native prepared statement (`PDO::ATTR_EMULATE_PREPARES => false`).

### 2. Desain Database (`mvp_db.sql`)

Database: **`mvp_kandidat_db`**

1. **Tabel `kandidat`**
   - `id` (`INT`, `AUTO_INCREMENT`, `PRIMARY KEY`)
   - `nama` (`VARCHAR(100)`)
   - `posisi` (`VARCHAR(100)`)
   - `domisili` (`VARCHAR(100)`)
   - `skorTes` (`INT`)
   - `pengalamanTahun` (`INT`)

2. **Tabel `lowongan`** *(modul tambahan bertema rekrutmen/HR)*
   - `id` (`INT`, `AUTO_INCREMENT`, `PRIMARY KEY`)
   - `judulPosisi` (`VARCHAR(100)`)
   - `departemen` (`VARCHAR(100)`)
   - `lokasi` (`VARCHAR(100)`)
   - `tipeKerja` (`VARCHAR(50)`: `Full-time`, `Hybrid`, `Remote`, `Contract`)
   - `kuota` (`INT`)
   - `status` (`VARCHAR(30)`: `Dibuka`, `Ditutup`)

---

## Penjelasan Alur Program

pada `project/index.php` dengan parameter `module` (`kandidat` sebagai default, atau `lowongan`).

1. **Menampilkan Daftar Data (`Read`):**
   - Request `GET index.php` (atau `GET index.php?module=lowongan`) memanggil `PresenterKandidat::tampilkanKandidat()` atau `PresenterLowongan::tampilkanLowongan()`.
   - Presenter meminta seluruh entitas dari Model (`getAllKandidat()` / `getAllLowongan()`), menghitung ringkasan KPI, menyusun HTML baris tabel, lalu meneruskan string hasil ke View (`tampilKandidat()` / `tampilLowongan()`).

2. **Menambah Data (`Create`):**
   - Klik tombol **+ Tambah Kandidat** (`?module=kandidat&screen=add`) atau **+ Tambah Lowongan** (`?module=lowongan&screen=add`).
   - Presenter memanggil View untuk merender form kosong dengan `action="tambah"`.
   - Saat form disubmit via `POST`, `index.php` membaca `$_POST['action'] === 'tambah'`, memanggil `tambahKandidat()` / `tambahLowongan()`, menyimpan notifikasi status ke `$_SESSION['flash']`, lalu melakukan redirect ke halaman daftar.

3. **Mengubah Data (`Update`):**
   - Klik tombol **Edit** pada salah satu baris (`?module=...&screen=edit&id=...`).
   - Presenter memanggil `getKandidatById($id)` / `getLowonganById($id)`. Jika data ditemukan, nilai lama langsung diisikan (*prefill*) ke field form dengan `action="ubah"` dan input tersembunyi `id`.
   - Saat form disubmit via `POST`, `index.php` menangani `$_POST['action'] === 'ubah'`, memanggil `ubahKandidat()` / `ubahLowongan()`, lalu redirect kembali ke daftar.

4. **Menghapus Data (`Delete`):**
   - Klik tombol **Hapus** pada baris data (meminta konfirmasi pengguna terlebih dahulu).
   - Form mengirimkan `POST` ke `index.php` dengan `action="hapus"` dan `id` baris terkait.
   - `index.php` memanggil `hapusKandidat($id)` / `hapusLowongan($id)` yang mengeksekusi query `DELETE` prepared statement, lalu redirect kembali ke daftar.

---

## Dokumentasi & Screenrecord

https://github.com/user-attachments/assets/a27bec5b-300a-40ec-ae25-a8a807ef5d52


