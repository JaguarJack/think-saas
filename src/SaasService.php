<?php
namespace catch\saas;

use think\event\HttpRun;
use catch\saas\commands\InstallSaas;
use catch\saas\events\InitializeTenantDatabase;
use catch\saas\events\InitializeTenantDatabaseData;
use catch\saas\events\SaasBeforeInsert;
use catch\saas\listeners\InitializeTenantDatabaseListener;
use think\Service;
use catch\saas\listeners\HttpRunListener;
use catch\saas\support\Tenant;
use catch\saas\listeners\InitializeTenantDatabaseDataListener;
use catch\saas\listeners\SaasBeforeInsertListener;

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
