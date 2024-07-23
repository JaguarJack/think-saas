<?php
namespace think\saas\support\sync;


use think\facade\Db;

class Schema
{
    public function __construct(protected string $tableName = '')
    {

    }


    public function setTable(string $tableName): static
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function getOriginTablesStructure(): array
    {
        $tables = Db::connect('mysql')->getTables();

        $structures = [];

        foreach ($tables as $table) {
            $structures[] =$this->generateCreateTableSQL($table);
        }

        return $structures;
    }


    protected function generateCreateTableSQL($tableName): string
    {
        $fields = Db::connect('mysql')->getFields($tableName);

        $sql = "CREATE TABLE `$tableName` ( \n";
        $primaryKey = null;

        foreach ($fields as $field => $info) {
            $sql .= "  `$field` " . $info['type'];

            if ($info['primary']) {
                $primaryKey = $field;
            }

            if ($info['notnull']) {
                $sql .= " NOT NULL";
            }

            if ($info['autoinc']) {
                $sql .= " AUTO_INCREMENT";
            }

            if ($info['default'] !== null) {
                $sql .= " DEFAULT '" . $info['default'] . "'";
            }

            $sql .= ",\n";
        }

        if ($primaryKey) {
            $sql .= "  PRIMARY KEY (`$primaryKey`)\n";
        } else {
            // 移除最后一个字段定义后的逗号
            $sql = rtrim($sql, ",\n") . "\n";
        }

        $charset = Db::connect('mysql')->getConfig('charset');

        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$charset}_unicode_ci;";

        return $sql;
    }

    protected function changeToMasterDatabase()
    {

    }
}
