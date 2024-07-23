<?php

use think\saas\models\Tenant;
use think\saas\models\Domain;

return [
    /**
     * 是否是单数据库模型
     */
    'is_single_database' => true,

    /**
     * 是否是单域名模式
     */
    'is_single_domain' => true,

    /**
     *
     * 如果是多域名模式
     *
     * 需要配置总后台的域名
     *
     */
    'hosts' => [

    ],

    /**
     * 租户ID
     */
    'tenant_id' => 'tenant_id',

    /**
     * 租户模型
     */
    'tenant_model' => Tenant::class,

    /**
     * 租户模型
     */
    'domain_model' => Domain::class,

    /**
     * 针对多租户模式
     *
     * 新增租户，同步数据
     *
     */
    'sync' => [
        'schema' => [
            /**
             * 需要同步的表
             *
             * @first * 代表全部
             *
             * @second 如果需要指定表，需要使用数组 ['users', 'posts']
             */
            'tables' => '*',

            /**
             * 默认空数组，同步所有字段
             *
             * 指定字段
             *
             * "users" => 'field,field2' || ['fields', 'fields2']
             */
            'fields' => [

            ]
        ]
    ]
];
