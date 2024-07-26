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

// 应用初始化
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\saas\exceptions\CreateDatabaseFailed;
use think\saas\exceptions\TenantNotFoundException;
use think\saas\managers\Manager;
use think\saas\support\Tenant;

/**
 * 处理请求开始
 *
 * 切换租户信息
 */
class HttpRunListener
{
    /**
     * @throws TenantNotFoundException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException|CreateDatabaseFailed
     */
    public function handle($event): void
    {
        // dd(app(Tenant::class)->createDatabase(1));
        // 这里来处理租户，因为这是应用最早启动的时候
        // 因为此时配置文件已经加载
        // 所以这里可以从主库获取数据
        // 然后保存到缓存中
        // 获取 host
        /* @var Tenant $tenant*/
        $tenant = app()->make('tenant');
        // 非单库模式
        if (! $tenant->isSingleDatabase()) {
            $currentTenant = $this->getTenant();
            if ($currentTenant) {
                // 租户初始化
                app(Tenant::class)->initialize($currentTenant);

                // 重新绑定核心
                app(Manager::class)->reBind();
            }
        }
    }

    /**
     * 获取租户 租户ID|模式
     *
     * @return mixed
     */
    protected function getTenant(): mixed
    {
        // 单域名模式
        if (config('saas.mode.is_single_domain')) {
            // 从头部获取租户 ID
            $tenantId = request()->header(config('saas.mode.tenant_header'));
            if (intval($tenantId) === 0) {
                return false;
            }

            return $tenantId;
        } else {
            // 多域名模式
            $host = request()->host();
            // 如果是总后台则不处理
            if (in_array($host, config('saas.mode.hosts'))) {
                return false;
            }

            // 通过域名获取租户
            return app(config('saas.domain_model'))->getTenant($host);
        }
    }
}
