<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->string('role')->default('customer')); // customer | staff | admin
        Schema::create('products', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('category'); $t->decimal('price', 8, 2);
            $t->string('image')->nullable(); $t->text('description')->nullable(); $t->timestamps();
        });
        Schema::create('variants', function (Blueprint $t) {
            $t->id(); $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('size'); $t->string('color')->default('Classic');
            $t->unsignedInteger('stock')->default(0); $t->unsignedInteger('low_stock_at')->default(3); $t->timestamps();
        });
        Schema::create('orders', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->constrained(); $t->string('code')->unique();
            $t->date('pickup_date'); $t->string('pickup_slot');
            $t->string('status')->default('reserved'); // reserved | ready | collected | cancelled
            $t->decimal('total', 8, 2)->default(0); $t->timestamps();
        });
        Schema::create('order_items', function (Blueprint $t) {
            $t->id(); $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('variant_id')->constrained(); $t->unsignedInteger('qty'); $t->decimal('price', 8, 2);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('order_items'); Schema::dropIfExists('orders');
        Schema::dropIfExists('variants'); Schema::dropIfExists('products');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
