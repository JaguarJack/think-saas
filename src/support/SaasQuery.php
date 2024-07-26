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

namespace think\saas\support;

use think\db\Query;
use think\saas\events\SaasBeforeInsert;

class SaasQuery extends Query
{
    /**
     * @param array $data
     * @param bool $getLastInsID
     * @return int|mixed|string
     */
    public function insert(array $data = [], bool $getLastInsID = false)
    {
        if (!empty($data)) {
            $this->options['data'] = event(new SaasBeforeInsert($data));
        }

        return $this->connection->insert($this, $getLastInsID);
    }

    /**
     * 批量插入记录.
     *
     * @param array $dataSet 数据集
     * @param int   $limit   每次写入数据限制
     *
     * @return int
     */
    public function insertAll(array $dataSet = [], int $limit = 0): int
    {
        if (empty($dataSet)) {
            $dataSet = $this->options['data'] ?? [];

            $dataSet = event(new SaasBeforeInsert($dataSet));
        }

        if ($limit) {
            $this->limit($limit);
        }

        return $this->connection->insertAll($this, $dataSet);
    }
}
