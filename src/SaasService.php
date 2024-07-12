<?php
namespace think\saas;

use think\event\HttpRun;
use think\saas\commands\InstallSaas;
use think\saas\events\InitializeTenantDatabase;
use think\saas\events\InitializeTenantDatabaseData;
use think\saas\listeners\InitializeTenantDatabaseListener;
use think\saas\managers\DbManager;
use think\Service;
use think\saas\listeners\HttpRunListener;
use think\saas\models\Tenant;
use think\saas\listeners\InitializeTenantDatabaseDataListener;

class SaasService extends Service
{
    public function boot()
    {
        $this->commands([
            InstallSaas::class
        ]);


        $this->eventListen();
    }

    public function register(): void
    {
        // 绑定租户单例
        $this->app->bind(Tenant::class, Tenant::class);
        // 重新绑定DB对象
        $this->app->bind('db', DbManager::class);

        // 接管框架相关核心
        $this->app->bind('db', DbManager::class);
        $this->app->bind('think\DbManager', DbManager::class);
    }


    /**
     * 事件监听
     *
     * @return void
     */
    protected function eventListen(): void
    {
        $this->app->loadEvent([
            'listen' => [
                HttpRun::class => [HttpRunListener::class],
                InitializeTenantDatabase::class => [InitializeTenantDatabaseListener::class],
                InitializeTenantDatabaseData::class => [InitializeTenantDatabaseDataListener::class]
            ]
        ]);
    }
}
