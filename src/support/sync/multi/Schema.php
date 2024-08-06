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


namespace catch\saas\support\sync\multi;

use think\db\ConnectionInterface;
use think\facade\Db;

class Schema
{
    public function __construct(
        protected string $originConnect = 'mysql',
        protected string $targetConnect = 'tenant'
    ){}

    /**
     * 获取主应用表结构
     *
     * @return array
     */
    public function getTablesFields(): array
    {
        $tables = $this->getOriginConnect()->getTables();

        // 配置需要同步的表
        $syncTables = config('saas.sync.schema.tables');

        if ($syncTables && $syncTables != '*') {
            foreach ($tables as $k => $table) {
                if (!in_array($table, $syncTables)) {
                    unset($tables[$k]);
                }
            }
        }

        $tablesFields = [];

        foreach ($tables as $table) {
            $tableFields = $this->getTableFields($table);
            $tablesFields[$table] = $tableFields;
        }

        return $tablesFields;
    }


    /**
     * @param $tableName
     * @return array
     */
    protected function getTableFields($tableName): array
    {
        $fields = $this->getOriginConnect()->getFields($tableName);

        // 需要同步的表字段
        $syncTableFields = config('saas.sync.schema.fields');

        // fields
        foreach ($fields as $k => $field) {
            if (! isset($syncTableFields[$tableName])) {
                break;
            }

            $syncFields = $syncTableFields[$tableName];
            if (!is_array($syncFields)) {
                $syncFields = explode(',', $syncFields);
            }

            if (!in_array($field['name'], $syncFields)) {
                unset($fields[$k]);
            }
        }

        return $fields;
    }

    /**
     *
     * 同步到租户数据库
     *
     * @return void
     */
    protected function syncToTenantDatabase(): void
    {
        $tablesFields = $this->getTablesFields();

        $charset = $this->getOriginConnect()->getConfig('charset');

        $structures = [];
        foreach ($tablesFields as $table => $fields) {
            $structures[] = (new CreateTableSQL($table, $charset))->generate($fields);
        }

        foreach ($structures as $structure) {
            $this->getTargetConnect()->execute($structure);
        }
    }

    /**
     * 同步数据
     *
     * @return void
     */
    protected function syncToTenantDatabaseData(): void
    {
        $tablesFields = $this->getTablesFields();

        foreach ($tablesFields as $table => $fields) {
            $tableData = new TableData($this->originConnect, $this->targetConnect);

            $tableData->sync($table, $fields);
        }
    }

    /**
     * @return ConnectionInterface
     */
    protected function getOriginConnect(): ConnectionInterface
    {
        return Db::connect($this->originConnect);
    }

    /**
     * @return ConnectionInterface
     */
    protected function getTargetConnect(): ConnectionInterface
    {
        return Db::connect($this->targetConnect);
    }
}
