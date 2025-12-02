# 🌐 Temploka  
Platform ERP + CRM Builder Berbasis Template  
_Didesain seperti gabungan Odoo + Canva._

Temploka adalah platform untuk membuat sistem bisnis (ERP, CRM, Integrasi Marketplace, dan modul bisnis lainnya) dengan **template siap pakai**. Pengguna dapat mengedit, menyesuaikan, dan mempublikasi modul bisnis mereka dengan mudah — tanpa coding.

Konsep utamanya:
- Seperti **Odoo**, namun **tanpa perlu membangun modul dari nol**  
- Seperti **Canva**, pengguna bisa **edit dashboard, modul, workflow**, dan **bayar hanya ketika fitur premium dipakai**

---

## ✨ Fitur Utama

### 🔹 **Template-based ERP & CRM**
Pengguna dapat memilih template sistem bisnis siap pakai, mengeditnya, lalu menggunakan atau mempublikasikannya.

### 🔹 **Modular System (ERP / CRM / Integrasi Marketplace / Produk)**
Pengguna bebas mengaktifkan modul yang mereka inginkan:
- Manajemen Produk  
- CRM  
- ERP  
- Integrasi marketplace (Tokopedia, Shopee — planned)  
- Workshop & editor builder  

### 🔹 **Freemium Model**
- 1 modul → gratis  
- Paket lengkap → bayar  
- Sistem pembayaran sudah built-in

### 🔹 **Powerful Editor**
Mirip Canva, tetapi untuk dashboard bisnis:
- Drag & drop  
- Custom template  
- Publish template  
- Preview publik  

### 🔹 **Admin Panel (Halaman Terpisah)**
Admin dapat:
- Mengelola template global  
- Upload template via ZIP  
- Kelola user  
- Kelola pembayaran  

---

## 📁 Struktur Proyek

Proyek dibangun di atas:

- **Laravel 12 (PHP 8.2+)**
- **Blade + Alpine.js**
- **TailwindCSS**
- **Vite Build System**
- **Chart.js** untuk grafik
- **Axios** untuk API request
- Sistem editor custom

---



2. Install Dependencies Backend
composer install

3. Copy File Environment
cp .env.example .env

4. Generate App Key
php artisan key:generate

5. Setup Database

Edit file .env:

DB_DATABASE=temploka
DB_USERNAME=root
DB_PASSWORD=yourpassword


Lalu jalankan migrasi:

php artisan migrate

6. Install Dependencies Frontend
npm install

7. Build Assets
npm run build

8. (Opsional) Jalankan Mode Pengembangan

Menjalankan Laravel + Vite + Queue + Logging bersamaan:

composer dev


Atau manual:

php artisan serve
npm run dev

📦 Script Penting
Composer Scripts

composer setup → instalasi cepat (backend + frontend)

composer dev → menjalankan development env lengkap

composer test → menjalankan unit tests

NPM Scripts

npm run dev → Vite dev server

npm run build → compile assets

🛣️ Routing (Ringkasan)
🔹 Public Routes

Halaman Home

List template

Detail template

Kategori

Pricing

Dokumentasi

Support

🔹 Auth Routes

Laravel Breeze

Login / Register

🔹 Onboarding

3 langkah onboarding sebelum masuk dashboard.

🔹 Dashboard (User)

Templates

Modules

Integrations

Workshop (Builder)

Settings

Profile

🔹 Editor

Edit template

Save / Publish / Reset / Duplicate

Upload image

View published

🔹 Payment

Checkout

Upload bukti pembayaran

Success page

🔹 Admin

CRUD Template

Upload template ZIP

🧱 Teknologi & Dependency
composer.json (ringkasan)

Laravel 12

Laravel Breeze

Pint (formatter)

PHPUnit

Sail

Collision (error handler)

package.json (ringkasan)

TailwindCSS

Vite

Alpine.js

Chart.js

Axios

🗺️ Roadmap (Future Features)

Integrasi marketplace: Shopee, Tokopedia, Lazada

Drag & drop builder full visual

Export project sebagai ZIP

REST API untuk modul custom

Subscription recurring

🧑‍💻 Kontribusi

Pull request dipersilakan!
Ikuti format PSR-12 & Laravel Pint.

📝 Lisensi

MIT License.

❤️ Terima Kasih

Proyek ini dibuat untuk memberikan kemudahan bagi siapa saja yang ingin membuat sistem bisnis modern tanpa coding — dengan cara seintuitif Canva dan se-powerful Odoo.
   
