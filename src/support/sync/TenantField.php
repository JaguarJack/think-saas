<?php
namespace think\saas\support\sync;

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

    protected function columnExistInTable($table, $column)
    {
        return Db::query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
    }


    protected function addColumn($table, $column)
    {
        Db::execute("ALTER TABLE `{$table}` ADD `{$column}` INT(11) DEFAULT 0 COMMENT '租户ID'");
    }
}
