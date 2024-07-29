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

namespace think\saas\support\sync\single;

use Generator;
use think\Db;
use think\saas\support\Tree;

/**
 * 单库数据同步
 */
class TableData
{
    /**
     * @param string $originTable
     * @param string $targetTable
     * @param int $tenantId
     */
    public function __construct(
        protected string $originTable,
        protected string $targetTable,
        protected int $tenantId
    ){}

    /**
     * 同步
     *
     * @param callable|null $callback
     * @return true
     */
    public function sync(callable $callback = null)
    {
        // 如果是树型结构
        if ($parentField = $this->isHasParentField()) {
            $originData = $this->getOriginTeenTableData($callback);
            $this->syncTreeData($originData, $parentField);
            return true;
        }

        // 同步正常结构
        $originData = $this->getOriginTableData();

        if (! is_null($callback)) {
            return $callback($this->targetTable, $originData);
        }

        $data = [];
        $originData->each(function ($item) use (&$data){
            $data[] = $this->addTenantId($item->toArray());
            if (count($data) >= 100) {
                Db::name($this->targetTable)->insertAll($data);
                $data = [];
            }
        });

        if (count($data)) {
            Db::name($this->targetTable)->insertAll($data);
        }

        return true;
    }

    /**
     * @param array $originData
     * @param $parentField
     * @param int $parentId
     * @return array
     */
    protected function syncTreeData(array $originData, $parentField, int $parentId = 0): array
    {
        $originData = Tree::done($originData, $parentId, $parentField);

        $insertedIds = [];

        foreach ($originData as $item) {
            $item = $this->addTenantId($item->toArray());
            $item['parent_id'] = $parentId;
            $id = Db::name($this->targetTable)->insertGetId($item);

            $insertedIds[] = $id;

            if (isset($item['children']) && is_array($item['children'])) {
                $childIds = $this->syncTreeData($item['children'], $id);
                $insertedIds = array_merge($insertedIds, $childIds);
            }
        }

        return $insertedIds;
    }

    /**
     * 获取源表数据
     *
     * @param callable|null $callback
     * @return Generator
     */
    public function getOriginTableData(callable $callback = null)
    {
        if (!is_null($callback)) {
            return $callback($this->originTable);
        }

        return Db::name($this->originTable)->cursor();
    }


    /**
     * @param callable|null $callback
     * @return mixed
     */
    public function getOriginTeenTableData(callable $callback = null)
    {
        if (!is_null($callback)) {
            return $callback($this->originTable);
        }

        return Db::name($this->originTable)->get();
    }

    /**
     * 是否有父级字段
     *
     * @return false|mixed
     */
    protected function isHasParentField(): mixed
    {
        $fields = Db::name($this->originTable)->getFields();

        $parentFields = config('saas.sync.schema.parent_fields', []);
        foreach ($fields as $field) {
            if (in_array($field, $parentFields)) {
                return $field;
            }
        }

        return false;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function addTenantId($data)
    {
        $data[config('saas.tenant_id')] = $this->tenantId;

        return $data;
    }
}
