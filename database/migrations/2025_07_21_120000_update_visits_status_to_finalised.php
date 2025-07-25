<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite (which doesn't support altering enum columns)
        if (DB::getDriverName() === 'sqlite') {
            // SQLite requires creating a new table and copying data
            Schema::table('visits', function (Blueprint $table) {
                $table->string('status_temp')->default('draft');
            });

            // Update existing values
            DB::table('visits')
                ->where('status', 'finalized')
                ->update(['status_temp' => 'finalised']);

            DB::table('visits')
                ->where('status', '!=', 'finalized')
                ->update(['status_temp' => DB::raw('status')]);

            // Drop old column and rename new one
            Schema::table('visits', function (Blueprint $table) {
                $table->dropColumn('status');
                $table->renameColumn('status_temp', 'status');
            });
        }
        // For MySQL
        else {
            DB::statement("ALTER TABLE visits MODIFY COLUMN status ENUM('draft', 'pending_review', 'approved', 'finalised') NOT NULL DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('visits', function (Blueprint $table) {
                $table->string('status_temp')->default('draft');
            });

            DB::table('visits')
                ->where('status', 'finalised')
                ->update(['status_temp' => 'finalized']);

            DB::table('visits')
                ->where('status', '!=', 'finalised')
                ->update(['status_temp' => DB::raw('status')]);

            Schema::table('visits', function (Blueprint $table) {
                $table->dropColumn('status');
                $table->renameColumn('status_temp', 'status');
            });
        } else {
            DB::statement("ALTER TABLE visits MODIFY COLUMN status ENUM('draft', 'pending_review', 'approved', 'finalized') NOT NULL DEFAULT 'draft'");
        }
    }
};
