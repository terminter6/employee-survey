<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `questions` MODIFY COLUMN `type` ENUM('scale', 'text', 'single_choice', 'multiple_choice') NOT NULL");
        } else {
            // Для SQLite: пересоздаём таблицу с новым enum
            Schema::table('questions', function (Blueprint $table) {
                $table->string('type_temp')->nullable();
            });

            DB::table('questions')->update(['type_temp' => DB::raw('type')]);

            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('type');
                $table->renameColumn('type_temp', 'type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `questions` MODIFY COLUMN `type` ENUM('scale', 'text') NOT NULL");
        } else {
            // Для SQLite просто оставляем как есть
        }
    }
};
