<?php
namespace think\saas;

use think\saas\commands\InstallSaas;
use think\Service;

class SaasService extends Service
{
    public function boot()
    {
        $this->commands([
            InstallSaas::class
        ]);
    }
}
