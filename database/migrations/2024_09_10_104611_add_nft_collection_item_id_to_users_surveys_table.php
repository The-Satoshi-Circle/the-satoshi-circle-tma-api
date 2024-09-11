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
        Schema::table('users_surveys', static function (Blueprint $table) {
            $table->unsignedBigInteger('nft_collection_item_id')->nullable()->default(null)->after('data');
            $table->foreign('nft_collection_item_id')->references('id')->on('nft_collection_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_surveys', function (Blueprint $table) {
            $table->dropForeign('users_surveys_nft_collection_item_id_foreign');
            $table->dropColumn('nft_collection_item_id');
        });
    }
};
