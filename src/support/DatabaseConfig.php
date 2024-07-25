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

namespace think\saas\support;

use think\helper\Str;
use think\Model;
use think\saas\events\CreateTenantDatabaseName;
use think\saas\events\CreateTenantDatabasePassword;
use think\saas\events\CreateTenantDatabaseUsername;
use think\saas\events\UpdateTenantDatabaseConfig;
use think\saas\models\Tenant;

class DatabaseConfig
{
    /** @var Tenant|Model */
    public Model|Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function getDatabaseName(): ?string
    {
        return $this->tenant->database['database'] ?? null;
    }

    public function getDatabaseUsername(): ?string
    {
        return $this->tenant->database['username'] ?? null;
    }

    public function getDatabasePassword(): ?string
    {
        return $this->tenant->database['password'] ?? null;
    }

    /**
     * 创建数据库名称
     *
     * 数据库名称默认使用[随机字符_租户ID]
     * @return string
     */
    public function createTenantDatabaseName(): string
    {
        if (app()->event->hasListener(CreateTenantDatabaseName::class)) {
            return event(new CreateTenantDatabaseName());
        }

        return 'tenant_database_' . $this->tenant->id;
    }

    /**
     * 创建用户名称
     *
     * 随机小写字母 + 用户ID
     *
     * @return string
     */
    public function createTenantDatabaseUsername(): string
    {
        if (app()->event->hasListener(CreateTenantDatabaseUsername::class)) {
            return event(new CreateTenantDatabaseUsername());
        }

        return Str::random(rand(6, 12), 3) . $this->tenant->id;
    }

    /**
     * 创建数据库密码
     *
     * @return string
     */
    public function createTenantDatabasePassword(): string
    {
        if (app()->event->hasListener(CreateTenantDatabasePassword::class)) {
            return event(new CreateTenantDatabasePassword());
        }

        return Str::random(rand(12, 18));
    }

    /**
     * create database config
     *
     * @return mixed
     */
    public function getConfig(): mixed
    {
        // $default Config
        $defaultConfig = config('database.connections.mysql');
        // 初始化租户的数据库
        $defaultConfig['database'] = $this->createTenantDatabaseName();
        // $defaultConfig['username'] = $this->createTenantDatabaseUsername();
        // $defaultConfig['password'] = $this->createTenantDatabasePassword();

        // 事件处理
        $event = app()->event;
        if ($event->hasListener(UpdateTenantDatabaseConfig::class)) {
            return event(new UpdateTenantDatabaseConfig($defaultConfig));
        }

        return $defaultConfig;
    }


    public function createDatabase()
    {

    }
}
