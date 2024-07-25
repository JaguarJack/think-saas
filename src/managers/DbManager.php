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


declare(strict_types=1);

namespace think\saas\managers;

use think\Db;
use think\db\ConnectionInterface;

class DbManager extends Db
{
    /**
     * 创建数据库
     *
     * @param string $database
     * @return bool
     */
    public function createDatabase(string $database): bool
    {
        $charset = $this->config->get('database.connections.mysql.charset');

        $collation = $this->config->get('database.connections.mysql.collation', 'utf8_general_ci');

        return (bool) $this->getDefaultConnection()->execute("CREATE DATABASE `{$database}` CHARACTER SET `$charset` COLLATE `$collation`");
    }


    /**
     * 删除数据库
     *
     * @param string $database
     * @return bool
     */
    public function dropDatabase(string $database): bool
    {
        $this->getDefaultConnection()->execute("DROP DATABASE `{$database}`");

        return !$this->databaseExists($database);
    }


    /**
     * 判断数据库是否存在
     *
     * @param string $database
     * @return mixed
     */
    public function databaseExists(string $database): mixed
    {
        return (bool) $this->getDefaultConnection()->execute("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$database}'");
    }


    /**
     * @param string $type
     * @return ConnectionInterface
     */
    public function getDefaultConnection(string  $type = 'mysql'): ConnectionInterface
    {
        return $this->connect($type);
    }
}
