<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('bde_bdd')->create(
            'carts',
            function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->float('price');
                $table->enum(
                    'status',
                    allowed: ['waiting', 'payed']
                );
                $table
                    ->foreign('member_id')
                    ->references('bde_id')
                    ->on('members')
                    ->onDelete('set null');
            }
        );

        Schema::connection('bde_bdd')->table(
            'orders',
            function (Blueprint $table) {
                $table
                    ->foreign('order_id')
                    ->references('id')
                    ->on('carts')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('bde_bdd')->table(
            'orders',
            function (Blueprint $table) {
                $table->removeColumn('order_id');
            }
        );

        Schema::dropIfExists('carts');
    }
};
