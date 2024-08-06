<?php
// +----------------------------------------------------------------------
// | Think SaaS 开源软件
// +----------------------------------------------------------------------
// | CatchAdmin [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024~2030 https://catchadmin.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Author: JaguarJack <njphper@gmail.com>
// +----------------------------------------------------------------------


namespace catch\saas\support\sync\single;

use think\facade\Db;

/**
 * 同步单库租户 ID
 */
class TenantField
{
    public function sync()
    {
        $tables = Db::getTables();

        $tenantId = config('saas.tenant_id');

        foreach ($tables as $table) {
            if (! $this->columnExistInTable($tables, $tenantId)) {
                $this->addColumn($table, $tenantId);
            }
        }

        return true;
    }

    /**
     * 判断字段是否存在
     *
     * @param $table
     * @param $column
     * @return mixed
     */
    protected function columnExistInTable($table, $column): mixed
    {
        return Db::query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
    }

    /**
     * @param $table
     * @param $column
     * @return void
     */
    protected function addColumn($table, $column): void
    {
        Db::execute("ALTER TABLE `{$table}` ADD `{$column}` INT(11) DEFAULT 0 COMMENT '租户ID'");
    }
}
