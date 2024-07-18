<?php
namespace think\saas\managers;

use think\App;
use think\Cache;
use think\saas\support\Tenant;

class CacheManager extends Cache
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
        $tenantPrefix = $this->app->get(Tenant::class)->tenantPrefix();

        $config = $this->app->config->get('cache');

        if (isset($config['stores'])) {
            foreach ($config['stores'] as &$store) {
                $store['prefix'] = $this->addPrefixIfNotSet($tenantPrefix, $store['prefix']);
                $store['tag_prefix'] = $this->addPrefixIfNotSet($tenantPrefix, $store['tag_prefix']);
            }
        }

        $this->app->config->set($config, 'cache');
    }

    /**
     * @param $tenantPrefix
     * @param $prefix
     * @return string
     */
    protected function addPrefixIfNotSet($tenantPrefix, $prefix): string
    {
        return $prefix ? ($tenantPrefix . ':' . $prefix) : $tenantPrefix;
    }
}
