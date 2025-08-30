<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



# 🏘️ Sistem Direktori Komuniti Perumahan

Sistem ini dibangunkan untuk komuniti perumahan bagi memudahkan ahli mencari kedai, servis, dan informasi dalam kawasan perumahan mereka.  
Ia juga menyediakan fungsi interaksi sosial, laporan masalah komuniti, dan ruang untuk peniaga mempromosikan servis mereka.

---

## 💡 Cadangan Penambahbaikan Ciri

- **Integrasi Pembayaran Online**  
	Untuk peniaga yang ingin menawarkan tempahan atau pembelian terus melalui platform.

- **Sistem Penjadualan Temujanji**  
	Ahli komuniti boleh menempah slot servis (cth: gunting rambut, servis rumah) secara terus.

- **Sistem Penghantaran/Delivery**  
	Untuk kedai yang menawarkan penghantaran dalam kawasan komuniti.

- **Sistem Pengesahan Identiti**  
	Pengesahan identiti pengguna/peniaga untuk keselamatan komuniti.

- **Sistem Ganjaran/Loyalty**  
	Ahli komuniti dapat mata ganjaran untuk interaksi aktif (review, laporan, promosi).

- **Integrasi dengan WhatsApp API**  
	Untuk komunikasi automatik atau broadcast pengumuman.

- **Sistem Polling/Undian Komuniti**  
	Untuk keputusan bersama (cth: undi aktiviti, cadangan penambahbaikan kawasan).


## ✨ Ciri-Ciri Utama

### 👤 Untuk Pengguna (Ahli Komuniti)
- Pendaftaran & Login (Email/Telefon + Password).
- Cari kedai/servis berdasarkan nama, kategori, rating, lokasi.
- Peta interaktif (pin lokasi kedai).
- Profil kedai: gambar, info perniagaan, menu/servis, waktu operasi, contact (WhatsApp/Call).
- Rating & Review (⭐⭐⭐⭐⭐ + komen).
- Simpan kedai kegemaran.
- Forum/Chat ringkas untuk perbincangan komuniti.
- Event & Notis komuniti.
- Hantar laporan masalah (lampu jalan rosak, kebersihan, keselamatan).
- Notifikasi push/email untuk promosi & pengumuman.

### 🏪 Untuk Peniaga / Penyedia Servis
- Pendaftaran kedai/servis mudah (nama, kategori, gambar, lokasi, contact).
- Dashboard untuk edit profil kedai & update info.
- Statistik klik & review pelanggan.
- Buat promosi khas komuniti.
- Opsyen kedai featured (untuk lebih highlight).

### 🛡️ Untuk Admin
- Dashboard admin untuk urus user, kedai, review.
- Approve / reject pendaftaran kedai.
- Manage event & notis komuniti.
- Moderasi forum & laporan.
- Analitik pengguna dan kedai.

---

## 🔄 Flow Sistem

1. User daftar/login → masuk Dashboard.
2. User cari kedai/servis → filter → klik kedai.
3. User lihat profil kedai → contact/WhatsApp → tinggalkan review.
4. Peniaga daftar kedai → isi borang → tunggu admin approve.
5. Peniaga update info/promosi → kedai muncul dalam direktori.
6. User interaksi sosial → forum/chat, event, notis.
7. User buat laporan masalah → admin semak → assign tindakan.
8. Admin urus & pantau keseluruhan sistem.

---

## 🗄️ ERD (Entity Relationship Diagram)



---

## 📋 Struktur Database (Table)

### 1. users
- id (PK)
- name
- email
- phone
- password
- address
- role (enum: user, vendor, admin)
- created_at, updated_at

### 2. shops
- id (PK)
- user_id (FK → users.id)
- name
- category
- description
- phone
- whatsapp_link
- address
- latitude, longitude
- opening_hours
- status (active/pending/rejected)
- created_at, updated_at

### 3. reviews
- id (PK)
- shop_id (FK → shops.id)
- user_id (FK → users.id)
- rating (1–5)
- comment
- created_at

### 4. promotions
- id (PK)
- shop_id (FK → shops.id)
- title
- description
- start_date, end_date
- is_featured (boolean)
- created_at, updated_at

### 5. events
- id (PK)
- user_id (FK → users.id)
- title
- description
- date
- location
- type (enum: event, notis)
- created_at, updated_at

### 6. forum_posts
- id (PK)
- user_id (FK → users.id)
- title
- content
- created_at, updated_at

### 7. forum_replies
- id (PK)
- post_id (FK → forum_posts.id)
- user_id (FK → users.id)
- content
- created_at, updated_at

### 8. reports
- id (PK)
- user_id (FK → users.id)
- category
- description
- location
- image
- status (open, in_progress, resolved)
- created_at, updated_at

---

## 🔑 Hubungan (Relationship)
- 1 User boleh ada banyak Shops.
- 1 Shop boleh ada banyak Reviews & Promotions.
- 1 User boleh buat banyak Events.
- 1 ForumPost boleh ada banyak ForumReplies.
- 1 User boleh submit banyak Reports.

---

## 🚀 Next Step untuk Laravel
1. Buat migration untuk semua table.
2. Buat model Eloquent (`User`, `Shop`, `Review`, `Promotion`, `Event`, `ForumPost`, `ForumReply`, `Report`).
3. Set relationship dalam model.
4. Buat seeder data contoh untuk testing.
5. Bangunkan route, controller, dan view ikut flow sistem di atas.
#   p u n c a k - j a l i l  
 