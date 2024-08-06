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

namespace catch\saas\support\traits;

use catch\saas\events\SaasBeforeInsert;

/**
 * 复用 Trait
 */
trait SaaSQueryTrait
{
    /**
     * @param array $data
     * @param bool $getLastInsID
     * @return int|mixed|string
     */
    public function insert(array $data = [], bool $getLastInsID = false)
    {
        if (!empty($data)) {
            // thinkphp 事件返回会包装在数组中，所以需要这么处理
            $this->options['data'] = event(new SaasBeforeInsert($data))[0] ?? $data;
        } else {
            $this->options['data'] = event(new SaasBeforeInsert($this->options['data']))[0] ?? $data;
        }

        $res = $this->connection->insert($this, $getLastInsID);

        // 如果插入成功 & 非自增键 & 获取最后一次插入的ID
        if ($res && !$this->getAutoInc() && $getLastInsID) {
            return $this->options['data'][config('saas.uuid')] ?? $res;
        }

        return $res;
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
        }

        // thinkphp 事件返回会包装在数组中，所以需要这么处理
        $dataSet = event(new SaasBeforeInsert($dataSet, true))[0] ?? $dataSet;

        if ($limit) {
            $this->limit($limit);
        }

        return $this->connection->insertAll($this, $dataSet);
    }
}
