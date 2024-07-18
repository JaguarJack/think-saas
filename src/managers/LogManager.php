<?php
namespace think\saas\managers;

use think\App;
use think\Log;
use think\saas\support\Tenant;

class LogManager extends Log
{
    /**
     * @param App $app
     */
    public function __construct(
        protected App $app
    )
    {
        parent::__construct($app);

        $this->addTenantPrefix();
    }

    /**
     * 添加租户前缀
     *
     * @return void
     */
    protected function addTenantPrefix(): void
    {
        $tenantPrefix = $this->app->get(Tenant::class)->tenantPrefix('');

        $config = $this->app->config->get('log');

        if (isset($config['channels'])) {
            foreach ($config['channels'] as &$channel) {
                $channel['path'] = $channel['path'] ? ($tenantPrefix . '_' . $channel['path']) : $tenantPrefix;
            }
        }

        $this->app->config->set($config, 'log');
    }
}
