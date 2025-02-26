<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\password;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Customers', function (Blueprint $table){
            $table->string('id', 10)->primary();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 10)->nullable();
            $table->timestamps();
        });
        Schema::create('Vehicles', function (Blueprint $table){
            $table->id();
            $table->string('plate');
            $table->string('model');
            $table->string('make');
            $table->string('customer_id', 10);
            // Clave foránea que referencia a la tabla products
            $table->foreign('customer_id')->references('id')->on('Customers')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('Categories', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('Products', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->integer('price')->unsigned();
            $table->unsignedBigInteger('category_id');
            // Clave foránea que referencia a la tabla products
            $table->foreign('category_id')->references('id')->on('Categories')->onDelete('cascade');
            $table->timestamps();

        });
        Schema::create('Inventory', function (Blueprint $table){
            $table->id();
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('product_id');
            // Clave foránea que referencia a la tabla products
            $table->foreign('product_id')->references('id')->on('Products')->onDelete('cascade');
            $table->timestamps();   
        });
        Schema::create('Inventory_movements', function (Blueprint $table){
            $table->id(); 
            $table->unsignedBigInteger('inventory_id');
            $table->enum('movement_type', ['incoming', 'outgoing']); 
            $table->integer('quantity'); 
            $table->dateTime('movement_date')->useCurrent(); 
            $table->string('reason')->nullable(); 
            // Clave foránea que referencia a la tabla products
            $table->foreign('inventory_id')->references('id')->on('Inventory')->onDelete('cascade');
            $table->timestamps();
        
        });
        Schema::create('Sales', function (Blueprint $table){
            $table->id();  // Crea la columna 'id' como clave primaria
            $table->string('customer_id', 10);  // Relacionado con el cliente
            $table->dateTime('sale_date')->useCurrent();  // Fecha de la venta
            $table->unsignedInteger('total');  // Total de la venta
            $table->string('payment_method', 50)->nullable();  // Método de pago
            $table->foreign('customer_id')->references('id')->on('Customers')->onDelete('cascade');
            $table->timestamps(); 
           
        });
        Schema::create('Sale_details', function (Blueprint $table){
            $table->id();  // Crea la columna 'id' como clave primaria
            $table->unsignedBigInteger('sale_id');  // Relacionado con la venta
            $table->unsignedBigInteger('product_id');  // Relacionado con el producto
            $table->integer('quantity');  // Cantidad vendida
            $table->unsignedInteger('priceTotal');
            $table->foreign('sale_id')->references('id')->on('Sales')->onDelete('cascade');  // Relación con la tabla 'sales'
            $table->foreign('product_id')->references('id')->on('Products')->onDelete('cascade');  // Relación con la tabla 'products'
            $table->timestamps();

        });
        Schema::create('Invoices', function (Blueprint $table){
            $table->id();  // Crea la columna 'id' como clave primaria
            $table->unsignedBigInteger('sale_id');  // Relacionado con la venta
            $table->string('invoice_number', 50);  // Número de factura
            $table->dateTime('invoice_date')->useCurrent();  // Fecha de la factura
            $table->decimal('total', 10, 2);  // Total de la factura
            $table->foreign('sale_id')->references('id')->on('Sales')->onDelete('cascade');  // Relación con la tabla 'sales'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Invoices');
        Schema::dropIfExists('Sale_details');
        Schema::dropIfExists('Sales');
        Schema::dropIfExists('Inventory_movements');
        Schema::dropIfExists('Inventory');
        Schema::dropIfExists('Products');
        Schema::dropIfExists('Categories');
        Schema::dropIfExists('Vehicles');
        Schema::dropIfExists('Customers');
        Schema::dropIfExists('Users');
    }
};
