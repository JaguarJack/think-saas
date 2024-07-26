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

use think\migration\Migrator;
use think\migration\db\Column;

class Tenant extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('tenant');
        $table->addColumn('name', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('description', 'string', ['limit' => 1000, 'default' => ''])
            ->addColumn('database', 'json', ['null' => true])
            ->addColumn('created_at', 'integer', ['default' => 0])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}
