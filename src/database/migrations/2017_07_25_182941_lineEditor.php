<?php

use Illuminate\Support\Facades\DB;

class LineEditor extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = "kkt_line";
        if (!$this->schema->hasTable($tableName)) {
            $this->schema->create($tableName, function ($table) {
                $table->increments('id')->unsigned()->comment('表主键id');
                $table->integer('dest_city_pid')->default(0)->comment('(冗余字段)目的国父id,对应kkt_city表的id');
                $table->string('dest_city_name')->default(0)->comment('(冗余字段)目的国名称');
                $table->string('title')->default('')->comment('线路名称');
                $table->unsignedTinyInteger('day_num')->default(0)->comment('线路天数');
                $table->string('cover_url')->default('')->comment('封面图地址');
                $table->string('cover_group')->default('')->comment('封面图分组');
                $table->integer('cover_img_id')->default(0)->comment('(冗余字段)行程图片id，关联kkt_line_img表的id');
                $table->string('uuid')->default('')->comment('uuid');
                $table->unsignedSmallInteger('created_user_id')->default(0)->comment('创建用户id')->index('created_user_id', 'created_user_id');
                $table->unsignedSmallInteger('created_organization_id')->default(0)->comment('创建用户组织')->index('created_organization_id', 'created_organization_id');
                $table->unsignedTinyInteger('is_draft')->default(1)->comment('是否草稿 0：否 1：是');
                $table->unsignedTinyInteger('is_delete')->default(1)->comment('是否删除 1：否 2：删除');
                $table->timestamps();
                $table->integer('version_id')->comment('版本号id');
            });
            DB::connection('mysql_migrate')->statement("ALTER TABLE `kkt_line` COMMENT = '线路表';");
        }

        $tableName = "kkt_line_day_rela";
        if (!$this->schema->hasTable($tableName)) {
            $this->schema->create($tableName, function ($table) {
                $table->increments('id')->unsigned()->comment('表主键id');
                $table->integer('line_id')->comment('线路id,关联kkt_line的id');
                $table->integer('day_id')->comment('天数id,关联kkt_line_day的id');
                $table->unsignedTinyInteger('day')->default(0)->comment('具体天数');
                $table->unsignedTinyInteger('is_delete')->default(1)->comment('是否删除 1：否 2：删除');
                $table->timestamps();
            });
            DB::connection('mysql_migrate')->statement("ALTER TABLE `kkt_line_day_rela` COMMENT = '线路天数关系表';");
        }


        $tableName = "kkt_line_day";
        if (!$this->schema->hasTable($tableName)) {
            $this->schema->create($tableName, function ($table) {
                $table->increments('id')->unsigned()->comment('表主键id');
                $table->unsignedTinyInteger('day')->default(0)->comment('具体天数');
                $table->unsignedTinyInteger('is_delete')->default(1)->comment('是否删除 1：否 2：删除');
                $table->timestamps();
                $table->integer('version_id')->comment('版本号id');
            });
            DB::connection('mysql_migrate')->statement("ALTER TABLE `kkt_line_day` COMMENT = '线路天数表';");
        }

        $tableName = "kkt_line_day_items";
        if (!$this->schema->hasTable($tableName)) {
            $this->schema->create($tableName, function ($table) {
                $table->increments('id')->unsigned()->comment('表主键id');
                $table->integer('day_id')->comment('线路天数id,关联kkt_line_tour的id');
                $table->integer('item_id')->comment('单项资源项目id,关联kkt_line_item的id');
                $table->unsignedTinyInteger('is_import')->default(0)->comment('重要行程 0 否 1是');
                $table->unsignedTinyInteger('is_delete')->default(1)->comment('是否删除 1：否 2：删除');
                $table->integer('sort')->default(0)->comment('排序');
                $table->timestamps();
            });
            DB::connection('mysql_migrate')->statement("ALTER TABLE `kkt_line_day_items` COMMENT = '线路天数单项资源关系表';");
        }

        $tableName = "kkt_line_item";
        if (!$this->schema->hasTable($tableName)) {
            $this->schema->create($tableName, function ($table) {
                $table->increments('id')->unsigned()->comment('表主键id');
                $table->integer('type_id')->comment('单项资源类型,关联kkt_line_item_type的id');
                $table->string('title')->default('')->comment('标题');
                $table->string('desc',2550)->default('')->comment('描述');
                $table->unsignedTinyInteger('time_type')->default(0)->comment('时间类型 1 小时 2 分钟');
                $table->unsignedTinyInteger('time')->default(0)->comment('用时');
                $table->unsignedTinyInteger('self_care')->default(1)->comment('是否自理 0否 1:是');
                $table->integer('dest_city_id')->default(0)->comment('目的国id');
                $table->string('dest_city_name')->default('')->comment('目的国名称');
                $table->double('distance', 15, 2)->default('0.00')->comment('路程距离单位');
                $table->unsignedTinyInteger('distance_type')->default(1)->comment('距离单位类型 1公里 2英里 3海里');
                $table->unsignedTinyInteger('is_delete')->default(1)->comment('是否删除 1：否 2：删除');
                $table->timestamps();
                $table->integer('version_id')->comment('版本号id');
            });
            DB::connection('mysql_migrate')->statement("ALTER TABLE `kkt_line_item` COMMENT = '线路单项资源表';");
        }

        $tableName = "kkt_line_item_type";
        if (!$this->schema->hasTable($tableName)) {
            $this->schema->create($tableName, function ($table) {
                $table->increments('id')->unsigned()->comment('表主键id');
                $table->integer('pid')->comment('父id,0则为顶级类型');
                $table->string('name')->default('')->comment('类型名称');
                $table->unsignedTinyInteger('is_delete')->default(1)->comment('是否删除 1：否 2：删除');
                $table->timestamps();
            });
            DB::connection('mysql_migrate')->statement("ALTER TABLE `kkt_line_item_type` COMMENT = '线路单项资源类型表';");
            $time = date('Y-m-d H:i:s');
            DB::connection('mysql_migrate')->statement("INSERT INTO kkt_line_item_type (`id`, `pid`, `name`,`created_at`,`updated_at`)
            VALUES
            (1, 0, '景点','{$time}','{$time}'),
	(2, 0, '娱乐','{$time}','{$time}'),
	(3, 0, '购物','{$time}','{$time}'),
	(4, 0, '住宿','{$time}','{$time}'),
	(5, 0, '餐饮','{$time}','{$time}'),
	(6, 0, '出行','{$time}','{$time}'),
	(7, 6, '口岸','{$time}','{$time}'),
	(8, 6, '交通工具','{$time}','{$time}');
");
        }

        $tableName = "kkt_line_img";
        if (!$this->schema->hasTable($tableName)) {
            $this->schema->create($tableName, function ($table) {
                $table->increments('id')->unsigned()->comment('表主键id');
                $table->string('large_url')->default('')->comment('大图片地址');
                $table->string('middle_url')->default('')->comment('中图片地址');
                $table->string('small_url')->default('')->comment('小图片地址');
                $table->string('group_large')->default('')->comment('大图片分组');
                $table->string('group_middle')->default('')->comment('中图片分组');
                $table->string('group_small')->default('')->comment('小图片分组');
                $table->unsignedTinyInteger('is_delete')->default(1)->comment('是否删除 1：否 2：删除');
                $table->timestamps();
                $table->integer('version_id')->comment('版本号id');
            });
            DB::connection('mysql_migrate')->statement("ALTER TABLE `kkt_line_img` COMMENT = '线路图片表';");
        }

        $tableName = "kkt_line_item_imgs";
        if (!$this->schema->hasTable($tableName)) {
            $this->schema->create($tableName, function ($table) {
                $table->increments('id')->unsigned()->comment('表主键id');
                $table->integer('item_id')->comment('行程单项id,关联kkt_line_item的id');
                $table->integer('img_id')->comment('线路图片id,关联kkt_line_img的id');
                $table->unsignedTinyInteger('is_delete')->default(1)->comment('是否删除 1：否 2：删除');
                $table->timestamps();
                $table->integer('version_id')->comment('版本号id');
            });
            DB::connection('mysql_migrate')->statement("ALTER TABLE `kkt_line_item_imgs` COMMENT = '线路单项资源图片关系表';");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if ($this->schema->hasTable('kkt_line')) {
            $this->schema->drop('kkt_line');
        }
        if ($this->schema->hasTable('kkt_line_day')) {
            $this->schema->drop('kkt_line_day');
        }
        if ($this->schema->hasTable('kkt_line_day_item')) {
            $this->schema->drop('kkt_line_day_item');
        }
        if ($this->schema->hasTable('kkt_line_item')) {
            $this->schema->drop('kkt_line_item');
        }
        if ($this->schema->hasTable('kkt_line_item_type')) {
            $this->schema->drop('kkt_line_item_type');
        }
        if ($this->schema->hasTable('kkt_line_img')) {
            $this->schema->drop('kkt_line_img');
        }
        if ($this->schema->hasTable('kkt_line_item_imgs')) {
            $this->schema->drop('kkt_line_item_imgs');
        }

    }
}
