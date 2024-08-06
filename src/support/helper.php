<?php

use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use catch\saas\support\Tenant;
use catch\saas\exceptions\TenantNotFoundException;
use catch\saas\support\Str;

if (!function_exists('tenant')) {

    /**
     * 获取当前租户
     *
     * @param mixed|null $t
     * @return Tenant|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws TenantNotFoundException
     */
    function tenant(mixed $t = null): Tenant|null
    {
        if (! $t) {
            /* @var Tenant $tenant */
            $tenant = app()->make('tenant');

            if (! $tenant) {
                throw new TenantNotFoundException();
            }

            if (! $tenant->isInitialized()) {
                throw new TenantNotFoundException();
            }

            return $tenant;
        } else {
            $tenant = new Tenant();
            $tenant->initialize($t);
            return $tenant;
        }
    }
}


if (! function_exists('tenant_uuid')) {

    /**
     * 获取当前租户的uuid
     *
     * @return string
     */
    function tenant_uuid(): string
    {
        return Str::uuid();
    }
}
