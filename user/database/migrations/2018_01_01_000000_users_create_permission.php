<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersCreatePermission extends Migration
{
    const CONST_PERMISSION_ID = 'permission_id';
    const CONST_ROLE_ID = 'role_id';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger(self::CONST_PERMISSION_ID);
            $table->morphs('model');

            $table->foreign(self::CONST_PERMISSION_ID)
                  ->references('id')
                  ->on($tableNames['permissions'])
                  ->onDelete('cascade');

            $table->primary(
                [self::CONST_PERMISSION_ID, 'model_id', 'model_type']
                , 'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger(self::CONST_ROLE_ID);
            $table->morphs('model');

            $table->foreign(self::CONST_ROLE_ID)
                  ->references('id')
                  ->on($tableNames['roles'])
                  ->onDelete('cascade');

            $table->primary([self::CONST_ROLE_ID, 'model_id', 'model_type']);
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger(self::CONST_PERMISSION_ID);
            $table->unsignedInteger(self::CONST_ROLE_ID);

            $table->foreign(self::CONST_PERMISSION_ID)
                  ->references('id')
                  ->on($tableNames['permissions'])
                  ->onDelete('cascade');

            $table->foreign(self::CONST_ROLE_ID)
                  ->references('id')
                  ->on($tableNames['roles'])
                  ->onDelete('cascade');

            $table->primary([self::CONST_PERMISSION_ID, self::CONST_ROLE_ID]);

            app('cache')->forget('spatie.permission.cache');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
