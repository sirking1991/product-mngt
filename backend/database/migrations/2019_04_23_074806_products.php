<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('code')->unique()->index();
            $table->string('name');
            $table->string('url')->unique()->index();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        DB::statement("CREATE VIEW duplicate_names AS 
            SELECT a.*, b.ctr
            FROM products a
            JOIN (SELECT name, COUNT(*) as ctr
                FROM products
                GROUP BY name
                HAVING COUNT(*) > 1) b on a.name=b.name
            ORDER BY a.name        
        ");
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW duplicate_names");
        Schema::drop('products');
        
    }
}