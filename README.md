# 🎮 Python Beginner Game — Laravel Backend

ระบบจัดการ Python Coding Game พร้อม 2 บทบาท: **Admin** และ **Player**

---

## 📦 โครงสร้าง Project

```
laravel-python-game/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/LoginController.php   ← Login / Logout
│   │   │   ├── AdminController.php        ← ดูสกอร์ผู้เล่น
│   │   │   └── GameController.php         ← เล่นเกม + บันทึกคะแนน
│   │   └── Middleware/
│   │       ├── Authenticate.php
│   │       └── RoleMiddleware.php         ← แยก admin / player
│   └── Models/
│       ├── User.php                       ← role: admin | player
│       └── GameScore.php                  ← คะแนน, เวลา, ด่าน
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_sessions_table.php
│   │   └── ..._create_game_scores_table.php
│   └── seeders/DatabaseSeeder.php         ← บัญชีทดสอบ
├── resources/views/
│   ├── layouts/app.blade.php              ← Layout หลัก
│   ├── auth/login.blade.php               ← หน้า Login
│   ├── admin/
│   │   ├── dashboard.blade.php            ← Admin Dashboard
│   │   └── player_detail.blade.php        ← รายละเอียดผู้เล่น
│   └── game/
│       ├── index.blade.php                ← หน้าเล่นเกม
│       ├── history.blade.php              ← ประวัติคะแนน
│       └── complete.blade.php             ← หน้าผ่านครบ
├── public/game/
│   └── python-game.html                   ← เกม (มี postMessage)
├── routes/web.php
├── .env.example
└── setup.sh                               ← Script ติดตั้ง
```

---

## 🚀 วิธีติดตั้ง

### 1. ติดตั้ง Dependencies

```bash
composer install
```

### 2. ตั้งค่า Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 3. สร้าง Database (SQLite)

```bash
touch database/database.sqlite
php artisan migrate --seed
```

> ใช้ MySQL แทน? แก้ `DB_CONNECTION=mysql` ใน `.env` แล้ว `php artisan migrate --seed`

### 4. รัน Server

```bash
php artisan serve
```

เปิดเบราว์เซอร์: **http://localhost:8000**

---

## 👤 บัญชีทดสอบ

| บทบาท | Email | Password |
|-------|-------|----------|
| **Admin** | admin@pythongame.com | admin1234 |
| **Player** | player@demo.com | player1234 |
| Player (อื่น) | somchai@demo.com | player1234 |
| Player (อื่น) | somying@demo.com | player1234 |

---

## 🎮 ฟีเจอร์ Admin

| ฟีเจอร์ | URL |
|---------|-----|
| Dashboard + สถิติรวม | `/admin/dashboard` |
| รายละเอียดผู้เล่น | `/admin/player/{id}` |
| ลบคะแนนรายการเดียว | DELETE `/admin/score/{id}` |
| Reset คะแนนทั้งหมดของผู้เล่น | DELETE `/admin/player/{id}/reset` |
| Export CSV | `/admin/export` |

## 🎯 ฟีเจอร์ Player

| ฟีเจอร์ | URL |
|---------|-----|
| เล่นเกม | `/game` |
| บันทึกคะแนน (AJAX) | POST `/game/score` |
| ประวัติคะแนน | `/game/history` |
| หน้าสำเร็จ | `/game/complete` |

---

## 📊 Database Schema

### `users`
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| name | string | ชื่อผู้ใช้ |
| email | string (unique) | อีเมล |
| password | string | รหัสผ่าน (hashed) |
| role | enum: admin, player | บทบาท |

### `game_scores`
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| user_id | bigint (FK) | ผู้เล่น |
| score | tinyint 0-100 | คะแนนรวม |
| levels_completed | tinyint 0-6 | จำนวนด่านที่ผ่าน |
| level_scores | json | คะแนนแต่ละด่าน `[d1,d2,...,d6]` |
| time_spent_seconds | int | เวลารวม (วินาที) |
| level_times | json | เวลาแต่ละด่าน (วินาที) |
| hints_used | smallint | จำนวนคำใบ้ที่ใช้ |
| completed_at | timestamp? | เวลาผ่านครบ (null ถ้ายังไม่ครบ) |

---

## 🔌 การเชื่อมต่อกับ python.html

ไฟล์ `public/game/python-game.html` ใช้ `postMessage` ส่งข้อมูลคะแนนมายัง Laravel:

```javascript
// ส่งจากเกม (iframe)
window.parent.postMessage({
  type: 'SAVE_SCORE',
  payload: {
    score, levels_completed, level_scores,
    time_spent_seconds, level_times, hints_used
  }
}, '*');

// Laravel รับแล้วส่ง ack กลับ
window.postMessage({ type: 'SCORE_SAVED', grade: 'A' }, '*');
```

---

## 📝 หมายเหตุ

- เกมใช้ **Skulpt.js** รัน Python ใน browser (ไม่ต้องติดตั้ง Python ฝั่ง server)
- รองรับ PHP 8.1+, Laravel 10/11
- ใช้ SQLite เป็น default (ไม่ต้องตั้งค่า database server)
