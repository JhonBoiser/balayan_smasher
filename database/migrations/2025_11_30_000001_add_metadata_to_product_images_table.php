<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_images', function (Blueprint $table) {
            // Add new columns to store image metadata
            $table->string('alt_text')->nullable()->after('image_path');
            $table->string('original_filename')->nullable()->after('alt_text');
        });
    }

    public function down()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn(['alt_text', 'original_filename']);
        });
    }
};
