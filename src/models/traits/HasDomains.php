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

namespace think\saas\models\traits;

use think\model\relation\HasMany;
use think\saas\models\Domain;

trait HasDomains
{

    public function domain(): HasMany
    {
        return $this->hasMany(config('saas.domain_model', Domain::class), 'tenant_id');
    }
}
