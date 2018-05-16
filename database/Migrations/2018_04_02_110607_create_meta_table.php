<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Zoha\Meta\Helpers\MetaHelper as Meta;

class CreateMetaTable extends Migration
{
    /**
     * Run the Migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('meta.tables.default', 'meta'), function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->string('key', 110);
            $table->text('value')->nullable();
            $table->string('type')->default(Meta::META_TYPE_STRING);
            $table->boolean('status')->default(true);
            $table->string('owner_type', 80);
            $table->integer('owner_id');
            $table->unique(['key', 'owner_type', 'owner_id']);
            $table->timestamps();
        });
        if (!is_null(config('meta.tables.custom')) && is_array(config('meta.tables.custom'))) {
            foreach (config('meta.tables.custom') as $customTable => $relatedClass) {
                Schema::create($customTable, function (Blueprint $table) {
                    $table->charset = 'utf8';
                    $table->collation = 'utf8_unicode_ci';
                    $table->increments('id');
                    $table->string('key', 110);
                    $table->text('value')->nullable();
                    $table->string('type')->default(Meta::META_TYPE_STRING);
                    $table->boolean('status')->default(true);
                    $table->string('owner_type', 80);
                    $table->integer('owner_id');
                    $table->unique(['key', 'owner_type', 'owner_id']);
                    $table->timestamps();
                });
            }
        }
    }

    /**
     * Reverse the Migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('meta.tables.default', 'meta'));
        if (!is_null(config('meta.tables.custom')) && is_array(config('meta.tables.custom'))) {
            foreach (config('meta.tables.custom') as $customTable => $relatedClass) {
                Schema::dropIfExists($customTable);
            }
        }
    }
}
