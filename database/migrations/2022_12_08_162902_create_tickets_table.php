<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('assigned to'); 
            $table->unsignedBigInteger('raised_by')->nullable(); //raised by
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('approved')->nullable()->comment('from_user_table'); 
            $table->string('title');
            $table->text('details');
            $table->string('ticket_code');
            $table->char('type')->default(0)->comment('0 = problem , 1 = change request , 2 = new request , 3 = support');
            $table->char('priority')->comment(' L = low , U = urgent , N = normal');
            $table->string('url')->nullable();
            $table->date('raising_date')->nullable();
            $table->string('ticket_number')->nullable();
            $table->string('related_ticket_id')->nullable();
            $table->string('comment')->nullable();
            $table->text('root_cause')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('raised_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('approved')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
