<?php

namespace think\saas\support\sync;

use think\Db;
use think\db\ConnectionInterface;

class TableData
{
    public function __construct(
        protected string $originConnect = 'mysql',
        protected string $targetConnect = 'tenant'
    ){}

    public function syncAll()
    {

    }


    /**
     * 同步数据
     *
     * @param string $tableName
     * @param array $fields
     * @return void
     */
    public function sync(string $tableName, array $fields): void
    {
        $data = [];

        $this->getOriginConnect()->table($this->tableName)->field($this->fields)
            ->cursor(function ($item) use (&$data) {
                $data[] = $item->toArray();

                if (count($data) >= 100) {
                    $this->getTargetConnect()->table($this->tableName)->insertAll($data);
                    $data = [];
                }
            });

        if (count($data)) {
            $this->getTargetConnect()->table($this->tableName)->insertAll($data);
            $data = [];
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
