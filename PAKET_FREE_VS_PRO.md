# 🏆 Rangkuman Fitur & Spesifikasi Paket FREE vs PRO
**Palovite Digital Wedding Invitation System**

Dokumen ini berisi spesifikasi pembagian fitur antara paket **FREE (Gratis)** dan paket **PRO (Berbayar)** yang dirancang untuk memaksimalkan angka konversi penjualan (*conversion rate*) pada platform Palovite.

---

## 📊 Tabel Perbandingan Fitur

| Modul / Fitur | 🎁 Paket FREE (Gratis) | ⭐ Paket PRO (Berbayar) |
| :--- | :--- | :--- |
| **1. Daftar Undangan & Nama Tamu** | ❌ **Terkunci**<br>*(Hanya dapat link polos tanpa nama tamu)* | ✅ **Terbuka Penuh**<br>*(Link khusus nama tamu `?to=Nama` + Generator WA 1-klik)* |
| **2. Amplop Digital & Hadiah** | ❌ **Terkunci**<br>*(Fitur hadiah/transfer disembunyikan)* | ✅ **Terbuka Penuh**<br>*(Unlimited Bank & E-Wallet milik sendiri + Foto QRIS)* |
| **3. Galeri Foto** | 📷 **Maksimal 5 Foto** | 📸 **Unlimited Foto** |
| **4. Timeline Cerita Cinta** | 📝 **Hanya Teks**<br>*(Bebas tulis banyak cerita tanpa foto)* | 🖼️ **Teks + Upload Foto**<br>*(Bisa upload foto di tiap momen cerita)* |
| **5. Buku Tamu & Balas Ucapan** | 💬 **Bisa Terima Ucapan**<br>*(Tidak bisa membalas ucapan tamu)* | 💬 **Bisa Balas Pesan Ucapan**<br>+ **Fitur Export Data ke Excel/CSV** |
| **6. Watermark Aplikasi** | ⚠️ **Ada Watermark**<br>*(Tulisan branding Palovite di footer)* | ✨ **Bebas Watermark**<br>*(Tampilan bersih / branding sendiri)* |
| **7. Masa Aktif Undangan** | ⏳ **7 - 14 Hari** | ♾️ **Selamanya (Lifetime)** / 1 Tahun |

---

## 🔑 3 Pemicu Utama Pengantin Upgrade ke PRO (Conversion Drivers)

1. **Keinginan Mengirim Pesan WhatsApp Nama Tamu (Personalized Links)**:
   - Pengantin tidak ingin mengirim link polosan tanpa nama. Begitu mereka mencoba fitur sebar undangan ke tamu di H-30, mereka akan terdorong untuk upgrade ke PRO.
2. **Ingin Menerima Amplop Digital & Kado (Direct Transfer)**:
   - Fitur transfer bank / QRIS milik pengantin sendiri sangat krusial bagi tamu yang tidak bisa hadir secara fisik.
3. **Ingin Membalas Pesan Ucapan (Interaktif)**:
   - Pengantin ingin membalas ucapan doa dari sahabat dan keluarga yang menulis di buku tamu undangan digital mereka.

---

## 🛠️ Catatan Implementasi Teknis

Untuk menerapkan pembatasan ini secara otomatis pada kodingan aplikasi:
1. **Database Schema**: Tambahkan kolom `package` dengan tipe ENUM (`'free'`, `'pro'`) default `'free'` pada tabel `weddings`.
2. **Feature Gating**:
   - `guests` (Daftar Undangan): Tampilkan *Paywall Screen / Lock Banner* jika `package === 'free'`.
   - `wedding_gift` (Hadiah): Sembunyikan bagian amplop digital pada tampilan publik jika `package === 'free'`.
   - `gallery` (Galeri): Batasi upload maksimal 5 foto jika `package === 'free'`.
   - `love_story` (Cerita): Nonaktifkan (*disable*) field upload foto cerita jika `package === 'free'`.
   - `buku_tamu` (Ucapan): Sembunyikan tombol **Balas Pesan** jika `package === 'free'`.
