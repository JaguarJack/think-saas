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


namespace think\saas\listeners;

use think\saas\events\SaasBeforeInsert;
use think\saas\exceptions\TenantNotFoundException;
use think\saas\support\Tenant;

/**
 * 处理请求开始
 *
 * 切换租户信息
 */
class SaasBeforeInsertListener
{

    /**
     * @param SaasBeforeInsert $event
     * @return array
     * @throws TenantNotFoundException
     */
    public function handle(SaasBeforeInsert $event): array
    {
        $data = $event->data;

        if (! count($data)) {
            return $data;
        }

        // 添加 uuid
        return $this->addUuid(
            // 添加租户ID
            $this->addTenantId($data, $event->multi),
            $event->multi
        );
    }

    /**
     * 添加租户ID
     *
     * @param $data
     * @param $isMulti
     * @return mixed
     * @throws TenantNotFoundException
     */
    protected function addTenantId($data, $isMulti): mixed
    {
        /* @var Tenant $tenant */
        $tenant = \tenant();

        // 租户为初始化或者是单库模式
        if (! $tenant->isSingleDatabase()) {
            return $data;
        }

        // 填充租户信息
        $tenantId = $tenant->getId();
        if ($isMulti) {
            foreach ($data as &$item) {
                $item[config('saas.tenant_id')] = $tenantId;
            }

        } else {
            $data[config('saas.tenant_id')] = $tenantId;
        }

        return $data;
    }

    /**
     * add uuid
     *
     * @param $data
     * @param $isMulti
     * @return mixed
     */
    protected function addUuid($data, $isMulti): mixed
    {
        $uuid = config('saas.uuid');

        if (! $uuid) {
            return $data;
        }

        if ($isMulti) {
            foreach ($data as &$item) {
                $item[$uuid] = \tenant_uuid();
            }
        } else {
            $data[$uuid] = \tenant_uuid();
        }

        return $data;
    }
}
