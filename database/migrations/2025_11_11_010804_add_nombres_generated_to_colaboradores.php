<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('colaboradores','nombres')) {
            try {
                Schema::table('colaboradores', function (Blueprint $table) {
                    $table->string('nombres', 201)
                        ->virtualAs("TRIM(CONCAT(COALESCE(nombre,''),' ',COALESCE(apellidos,'')))")
                        ->after('apellidos');
                    $table->index('nombres');
                });
            } catch (\Throwable $e) {
                DB::statement("
                    ALTER TABLE colaboradores
                    ADD COLUMN nombres VARCHAR(201)
                    GENERATED ALWAYS AS (TRIM(CONCAT(COALESCE(nombre,''),' ',COALESCE(apellidos,''))))
                    VIRTUAL
                ");
                DB::statement("CREATE INDEX idx_colaboradores_nombres ON colaboradores (nombres)");
            }
        }
    }
    public function down(): void {
        if (Schema::hasColumn('colaboradores','nombres')) {
            Schema::table('colaboradores', function (Blueprint $table) {
                $table->dropIndex(['nombres']);
                $table->dropColumn('nombres');
            });
        }
    }
};
