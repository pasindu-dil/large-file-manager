<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cell_sites', function (Blueprint $table) {
            $table->id();

            // Primary identifiers
            $table->string('lac_ci');           // e.g. 26003_13734
            $table->string('old_sic')->nullable();           // e.g. WA-GMP-0286
            $table->string('new_site_name');                // e.g. Galborella-1
            $table->string('new_sic');                       // e.g. GAM401

            // Location
            $table->string('district');
            $table->string('province');
            $table->decimal('lon', 10, 8);
            $table->decimal('lat', 10, 8);
            $table->smallInteger('azimuth');               // 0–360 degrees

            // Cell identifiers
            $table->string('cell_name');                     // e.g. Galborella-1_G1-091
            $table->unsignedInteger('ci');               // Cell Identity
            $table->unsignedInteger('lac');               // Location Area Code
            $table->unsignedSmallInteger('rac_tac');      // RAC / TAC

            // Network equipment
            $table->unsignedSmallInteger('bsc_rnc_mme_id')->nullable();
            $table->string('cluster_id')->nullable();
            $table->string('technology', 4);              // 2G / 3G / 4G / 5G
            $table->string('vendor', 20);

            // Sector info
            $table->unsignedTinyInteger('sector_number');  // ID column (1,2,3)
            $table->string('sector_id');                    // e.g. GAM401_1

            // Admin / ownership
            $table->string('cluster_owner')->nullable();
            $table->string('mkt_priority')->nullable();      // Cat 1 / Cat 2 / Cat 3
            $table->string('fivegb_category')->nullable();  // 5GB category
            $table->string('ds_division')->nullable();       // e.g. Kelaniya

            // Status
            $table->date('last_available')->nullable();
            $table->string('status', 20)->default('onair');

            $table->timestamps();

            // Indexes for common queries
            $table->index('lac');
            $table->index('ci');
            $table->index('new_sic');
            $table->index(['lac', 'ci']);
            $table->index('technology');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cell_sites');
    }
};
