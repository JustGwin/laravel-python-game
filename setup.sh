#!/bin/bash
# ─── setup.sh ──────────────────────────────────────────────────────────────
# Script ติดตั้ง Python Beginner Game Laravel project
# ใช้: bash setup.sh
# ───────────────────────────────────────────────────────────────────────────

set -e

echo "🎮 Python Beginner Game — Laravel Setup"
echo "========================================"

# 1. ติดตั้ง dependencies
echo ""
echo "📦 [1/5] ติดตั้ง Composer dependencies..."
composer install --no-interaction --prefer-dist

# 2. สร้าง .env
echo ""
echo "⚙️  [2/5] ตั้งค่า .env..."
if [ ! -f .env ]; then
    cp .env.example .env
fi

# 3. Generate app key
echo ""
echo "🔑 [3/5] สร้าง App Key..."
php artisan key:generate

# 4. สร้าง SQLite database + migrate + seed
echo ""
echo "🗄️  [4/5] สร้างฐานข้อมูลและ seed..."
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi
php artisan migrate --seed --force

# 5. สร้าง storage link
echo ""
echo "🔗 [5/5] สร้าง storage link..."
php artisan storage:link 2>/dev/null || true

echo ""
echo "✅ ติดตั้งสำเร็จ!"
echo ""
echo "📋 บัญชีทดสอบ:"
echo "   Admin:  admin@pythongame.com  /  admin1234"
echo "   Player: player@demo.com       /  player1234"
echo ""
echo "🚀 รัน server ด้วย:"
echo "   php artisan serve"
echo ""
echo "🌐 เปิดเบราว์เซอร์ที่: http://localhost:8000"
