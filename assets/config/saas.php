<?php

use think\saas\models\Tenant;
use think\saas\models\Domain;

return [
     // 总台 host 集合
    'hosts' => [

     ],

    // 是否是单数据库模型
    'is_single_database' => true,

    // 租户 ID 字段
    'tenant_id' => 'tenant_id',

    // 租户模型
    'tenant_model' => Tenant::class,

    // 租户域名模型
    'domain_model' => Domain::class,

    // manager
    'mangers' => [
      ''
    ],
];
