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

use think\event\HttpRun;
use think\saas\events\InitializeTenantDatabase;
use think\saas\events\InitializeTenantDatabaseData;
use think\saas\events\SaasBeforeInsert;
use think\saas\listeners\HttpRunListener;
use think\saas\listeners\InitializeTenantDatabaseDataListener;
use think\saas\listeners\InitializeTenantDatabaseListener;
use think\saas\listeners\SaasBeforeInsertListener;
use think\saas\models\Tenant;
use think\saas\models\Domain;

return [
    /**
     * 租户模式
     */
    'mode' => [
        /**
         * 是否是单数据库模型
         */
        'is_single_database' => true,

        /**
         * 是否是单域名模式
         */
        'is_single_domain' => true,

        /**
         * 如果是单域名，多租户模式
         *
         * 租户请求头
         */
        'tenant_header' => '',

        /**
         *
         * 如果是多域名模式
         *
         * 需要配置总后台的域名
         *
         */
        'hosts' => [

        ],
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
    ],

    /**
     * 事件监听
     */
    'listeners' => [
        // http run 事件
        HttpRun::class => [HttpRunListener::class],
        // 初始化租户数据库
        InitializeTenantDatabase::class => [InitializeTenantDatabaseListener::class],
        // 初始化租户数据
        InitializeTenantDatabaseData::class => [InitializeTenantDatabaseDataListener::class],
        // 租户数据插入前
        SaasBeforeInsert::class => [SaasBeforeInsertListener::class],
    ],

    /**
     * Query 类替换
     *
     * SaasQuery 主要用来劫持底层的 Insert 方法
     *
     * 在单库模式下，使用它则可以无需维护 `tenant_id` 字段
     *
     * 将会由扩展自动维护
     */
    'query' => \think\saas\support\SaasQuery::class
];
