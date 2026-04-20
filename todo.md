Saya memiliki project Laravel 13 + Filament 5 yang SUDAH hampir selesai. Saya ingin kamu MEMPELAJARI seluruh alur project saya terlebih dahulu, memahami database, status transaksi, relasi model, logika tiket, dan sistem yang sudah berjalan. Setelah itu baru tambahkan fitur terakhir yaitu Scan QR Check-In Admin.

Jangan asal generate code. Pahami dulu konteks project agar fitur baru nyambung dengan sistem lama.

====================================================
PROJECT SAYA
====================================================

Nama Project:
Sistem Penjualan Tiket Event Berbasis Web

Framework:
- Laravel 13
- Filament 5 (admin panel)
- MySQL

====================================================
FITUR YANG SUDAH JADI
====================================================

1. AUTH LOGIN
- Login user
- Login admin
- Role admin / user

====================================================

2. ADMIN PANEL FILAMENT SUDAH ADA

Resource:

- Users
- Venues
- Events
- Tikets
- Vouchers
- Orders
- Attendes
- Voucher Usages

====================================================

3. USER SIDE SUDAH ADA

User bisa:

- lihat daftar event
- search event
- lihat detail event
- pilih tiket
- beli tiket
- pakai voucher
- checkout
- lihat riwayat order
- lihat tiket
- tiket menampilkan QR Code realtime

====================================================

4. SISTEM ORDER SUDAH JALAN

Flow:

User pilih tiket
-> qty
-> checkout

Saat checkout:

stok tiket langsung berkurang

Order dibuat dengan status:

pending

expired_at = 24 jam

Jika user klik bayar:

status jadi paid

Jika admin approve:

status jadi confirm

Jika pending melewati expired:

status cancel

Jika admin cancel:

status cancel

====================================================

5. SAAT ORDER CONFIRM

Sistem otomatis generate data attendees sesuai qty tiket.

Contoh:

qty beli = 3

Maka buat 3 row attendes.

====================================================

DATABASE attendes
====================================================

Table: attendes

Field:

- id_attendes
- id_detail
- kode_tiket
- status (belum,sudah)
- waktu_checkin

====================================================

6. QR CODE SUDAH ADA

QR ditampilkan realtime di halaman tiket user.

Isi QR sekarang berupa:

kode_tiket

contoh:

6SMZEKTFBF

====================================================
DATABASE RELASI
====================================================

users
-> hasMany orders

venues
-> hasMany events

events
-> belongsTo venue
-> hasMany tikets

tikets
-> belongsTo events

orders
-> belongsTo users
-> hasMany order_details

order_details
-> belongsTo orders
-> belongsTo tikets
-> hasMany attendes

attendes
-> belongsTo order_details

vouchers
-> hasMany voucher_usages

====================================================
STATUS YANG DIPAKAI
====================================================

orders.status:

- pending
- paid
- confirm
- cancel

attendes.status:

- belum
- sudah

====================================================
TUJUAN SEKARANG
====================================================

Saya hanya ingin menambahkan SATU fitur terakhir:

SCAN QR CHECK-IN UNTUK ADMIN

====================================================
FITUR YANG DIINGINKAN
====================================================

Di sidebar Filament tambahkan menu baru:

Transactions
-> Scan Tiket

Saat admin buka halaman itu:

kamera aktif otomatis.

Gunakan library scan QR kamera browser.

Contoh:

html5-qrcode

====================================================
ALUR SCAN
====================================================

Admin scan QR user.

QR berisi kode_tiket.

Sistem cari ke tabel attendes:

where kode_tiket = hasil scan

====================================================

KONDISI 1:

Jika tiket tidak ditemukan:

Notif merah:

"Tiket tidak ditemukan"

====================================================

KONDISI 2:

Jika status = sudah

Notif kuning:

"Tiket sudah digunakan"

====================================================

KONDISI 3:

Jika status = belum

Update:

status = sudah
waktu_checkin = now()

Notif hijau:

"Check-in berhasil"

====================================================

SETELAH BERHASIL TAMPILKAN
====================================================

- kode_tiket
- nama event
- nama tiket
- tanggal event
- waktu checkin

Relasi ambil dari:

attendes
-> orderDetail
-> tiket
-> event

====================================================
YANG HARUS DIBUAT
====================================================

1. Custom Filament Page:

ScanTicket.php

2. Blade view scanner

3. Register navigation sidebar

4. AJAX / Livewire / method scan process

5. Notifikasi Filament modern

====================================================
PENTING
====================================================

- jangan ubah sistem lama
- jangan rusak resource lama
- hanya tambah fitur scan
- gunakan code clean
- Laravel 13
- Filament 5
- siap copy paste

====================================================
OUTPUT
====================================================

Berikan:

1. Penjelasan singkat bahwa kamu paham alur project saya
2. Struktur implementasi scan
3. File lengkap ScanTicket.php
4. Blade view scanner
5. Semua code final siap pakai