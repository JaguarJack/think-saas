<?php

namespace think\saas\support\sync;

class CreateTableSQL
{
    public function __construct(
        protected string $tableName,
        protected string $charset = 'utf8mb4'
    ){}

    /**
     * 生成 SQL
     *
     * @param $fields
     * @return string
     */
    public function generate($fields): string
    {
        $sql = "CREATE TABLE `{$this->tableName}` ( \n";
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

        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET={$this->charset} COLLATE={$this->charset}_unicode_ci;";

        return $sql;
    }

}
