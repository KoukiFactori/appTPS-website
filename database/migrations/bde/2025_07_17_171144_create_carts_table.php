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

                $table->unsignedBigInteger('member_id')->nullable();
                $table
                    ->foreign('member_id')
                    ->references('id')
                    ->on('members')
                    ->nullOnDelete();
            }
        );

        Schema::connection('bde_bdd')->table(
            'orders',
            function (Blueprint $table) {
                $table->unsignedBigInteger('cart_id')->nullable();
                $table
                    ->foreign('cart_id')
                    ->references('id')
                    ->on('carts')
                    ->cascadeOnDelete();
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
