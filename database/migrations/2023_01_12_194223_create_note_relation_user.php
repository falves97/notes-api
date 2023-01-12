<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if ( Schema::hasTable( 'users' ) && Schema::hasTable( 'notes' ) ) {
            Schema::table( 'notes', function ( Blueprint $table ) {
                $table->foreignIdFor( User::class )
                      ->constrained()
                      ->onUpdate( 'cascade' )
                      ->onDelete( 'cascade' );
            } );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if ( Schema::hasTable( 'notes' ) ) {
            Schema::table( 'notes', function ( Blueprint $table ) {
                $table->dropForeignIdFor( User::class );
            } );
        }
    }
};
