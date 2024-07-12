<?php
namespace think\saas\listeners;


// 应用初始化
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\saas\exceptions\CreateDatabaseFailed;
use think\saas\exceptions\TenantNotFoundException;
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
        dd(app(Tenant::class)->createDatabase(1));
        // 这里来处理租户，因为这是应用最早启动的时候
        // 因为此时配置文件已经加载
        // 所以这里可以从主库获取数据
        // 然后保存到缓存中
        // 获取 host
        $host = request()->host();
        // 如果是总后台则不处理
        if (in_array($host, config('saas.hosts'))) {
            return;
        }
        // 租户初始化
        app(Tenant::class)->initialize(app(config('saas.domain_model'))->getTenant($host));
    }
}
