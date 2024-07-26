<?php
namespace think\saas;

use think\event\HttpRun;
use think\saas\commands\InstallSaas;
use think\saas\events\InitializeTenantDatabase;
use think\saas\events\InitializeTenantDatabaseData;
use think\saas\events\SaasBeforeInsert;
use think\saas\listeners\InitializeTenantDatabaseListener;
use think\Service;
use think\saas\listeners\HttpRunListener;
use think\saas\support\Tenant;
use think\saas\listeners\InitializeTenantDatabaseDataListener;
use think\saas\listeners\SaasBeforeInsertListener;

class SaasService extends Service
{
    /**
     * boot
     *
     * @return void
     */
    public function boot(): void
    {
        $this->commands([
            InstallSaas::class
        ]);

        $this->eventListen();
    }

    /**
     * register
     *
     * @return void
     */
    public function register(): void
    {
        // 绑定租户单例
        $this->app->bind('tenant', Tenant::class);
    }

    /**
     * 事件监听
     *
     * @return void
     */
    protected function eventListen(): void
    {
        $this->app->loadEvent([
            'listen' => config('saas.listeners', []),
        ]);
    }
}
