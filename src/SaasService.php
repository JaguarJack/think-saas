<?php
namespace think\saas;

use think\event\HttpRun;
use think\saas\commands\InstallSaas;
use think\saas\contracts\DomainContract;
use think\saas\contracts\TenantContract;
use think\Service;
use think\saas\listeners\HttpRunListener;
use think\saas\models\Domain;
use think\saas\models\Tenant;

class SaasService extends Service
{
    public function boot()
    {
        $this->commands([
            InstallSaas::class
        ]);


        $this->eventListen();
    }

    public function register()
    {
        $this->app->bind(DomainContract::class, config('saas.domain_model', Domain::class));

        $this->app->bind(TenantContract::class, config('saas.tenant_model', Tenant::class));
    }


    /**
     * 事件监听
     *
     * @return void
     */
    protected function eventListen()
    {
        $this->app->loadEvent([
            'listen' => [
                HttpRun::class => [HttpRunListener::class],
            ]
        ]);
    }
}
