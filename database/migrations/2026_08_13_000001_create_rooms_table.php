<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('ชื่อห้อง เช่น ม.2/3');
            $table->string('school_name')->comment('ชื่อโรงเรียน');
            $table->string('code', 8)->unique()->comment('รหัสห้อง 6-8 ตัวอักษร');
            $table->boolean('is_active')->default(true)->comment('เปิด/ปิดการใช้งาน');
            $table->timestamp('expires_at')->nullable()->comment('วันหมดอายุ null = ไม่มีกำหนด');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['code', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
