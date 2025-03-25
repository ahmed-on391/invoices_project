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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');//الخاص بالترقيم الفاتورة
            $table->string('invoice_number', 50);//رقم الفاتورة
            $table->date('invoice_Date')->nullable();//تاريخ الفاتورة
            $table->date('Due_date')->nullable();//تاريخ الاستحقاق
            $table->string('product', 50);//المنتج
            $table->bigInteger( 'section_id' )->unsigned();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->decimal('Amount_collection',8,2)->nullable();;
            $table->decimal('Amount_Commission',8,2);
            $table->decimal('Discount',8,2);//خصم في الفاتورة
            $table->decimal('Value_VAT',8,2);//ثلث الضريبة
            $table->string('Rate_VAT', 999);//نسبة الضريبة
            $table->decimal('Total',8,2);//الارقام العشرية
            $table->string('Status', 50);//الفاتورة المدفوعة والغير المدفوعة
            $table->integer('Value_Status');//عشان تستدعي بكل سهولة في القيم
            $table->text('note')->nullable();
            $table->date('Payment_Date')->nullable();
            $table->softDeletes();
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
