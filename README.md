# 📊 Stats Tracker - Sporta Statistikas Aplikācija

Pilna projekta dokumentācija

---

## 📋 Satura rādītājs

1. [Projekta Apraksts](#projekta-apraksts)
2. [Tehnoloģijas](#tehnoloģijas)
3. [Funkcionalitāte](#funkcionalitāte)
4. [Instalācija](#instalācija)
5. [Lietošana](#lietošana)
6. [Admin Panel](#admin-panel)
7. [API Endpoints](#api-endpoints)
8. [Datubāze](#datubāze)
9. [Problēmu Risināšana](#problēmu-risināšana)

---
<a id="projekta-apraksts"></a>

## 🎯 Projekta Apraksts

**Stats Tracker** - moderna web aplikācija sporta statistikas uzskaites un analīzei.

**Atbalsta 3 sporta veidus:**
- 🏀 Basketbols
- ⚽ Futbols  
- 🏐 Volejbols

---
<a id="tehnoloģijas"></a>

## 💻 Tehnoloģijas

- **Laravel 10** - Backend framework
- **Vue 3** - Frontend framework
- **Vuetify 3** - UI komponentes
- **MySQL** - Datubāze
- **Vite** - Build tool

---
<a id="funkcionalitāte"></a>

## ⚡ Funkcionalitāte

### Statistikas Pārvaldība
- ✅ Pievienot statistiku
- ✅ Skatīt visas statistikas
- ✅ Rediģēt statistiku
- ✅ Dzēst statistiku
- ✅ Filtrēt pēc sporta/datuma

### Datu Analīze
- 📊 Vidējie rādītāji
- 📈 Progresa tendences
- 🏆 Labākie sniegumi
- 📉 Grafiki un diagrammas

### Datu Eksports
- 📤 CSV eksports
- 📤 JSON eksports
- 🔍 Filtrēšana

### Admin Panel
- 👥 Lietotāju pārvaldība
- 🔑 Admin tiesības
- 📊 Sistēmas statistika

---
<a id="instalācija"></a>

## 🚀 Instalācija

### Priekšnosacījumi
```
PHP >= 8.1
Composer
Node.js >= 16
MySQL >= 5.7
```

### Soļi

```bash
# 1. Klonē projektu
git clone https://github.com/23DP2ASalk/my_website.git
cd my_website

# 2. Instalē
composer install
npm install

# 3. Konfigurē
cp .env.example .env
# Rediģē .env ar DB credentials

# 4. Setup
php artisan key:generate
php artisan migrate

# 5. Admin lietotājs
php artisan tinker
```

```php
$user = new \App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@admin.com';
$user->password = bcrypt('admin123');
$user->is_admin = true;
$user->save();
exit
```

```bash
# 6. Build & Run
npm run build
php artisan serve
```

**URL:** http://localhost:8000  
**Login:** admin@admin.com / admin123

---
<a id="lietošana"></a>

## 📖 Lietošana

### Pierakstīšanās
1. Atver /login
2. Ievadi email/password
3. Klikšķini Login

### Statistikas Pievienošana
1. Dashboard → "Pievienot Statistiku"
2. Izvēlies sporta veidu
3. Aizpildi datus
4. Saglabā

### Analīze
1. Izvēlne → "Datu analīze"
2. Skaties grafikus
3. Analizē tendences

### Eksports
1. Izvēlne → "Datu eksports"
2. Izvēlies formātu (CSV/JSON)
3. Filtrē (ja vajag)
4. Lejupielādē

---
<a id="admin-panel"></a>

## 🔐 Admin Panel

**URL:** /admin

### Funkcijas
- Lietotāju saraksts
- Piešķirt/noņemt admin
- Dzēst lietotājus
- Sistēmas statistika

### Piekļuve
Tikai lietotājiem ar `is_admin = true`

---
<a id="api-endpoints"></a>

## 🛣️ API Endpoints

### Statistikas Routes
```
GET    /statistics          - Visi ieraksti
GET    /statistics/create   - Create forma
POST   /statistics          - Izveidot jaunu
GET    /statistics/{id}/edit - Edit forma
PUT    /statistics/{id}     - Atjaunināt
DELETE /statistics/{id}     - Dzēst
```

### Analīze
```
GET /analytics - Analytics dashboard
```

### Eksports
```
GET /exports     - Export lapa
GET /exports/csv - Lejupielādēt CSV
GET /exports/json - Lejupielādēt JSON
```

### Admin
```
GET  /admin             - Admin dashboard
GET  /admin/users       - Lietotāju saraksts
POST /admin/users/{id}/toggle-admin
DELETE /admin/users/{id}
```

---
<a id="datubāze"></a>

## 🗄️ Datubāze

### Users
```sql
- id
- name
- email (unique)
- password (bcrypt)
- is_admin (boolean)
- role (user/admin)
- created_at
```

### Statistics
```sql
- id
- user_id (foreign key)
- sport_type (Basketball/Football/Volleyball)
- game_date
- minutes_played

Basketball:
- points, assists, rebounds, steals, blocks

Football:
- goals, assists_football, shots, passes, tackles

Volleyball:
- spikes, blocks_volleyball, serves, digs, aces

- notes (text)
- created_at, updated_at
```

---
<a id="problēmu-risināšana"></a>

## 🔧 Problēmu Risināšana

### "Class not found"
```bash
composer dump-autoload
```

### "CSRF token mismatch"
```bash
php artisan cache:clear
php artisan config:clear
```

### "Mix manifest not found"
```bash
npm run build
```

### "Tukša lapa"
```bash
# Pārbaudi Console (F12)
# Rebuild assets
npm run build
```

### "403 Unauthorized" admin panelī
```php
// Tinker:
$user = User::find(1);
$user->is_admin = true;
$user->save();
```

---

## 📁 Failu Struktūra

```
my_website/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/AdminController.php
│   │   ├── StatisticController.php
│   │   ├── AnalyticsController.php
│   │   └── ExportController.php
│   ├── Middleware/IsAdmin.php
│   └── Models/
│       ├── User.php
│       └── Statistic.php
├── database/migrations/
├── resources/
│   ├── js/
│   │   ├── app.js
│   │   └── components/
│   │       ├── Dashboard.vue
│   │       ├── StatisticsIndex.vue
│   │       ├── StatisticsCreate.vue
│   │       ├── StatisticsEdit.vue
│   │       ├── Analytics.vue
│   │       ├── Exports.vue
│   │       ├── AdminDashboard.vue
│   │       └── AdminUsers.vue
│   └── views/
│       ├── welcome.blade.php
│       ├── login.blade.php
│       ├── dashboard.blade.php
│       ├── statistics/
│       ├── analytics/
│       ├── exports/
│       └── admin/
├── routes/web.php
└── .env
```

---

## 👨‍💻 Autori

- **Andrejs Kristens Saļkovs** - 23DP2ASalk
- **Projekts:** Stats Tracker
- **Datums:** 2026

---

## 📄 Licence

Šis projekts ir izveidots izglītības nolūkiem.

---

## 🔗 Saites

- **GitHub:** https://github.com/23DP2ASalk/my_website
- **Laravel Docs:** https://laravel.com/docs
- **Vue Docs:** https://vuejs.org
- **Vuetify Docs:** https://vuetifyjs.com

---

**Paldies, ka izmanto Stats Tracker! 🎉**
