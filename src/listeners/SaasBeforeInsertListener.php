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
     */
    public function handle(SaasBeforeInsert $event): array
    {
        $data = $event->data;

        if (! count($data)) {
            return $data;
        }

        /* @var Tenant $tenant */
        $tenant = app()->make('tenant');
        if (!$tenant->isSingleDatabase()) {
            return $data;
        }

        // 填充租户信息
        $tenantId = $tenant->getId();
        if ($event->multi) {
            foreach ($data as &$item) {
                $item[config('saas.tenant_id')] = $tenantId;
            }

        } else {
            $data[config('saas.tenant_id')] = $tenantId;
        }

        return $data;
    }
}
