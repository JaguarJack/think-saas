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

namespace catch\saas\models\traits;

use think\model\relation\BelongsToMany;
trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(config('saas.permission_model'), 'tenant_has_permissions', 'tenant_id', 'permission_id');
    }
}
