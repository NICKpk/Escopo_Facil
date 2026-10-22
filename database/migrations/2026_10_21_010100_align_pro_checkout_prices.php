<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plans')->where('slug', 'pro')->update(['price_monthly' => 27.90, 'price_yearly' => 238.80]);
    }

    public function down(): void
    {
        // Pricing is business data; rolling back schema must not rewrite it.
    }
};
