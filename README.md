# 🌐 Temploka  
_SaaS ERP + CRM Builder Berbasis Template — Gabungan konsep Odoo + Canva._

Temploka adalah platform yang memungkinkan pengguna membuat sistem bisnis (ERP, CRM, integrasi marketplace, manajemen produk, dll.) menggunakan **template siap pakai**.  
Tidak seperti Odoo yang harus membangun modul dari nol, Temploka menawarkan **template editor visual** seperti Canva — sehingga pengguna dapat mengedit sistem mereka dengan bebas, lalu mempublikasikannya.

---

## ✨ Fitur Utama

### 🎨 Template-Based ERP Builder
- Pilih template sistem bisnis
- Edit tampilan, modul, workflow
- Simpan, publish, dan preview online

### 🧩 Modular System
Pengguna dapat mengaktifkan modul:
- CRM  
- ERP  
- Integrasi Marketplace  
- Manajemen Produk  
- Workshop Builder  
- Module Manager  

### 💸 Freemium Model
- 1 modul gratis  
- Semua modul / full fitur berbayar  
- Sistem checkout + upload bukti sudah tersedia

### 🧑‍💻 Admin Panel Khusus
Admin (pemilik Temploka) dapat:
- Upload template via ZIP  
- Kelola modul global  
- Kelola user  
- Kelola pembayaran  

### ⚡ Power Features
- Editor drag & drop (custom builder)
- Public template preview
- User onboarding (3 step)
- Publish template ke sub-URL

---

## 📁 Teknologi Utama

**Backend**
- Laravel 12  
- PHP 8.2+  
- Blade Templates  
- Middleware Custom (CheckTemplateAccess, auth, admin)

**Frontend**
- TailwindCSS  
- Alpine.js  
- Axios  
- Chart.js  
- Vite Bundler

**Tools**
- Laravel Breeze (auth)
- Laravel Pint (formatter)
- PHPUnit

---

📦 Instalasi & Setup

1️⃣ Clone Repository
```bash
git clone https://github.com/rickysilaban15/temploka.git
cd temploka
2️⃣ Install Backend Dependencies
composer install

3️⃣ Copy & Setup Environment
cp .env.example .env


Edit .env:

DB_DATABASE=temploka
DB_USERNAME=root
DB_PASSWORD=yourpassword

4️⃣ Generate Key
php artisan key:generate

5️⃣ Migrasi Database
php artisan migrate

6️⃣ Install Frontend Dependencies
npm install

7️⃣ Build Frontend
npm run build

8️⃣ Jalankan Mode Development

Mode development lengkap (Laravel + Queue + Logs + Vite):

composer dev


Atau manual:

php artisan serve
npm run dev




📜 Script Penting (Composer & NPM)
Composer

composer setup → instalasi otomatis (backend + frontend)

composer dev → environment dev lengkap

composer test → menjalankan unit test

NPM

npm run dev → Vite development

npm run build → compile assets

🛣️ Struktur Routing
🔓 Public

/ Home

/templates

/categories

/harga

/pusat-bantuan

/dokumentasi

/tutorial

🔐 Auth

Login / Register via Laravel Breeze

🧭 Onboarding

Pengguna baru melalui 3 step onboarding wajib.

📊 Dashboard (Customer)

Dashboard overview

Templates

Modules

Integrations

Workshop

Settings

Profile

📝 Editor

Edit template

Save

Publish

Reset

Duplicate

Upload image

Get content API

View published template

🧾 Payment

Checkout

Process Payment

Upload bukti

Payment success

🛠 Admin

CRUD Template

Upload template ZIP

🧱 Dependensi Utama
package.json
- TailwindCSS
- Vite
- Alpine.js
- Chart.js
- Axios

composer.json
- Laravel 12
- Laravel Breeze
- Laravel Pint
- PHPUnit
- Sail

🗺 Roadmap Pengembangan

Integrasi marketplace: Tokopedia, Shopee, Lazada

Builder drag & drop full visual

Export project ke ZIP

API akses modul custom

Sistem subscription otomatis

🤝 Kontribusi

Pull request dipersilakan!
Gunakan format PSR-12 + Laravel Pint.

📄 Lisensi

MIT License.

❤️ Tentang Temploka

Temploka dibangun untuk mempermudah UMKM, creator, dan pelaku bisnis membuat sistem ERP modern tanpa harus memiliki pengetahuan teknis.
Cepat, fleksibel, dan dapat dikustomisasi — seperti Canva, namun untuk sistem bisnis.
